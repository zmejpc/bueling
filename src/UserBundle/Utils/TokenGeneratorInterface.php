<?php

namespace UserBundle\Utils;

/**
 * @author Design studio origami <https://origami.ua>
 */
interface TokenGeneratorInterface
{
    /**
     * @return string
     */
    public function generateToken(): string;
}
