<?php

namespace UserBundle\Entity;

use ComponentBundle\Entity\Id\IdInterface;
use Doctrine\Common\Collections\Collection;
use ComponentBundle\Entity\Img\ImgInterface;
use UserBundle\Entity\PersonalInformation\PersonalInformationInterface;

/**
 * @author Design studio origami <https://origami.ua>
 */
interface UserInterface extends IdInterface, ImgInterface,
    CredentialsHolderInterface, \Symfony\Component\Security\Core\User\UserInterface,
    ToggleableInterface, PersonalInformationInterface
{
    /**
     *
     */
    const DEFAULT_ROLE = 'ROLE_USER';

    /**
     * @return string|null
     */
    public function __toString(): ?string;

    /**
     * @return string
     */
    public function getLocale(): string;

    /**
     * @param string $locale
     */
    public function setLocale(string $locale): void;

    /**
     * Sets the username.
     *
     * @param string|null $username
     */
    public function setUsername(?string $username): void;

    /**
     * @return string|null
     */
    public function getUsername(): ?string;

    /**
     * @param string|null $usernameCanonical
     */
    public function setUsernameCanonical(?string $usernameCanonical): void;

    /**
     * Gets normalized username (should be used in search and sort queries).
     *
     * @return string|null
     */
    public function getUsernameCanonical(): ?string;

    /**
     * Sets the email.
     *
     * @param string|null $email
     */
    public function setEmail(?string $email): void;

    /**
     * Gets email.
     *
     * @return string|null
     */
    public function getEmail(): ?string;

    /**
     * @param string|null $emailCanonical
     */
    public function setEmailCanonical(?string $emailCanonical): void;

    /**
     * Gets normalized email (should be used in search and sort queries).
     *
     * @return string|null
     */
    public function getEmailCanonical(): ?string;

    /**
     * Never use this to check if this user has access to anything!
     *
     * Use the SecurityContext, or an implementation of AccessDecisionManager
     * instead, e.g.
     *
     *         $securityContext->isGranted('ROLE_USER');
     *
     * @param string $role
     *
     * @return bool
     */
    public function hasRole(string $role): bool;

    /**
     * Sets the roles of the user.
     *
     * This overwrites any previous roles.
     *
     * @param array $roles
     *
     */
    public function setRoles(array $roles): void;

    /**
     * Adds a role to the user.
     *
     * @param string $role
     */
    public function addRole(string $role): void;

    /**
     * Removes a role to the user.
     *
     * @param string $role
     */
    public function removeRole(string $role): void;

    /**
     * @return array
     */
    public function getRoles(): array;

    /**
     * @param bool $locked
     */
    public function setLocked(bool $locked): void;

    /**
     * @return bool
     */
    public function isAccountNonLocked(): bool;

    /**
     * @return \DateTimeInterface|null
     */
    public function getExpiresAt(): ?\DateTimeInterface;

    /**
     * @param \DateTimeInterface|null $date
     */
    public function setExpiresAt(?\DateTimeInterface $date): void;

    /**
     * @param string|null $verificationToken
     */
    public function setEmailVerificationToken(?string $verificationToken): void;

    /**
     * @return string|null
     */
    public function getEmailVerificationToken(): ?string;

    /**
     * @param string|null $passwordResetToken
     */
    public function setPasswordResetToken(?string $passwordResetToken): void;

    /**
     * @return string|null
     */
    public function getPasswordResetToken(): ?string;

    /**
     * @param \DateTimeInterface|null $date
     */
    public function setPasswordRequestedAt(?\DateTimeInterface $date): void;

    /**
     * @return \DateTimeInterface|null
     */
    public function getPasswordRequestedAt(): ?\DateTimeInterface;

    /**
     * Checks whether the password reset request has expired.
     *
     * @param int $ttl Requests older than this many seconds will be considered expired
     *
     * @return bool
     */
    public function isPasswordRequestNonExpired($ttl): bool;

    /**
     * @return bool
     */
    public function isVerified(): bool;

    /**
     * @param \DateTimeInterface|null $verifiedAt
     */
    public function setVerifiedAt(?\DateTimeInterface $verifiedAt): void;

    /**
     * @return \DateTimeInterface|null
     */
    public function getVerifiedAt(): ?\DateTimeInterface;

    /**
     * @param \DateTimeInterface|null $time
     */
    public function setLastLogin(?\DateTimeInterface $time): void;

    /**
     * @return \DateTimeInterface|null
     */
    public function getLastLogin(): ?\DateTimeInterface;

    /**
     * @return Collection|UserOAuthInterface[]
     */
    public function getOAuthAccounts(): Collection;

    /**
     * @param string $provider
     *
     * @return UserOAuthInterface|null
     */
    public function getOAuthAccount(string $provider): ?UserOAuthInterface;

    /**
     * @param UserOAuthInterface $oauth
     */
    public function addOAuthAccount(UserOAuthInterface $oauth): void;
}