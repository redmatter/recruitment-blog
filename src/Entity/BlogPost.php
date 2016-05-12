<?php

namespace RedMatter\InterestingBlog\Entity;

class BlogPost
{
    /** @var int */
    public $id;

    /** @var User */
    public $user;

    /** @var string */
    public $subject;

    /** @var string */
    public $message;

    /** @var string */
    public $created;
}
