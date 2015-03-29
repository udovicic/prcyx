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

    // TODO: Implement config getters
}