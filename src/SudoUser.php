<?php

namespace Ballen\Executioner;

/**
 * Executioner Process Execution Library
 * Executioner is a PHP library for executing system processes
 * and applications with the ability to pass extra arguments and read
 *  CLI output results.
 *
 * @author  Bobby Allen <ballen@bobbyallen.me>
 * @license http://opensource.org/licenses/MIT
 * @link    https://github.com/allebb/executioner
 */
class SudoUser
{

    /**
     * The user account name.
     *
     * @var string
     */
    private $user;

    /**
     * The user password (optional).
     *
     * @var null
     */
    private $pass = null;

    /**
     * SudoUser constructor.
     *
     * @param string      $user The username to 'sudo' as.
     * @param null|string $pass Optionally pass in the password for this user to authenticate with.
     */
    public function __construct($user, $pass = null)
    {
        $this->user;
        $this->pass;
    }

    /**
     * Returns if this sudo user should authenticate with a password or not.
     *
     * @return bool
     */
    public function hasPassword()
    {
        return (isset($this->pass) ? true : false);
    }

    /**
     * Return the user account name.
     *
     * @return string
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Return the user account password.
     *
     * @return null
     */
    public function getPassword()
    {
        return $this->pass;
    }

}