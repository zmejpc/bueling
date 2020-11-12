<?php

namespace FrontendBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class Contacts
{
    public $name;
    public $subject;

    /**
     * @Assert\NotBlank
     */
    public $email;

    /**
     * @Assert\NotBlank
     */
    public $text;
}
