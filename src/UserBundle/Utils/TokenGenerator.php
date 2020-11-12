<?php

namespace UserBundle\Utils;

/**
 * @author Design studio origami <https://origami.ua>
 */
final class TokenGenerator implements TokenGeneratorInterface
{
    /**
     * {@inheritdoc}
     */
    public function generateToken(): string
    {
        return rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
    }
}
