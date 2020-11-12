<?php

namespace UserBundle\Entity;

/**
 * @author Design studio origami <https://origami.ua>
 */
interface CredentialsHolderInterface
{
    /**
     * Gets the plain password.
     *
     * @return string|null
     */
    public function getPlainPassword(): ?string;

    /**
     * Sets the plain password.
     *
     * @param string|null $password
     */
    public function setPlainPassword(?string $password): void;

    /**
     * Returns the password used to authenticate the user.
     *
     * This should be the encoded password. On authentication, a plain-text
     * password will be salted, encoded, and then compared to this value.
     *
     * @return string|null
     */
    public function getPassword(): ?string;

    /**
     * Sets the hashed password.
     *
     * @param string|null $password
     */
    public function setPassword(?string $password): void;

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null
     */
    public function getSalt(): ?string;

    /**
     * @param string|null $salt
     */
    public function setSalt(?string $salt): void;

    /**
     * @return \DateTimeInterface|null
     */
    public function getCredentialsExpireAt(): ?\DateTimeInterface;

    /**
     * @param \DateTimeInterface|null $date
     */
    public function setCredentialsExpireAt(?\DateTimeInterface $date): void;

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials(): void;

    /**
     * @return bool
     */
    public function isCredentialsNonExpired(): bool;

    /**
     * @return bool
     */
    public function isAccountNonExpired(): bool;
}
