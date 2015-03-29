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
 * Class Object
 *
 * General purpose object
 *
 * @category    Inchoo
 * @package     Framework\Model
 * @author      Stjepan Udovičić <udovicic.stjepan@gmail.com>
 * @copyright   Inchoo (http://inchoo.net)
 * @license     http://www.gnu.org/licenses/gpl-3.0.html  GNU GENERAL PUBLIC LICENSE (Version 3)
 */
class Object
{
    /**
     * @var array Internal data storage
     */
    protected $_data = array();

    /**
     * Set data to object internal storage
     *
     * @param   string  $key    Data identifier
     * @param   mixed   $value  Value to be stored
     * @return  Object  $this
     */
    public function setData($key, $value)
    {
        $this->_data[$key] = $value;
        return $this;
    }

    /**
     * Retrieve data from internal storage
     *
     * @param   string  $key    Data identifier
     * @return  mixed|null  Stored data
     */
    public function getData($key)
    {
        return (isset($this->_data[$key])) ? $this->_data[$key] : null;
    }

    /**
     * Unset data stored under specified identifier
     *
     * @param   string  $key Data identifier
     * @return  Object $this
     */
    public function unsetData($key)
    {
        unset($this->_data[$key]);
        return $this;
    }

    /**
     * Wrapper for getter/setter function, Magento style
     *
     * @param   string  $method Method to be called
     * @param   mixed   $args   Parameters for method
     * @return  mixed|null|Object
     * @throws  \Exception
     */
    public function __call($method, $args)
    {
        $key = $this->_underscore(substr($method, 3));

        switch (substr($method, 0, 3)) {
            case 'get':
                $result = $this->getData($key, isset($args[0]) ? $args[0] : null);
                break;
            case 'set':
                $result = $this->setData($key, isset($args[0]) ? $args[0] : null);
                break;
            case 'uns':
                $result = $this->unsetData($key);
                break;
            default:
                throw new \Exception(
                    "Invalid method call: " . get_class($this) . "::" . $method
                );
        }

        return $result;
    }

    /**
     * Converts camel cased names to snake cased
     *
     * @param   string  $key Camel cased key
     * @return  string  Snake cased version of key
     */
    protected function _underscore($key)
    {
        return strtolower(preg_replace('/(.)([A-Z])/', "$1_$2", $key));
    }
}