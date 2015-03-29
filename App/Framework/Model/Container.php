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

    // TODO: Make from config
    // TODO: Make by reflection
}