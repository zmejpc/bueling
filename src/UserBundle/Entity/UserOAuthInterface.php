<?php

namespace UserBundle\Entity;

use ComponentBundle\Entity\Id\IdInterface;

/**
 * @author Design studio origami <https://origami.ua>
 */
interface UserOAuthInterface extends IdInterface, UserAwareInterface
{
    /**
     * @return string|null
     */
    public function getProvider(): ?string;

    /**
     * @param string|null $provider
     */
    public function setProvider(?string $provider): void;

    /**
     * @return string|null
     */
    public function getIdentifier(): ?string;

    /**
     * @param string|null $identifier
     */
    public function setIdentifier(?string $identifier): void;

    /**
     * @return string|null
     */
    public function getAccessToken(): ?string;

    /**
     * @param string|null $accessToken
     */
    public function setAccessToken(?string $accessToken): void;

    /**
     * @return string|null
     */
    public function getRefreshToken(): ?string;

    /**
     * @param string|null $refreshToken
     */
    public function setRefreshToken(?string $refreshToken): void;
}
