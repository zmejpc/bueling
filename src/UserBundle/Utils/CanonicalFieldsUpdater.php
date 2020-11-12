<?php

namespace UserBundle\Utils;

use UserBundle\Entity\UserInterface;

/**
 * Class updating the canonical fields of the user.
 * @author Design studio origami <https://origami.ua>
 */
final class CanonicalFieldsUpdater
{
    /**
     * @var CanonicalizerInterface
     */
    private $usernameCanonicalizer;

    /**
     * @var CanonicalizerInterface
     */
    private $emailCanonicalizer;

    /**
     * CanonicalFieldsUpdater constructor.
     * @param CanonicalizerInterface $usernameCanonicalizer
     * @param CanonicalizerInterface $emailCanonicalizer
     */
    public function __construct(
        CanonicalizerInterface $usernameCanonicalizer, CanonicalizerInterface $emailCanonicalizer
    )
    {
        $this->usernameCanonicalizer = $usernameCanonicalizer;
        $this->emailCanonicalizer = $emailCanonicalizer;
    }

    /**
     * @param UserInterface $user
     */
    public function updateCanonicalFields(UserInterface $user): void
    {
        $user->setUsernameCanonical($this->canonicalizeUsername($user->getUsername()));
        $user->setEmailCanonical($this->canonicalizeEmail($user->getEmail()));
    }

    /**
     * @param string $email
     * @return string
     */
    public function canonicalizeEmail(string $email): string
    {
        return $this->emailCanonicalizer->canonicalize($email);
    }

    /**
     * @param string $username
     * @return string
     */
    public function canonicalizeUsername(string $username): string
    {
        return $this->usernameCanonicalizer->canonicalize($username);
    }
}
