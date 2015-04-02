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

namespace Framework\Model;

/**
 * Class Config
 *
 * @category    Inchoo
 * @package     Framework\Model
 * @author      Stjepan Udovičić <udovicic.stjepan@gmail.com>
 * @copyright   Inchoo (http://inchoo.net)
 * @license     http://www.gnu.org/licenses/gpl-3.0.html  GNU GENERAL PUBLIC LICENSE (Version 3)
 */
class Config
{
    /** @var array Config holder */
    protected $_config;

    /**
     * Read package configuration
     *
     * If package is null, default config is assumed
     *
     * @param string|null $package Package name
     */
    public function loadConfig($package = null)
    {
        $path = BP . 'App/' . $package;
        $package = strtolower($package);

        // App config
        if (!$package) {
            $path .= '..';
            $package = 'base';
        }

        // Config already loaded
        if (isset($this->_config[$package])) return;

        // false if file does not exists
        $this->_config[$package] = @include $path . '/etc/config.php';
    }

    /**
     * Retrieve config setting
     *
     * @param   string      $path   Path is separated by /
     * @param   null|mixed  $default
     * @return  mixed|null
     */
    public function getConfig($path, $default=null)
    {
        $_path = explode('/', $path);

        // If package not specified, prepend base
        if (!isset($this->_config[$_path[0]])) {
            array_unshift($_path, 'base');
        }

        // Fetch value from path
        $temp = &$this->_config;
        foreach($_path as $key) {
            if (!isset($temp[$key])) return $default; // Unset path
            $temp = &$temp[$key];
        }

        return $temp;
    }

    /**
     * Retrieve config setting as boolean
     * 
     * @param   string  $path
     * @return  bool
     */
    public function getConfigFlag($path)
    {
        return ($this->getConfig($path, false)) ? true : false;
    }
}