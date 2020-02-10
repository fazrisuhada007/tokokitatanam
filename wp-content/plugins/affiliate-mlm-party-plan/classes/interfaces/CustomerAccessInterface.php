<?php

namespace Unisho\Sb;

/**
 * @api
 */
interface CustomerAccessInterface {
    /**
     * Customer username(email)
     *
     * @return string
     */
    public function getUsername();

    /**
     * Customer password
     *
     * @return string
     */
    public function getPassword();

    /**
     * Set customer username(email)
     *
     * @param string $username
     * @return $this
     */
    public function setUsername($username);

    /**
     * Set customer password
     *
     * @param string $password
     * @return $this
     */
    public function setPassword($password);
}
