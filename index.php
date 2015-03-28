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

define('BP', __DIR__ . '/');
define('DS', DIRECTORY_SEPARATOR);
define('PS', PATH_SEPARATOR);
define('DEVELOP', isset($_SERVER['DEVELOPER_MODE']) ? true : false);

// Reset include paths
set_include_path(implode(PS, array(
    BP . 'App/',
    BP . 'lib/'
)));

// Setup autoloader
spl_autoload_register(function($class) {
    include str_replace('\\', DS, $class) . '.php';
});

// PHP setting for application behaviour
if (DEVELOP) {
    error_reporting(-1);
    ini_set('display_errors', 1);
    ini_set('log_errors', 0);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    ini_set('error_log', BP . 'var/log/php-error.log');
}

(new Framework\Model\Container())->boot();