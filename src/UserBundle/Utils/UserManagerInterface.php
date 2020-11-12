<?php

namespace UserBundle\Utils;

use UserBundle\Entity\UserInterface;

/**
 * @author Design studio origami <https://origami.ua>
 */
interface UserManagerInterface
{
    /**
     * Creates an empty user instance.
     *
     * @return UserInterface
     */
    public function createUser(): UserInterface;

    /**
     * Deletes a user.
     *
     * @param UserInterface $user
     */
    public function deleteUser(UserInterface $user);

    /**
     * Finds one user by the given criteria.
     *
     * @param array $criteria
     *
     * @return UserInterface
     */
    public function findUserBy(array $criteria);

    /**
     * Find a user by its username.
     *
     * @param string $username
     *
     * @return UserInterface or null if user does not exist
     */
    public function findUserByUsername(string $username): ?UserInterface;

    /**
     * Finds a user by its email.
     *
     * @param string $email
     *
     * @return UserInterface or null if user does not exist
     */
    public function findUserByEmail(string $email): ?UserInterface;

    /**
     * Finds a user by its username or email.
     *
     * @param string $usernameOrEmail
     *
     * @return UserInterface or null if user does not exist
     */
    public function findUserByUsernameOrEmail(string $usernameOrEmail): ?UserInterface;

    /**
     * Finds a user by its confirmationToken.
     *
     * @param string $token
     *
     * @return UserInterface or null if user does not exist
     */
    public function findUserByConfirmationToken(string $token): ?UserInterface;

    /**
     * @param string $token
     * @return UserInterface|null
     */
    public function findUserByPasswordResetConfirmationToken(string $token): ?UserInterface;

    /**
     * Returns a collection with all user instances.
     */
    public function findUsers(): array;

    /**
     * Reloads a user.
     *
     * @param UserInterface $user
     */
    public function reloadUser(UserInterface $user);

    /**
     * @param UserInterface $user
     * @param bool $andFlush
     * @return mixed
     */
    public function updateUser(UserInterface $user, bool $andFlush = true);

    /**
     * Updates the canonical username and email fields for a user.
     *
     * @param UserInterface $user
     */
    public function updateCanonicalFields(UserInterface $user);

    /**
     * Updates a user password if a plain password is set.
     *
     * @param UserInterface $user
     */
    public function updatePassword(UserInterface $user);
}
