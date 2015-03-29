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
 * Class Request
 *
 * Handling all request related action
 *
 * @category    Inchoo
 * @package     Framework\Model
 * @author      Stjepan Udovičić <udovicic.stjepan@gmail.com>
 * @copyright   Inchoo (http://inchoo.net)
 * @license     http://www.gnu.org/licenses/gpl-3.0.html  GNU GENERAL PUBLIC LICENSE (Version 3)
 */
class Request
{
    /**
     * Retrieve data from REQUEST variable
     *
     * @param   string  $key    Data identifier
     * @return  mixed|null
     */
    public function getParam($key)
    {
        return (isset($_REQUEST[$key])) ? $_REQUEST[$key] : null;
    }

    /**
     * Retrieve data from GET variable
     *
     * @param   string  $key    Data identifier
     * @return  mixed|null
     */
    public function getGet($key)
    {
        return (isset($_GET[$key])) ? $_GET[$key] : null;
    }

    /**
     * Retrieve data from POST variable
     *
     * @param   string  $key    Data identifier
     * @return  mixed|null
     */
    public function getPost($key)
    {
        return (isset($_POST[$key])) ? $_POST[$key] : null;
    }

    /**
     * Is current request under HTTPS
     *
     * @return bool
     */
    public function isSecure()
    {
        return (isset($_SERVER['HTTPS'])) ? true : false;
    }

    // TODO: HTTP_USER_AGENT
    // TODO: REMOTE_ADDR
    // TODO: REQUEST_URI
}