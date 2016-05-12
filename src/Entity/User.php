<?php

namespace RedMatter\InterestingBlog\Entity;

class User
{
    const STATUS_ENABLED = 'ENABLED';
    const STATUS_SUSPENDED = 'SUSPENDED';

    /** @var string */
    public $id;

    /** @var string */
    public $email_address;

    /** @var string */
    public $password;

    /** @var string */
    public $status;

    /**
     * @param string $password Plain-text password from user input
     * @return bool True if the password (when hashed) matches the stored hashed
     */
    public function validatePassword($password)
    {
        $valid = true;

        if (!empty($password) && $this->password !== md5($password)) {
            $valid = false;
        }

        return $valid;
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        if ($this->status == self::STATUS_ENABLED) {
            return true;
        }

        return false;
    }

    /**
     * @param bool $is_enabled
     */
    public function setEnabled($is_enabled)
    {
        if ($is_enabled) {
            $this->status = self::STATUS_ENABLED;
        } else {
            $this->status = self::STATUS_SUSPENDED;
        }
    }
}
