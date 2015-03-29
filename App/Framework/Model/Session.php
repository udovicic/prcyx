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
 * Class Session
 *
 * @category    Inchoo
 * @package     Framework\Model
 * @author      Stjepan Udovičić <udovicic.stjepan@gmail.com>
 * @copyright   Inchoo (http://inchoo.net)
 * @license     http://www.gnu.org/licenses/gpl-3.0.html  GNU GENERAL PUBLIC LICENSE (Version 3)
 */
class Session
extends Object
{
    /** @var bool Use encrypted session */
    protected $_secure = false;

    /** @var Container IoC container for generating various session handlers */
    protected $_container;

    /**
     * Object dependencies
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->_container = $container;
    }

    /**
     * @param bool  $secure Use encrypted session
     */
    public function useSecure($secure)
    {
        $this->_secure = $secure;
    }

    /**
     * Start the session
     */
    public function start()
    {
        // TODO: prevent re-starting of session

        if ($this->_secure) {
            session_set_save_handler($this->_container->make(
                'Framework\Model\Session\Secure'
            ));
        }

        session_save_path(BP . 'var/session');
        session_start();
    }

    // TODO: getter/setter functions
}