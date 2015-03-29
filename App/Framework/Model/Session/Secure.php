<?php
/**
 * prcyx
 * Copyright (C) 2015  Stjepan Udovičić <udovicic.stjepan@gmail.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Framework\Model\Session;

/**
 * Class Secure
 *
 * The encryption is built using mcrypt extension
 * and the randomness is managed by openssl
 * The default encryption algorithm is AES (Rijndael-128)
 * and we use CBC+HMAC (Encrypt-then-mac) with SHA-256
 *
 * Based on implementation by Enrico Zimuel (enrico@zimuel.it)
 *
 * @category    Inchoo
 * @package     Framework\Model\Session
 * @author      Stjepan Udovičić <udovicic.stjepan@gmail.com>
 * @copyright   Inchoo (http://inchoo.net)
 * @license     http://www.gnu.org/licenses/gpl-3.0.html  GNU GENERAL PUBLIC LICENSE (Version 3)
 */
class Secure
implements \SessionHandlerInterface
{
    /** @var string  Encryption algorithm */
    protected $_algo= MCRYPT_RIJNDAEL_128;

    /** @var string Key for encryption/decryption */
    protected $_key;

    /** @var string Key for HMAC authentication */
    protected $_auth;

    /** @var string Path of the session file */
    protected $_path;

    /** @var string Session name (optional) */
    protected $_name;

    /** @var integer Size of the IV vector for encryption */
    protected $_ivSize;

    /** @var string ookie variable name of the encryption + auth key */
    protected $_keyName;

    /**
     * Generate a random key using openssl
     * fallback to mcrypt_create_iv
     *
     * @param  integer $length
     * @return string
     */
    protected function _randomKey($length=32) {
        if(function_exists('openssl_random_pseudo_bytes')) {
            $rnd = openssl_random_pseudo_bytes($length, $strong);
            if ($strong === true) {
                return $rnd;
            }
        }
        return mcrypt_create_iv($length, MCRYPT_DEV_URANDOM);
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        if (!extension_loaded('mcrypt')) {
            throw new \Exception(
                'The SecureSession class needs the Mcrypt PHP extension.'
            );
        }
    }

    /**
     * Open the session
     *
     * @param  string $save_path
     * @param  string $session_name
     * @return bool
     */
    public function open($save_path, $session_name)
    {
        $this->_path    = $save_path.'/';
        $this->_name    = $session_name;
        $this->_keyName = "KEY_$session_name";
        $this->_ivSize  = mcrypt_get_iv_size($this->_algo, MCRYPT_MODE_CBC);

        if (empty($_COOKIE[$this->_keyName]) || strpos($_COOKIE[$this->_keyName],':')===false) {
            $keyLength    = mcrypt_get_key_size($this->_algo, MCRYPT_MODE_CBC);
            $this->_key   = self::_randomKey($keyLength);
            $this->_auth  = self::_randomKey(32);
            $cookie_param = session_get_cookie_params();
            setcookie(
                $this->_keyName,
                base64_encode($this->_key) . ':' . base64_encode($this->_auth),
                // if session cookie lifetime > 0 then add to current time; otherwise leave it as zero, honoring zero's special meaning: expire at browser close.
                ($cookie_param['lifetime'] > 0) ? time() + $cookie_param['lifetime'] : 0,
                $cookie_param['path'],
                $cookie_param['domain'],
                $cookie_param['secure'],
                $cookie_param['httponly']
            );
        } else {
            list ($this->_key, $this->_auth) = explode (':',$_COOKIE[$this->_keyName]);
            $this->_key  = base64_decode($this->_key);
            $this->_auth = base64_decode($this->_auth);
        }
        return true;
    }

    /**
     * Close the session
     *
     * @return bool
     */
    public function close()
    {
        return true;
    }

    /**
     * Read and decrypt the session
     *
     * @param  integer $id
     * @return string
     */
    public function read($id)
    {
        $sess_file = $this->_path.$this->_name."_$id";
        if (!file_exists($sess_file)) {
            return false;
        }
        $data      = file_get_contents($sess_file);
        list($hmac, $iv, $encrypted)= explode(':',$data);
        $iv        = base64_decode($iv);
        $encrypted = base64_decode($encrypted);
        $newHmac   = hash_hmac('sha256', $iv . $this->_algo . $encrypted, $this->_auth);
        if ($hmac !== $newHmac) {
            return false;
        }
        $decrypt = mcrypt_decrypt(
            $this->_algo,
            $this->_key,
            $encrypted,
            MCRYPT_MODE_CBC,
            $iv
        );
        return rtrim($decrypt, "\0");
    }

    /**
     * Encrypt and write the session
     *
     * @param integer $id
     * @param string $data
     * @return bool
     */
    public function write($id, $data)
    {
        $sess_file = $this->_path . $this->_name . "_$id";
        $iv        = mcrypt_create_iv($this->_ivSize, MCRYPT_DEV_URANDOM);
        $encrypted = mcrypt_encrypt(
            $this->_algo,
            $this->_key,
            $data,
            MCRYPT_MODE_CBC,
            $iv
        );
        $hmac  = hash_hmac('sha256', $iv . $this->_algo . $encrypted, $this->_auth);
        $bytes = file_put_contents($sess_file, $hmac . ':' . base64_encode($iv) . ':' . base64_encode($encrypted));
        return ($bytes !== false);
    }

    /**
     * Destoroy the session
     *
     * @param int $id
     * @return bool
     */
    public function destroy($id)
    {
        $sess_file = $this->_path . $this->_name . "_$id";
        setcookie ($this->_keyName, '', time() - 3600);
        return(@unlink($sess_file));
    }

    /**
     * Garbage Collector
     *
     * @param int $max
     * @return bool
     */
    public function gc($max)
    {
        foreach (glob($this->_path . $this->_name . '_*') as $filename) {
            if (filemtime($filename) + $max < time()) {
                @unlink($filename);
            }
        }
        return true;
    }
}