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
 * Class Container
 *
 * IoC container
 *
 * @category    Inchoo
 * @package     Framework\Model
 * @author      Stjepan Udovičić <udovicic.stjepan@gmail.com>
 * @copyright   Inchoo (http://inchoo.net)
 * @license     http://www.gnu.org/licenses/gpl-3.0.html  GNU GENERAL PUBLIC LICENSE (Version 3)
 */
class Container
{
    /** @var array Interface implementation preferences */
    protected $_preference;

    /** @var array Previously created objects */
    protected $_loaded;

    /** @var Config Configuration model */
    protected $_config;

    /**
     * IoC container initialization
     */
    public function __construct()
    {
        $this->_config = $this->make('Framework\Model\Config');
        $this->_config->loadConfig();
    }

    /**
     * Replace \ with _ to make usable names for stage in array
     *
     * @param   string  $key Class name
     * @return  string
     */
    protected function _underscore($key)
    {
        return str_replace('\\', '_', $key);
    }

    /**
     * Returns instance of requested object. Defaults to singletons,
     * unless specified otherwise by $newInstance
     *
     * @param   string  $key            Name of class to be instancieted
     * @param   bool    $newInstance    If true, always return new instance
     * @return  mixed   Return newly created object
     * @throws \Exception
     */
    public function make($key, $newInstance = false)
    {
        // TODO: To be implemented...
    }

    protected function _buildWithConfig($object)
    {
        // TODO: To be implemented...
    }

    /**
     * Build request object by analyzing constructor with reflection.
     *
     * @param string $object Class name
     *
     * @return mixed Instance of requested object
     * @throws \Exception
     */
    protected function _buildWithReflection($object)
    {
        // TODO: fetch default interface implementation from config

        if (!is_string($object) || !class_exists($object)) {
            throw new \Exception("Object is not a class");
        }

        $hint = $this->_underscore($object);
        if (isset($this->_loaded[$hint])) {
            return $this->_loaded[$hint];
        }

        $ref = new \ReflectionClass($object);

        if (!$constructor = $ref->getConstructor()) {
            return new $object; // No constructor specified
        }

        // Resolve constructor parameters
        $objParams = array();

        /** @var \ReflectionParameter $param */
        foreach ($constructor->getParameters() as $param) {
            if ($class = $param->getClass()) {
                $className = $class->getName();

                if (isset($this->_preference[$className])) {
                    $className = $this->_preference[$className];
                }

                $objParams[] = $this->_buildWithReflection($className);
            } else {
                // Parameter is non-object
                $objParams[] = array_shift($args);
            }
        }

        // Create new instance and store for future use
        $this->_loaded[$hint] = $ref->newInstanceArgs($objParams);

        return $this->_loaded[$hint];
    }
}