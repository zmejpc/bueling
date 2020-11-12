<?php

namespace UserBundle\Utils;

use UserBundle\Entity\User;
use UserBundle\Entity\UserInterface;
use UserBundle\Entity\UserMailSetting;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Persistence\ObjectRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @author Design studio origami <https://origami.ua>
 */
final class UserManager implements UserManagerInterface
{
    /**
     * @var PasswordUpdaterInterface
     */
    private $passwordUpdater;

    /**
     * @var CanonicalFieldsUpdater
     */
    private $canonicalFieldsUpdater;

    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var string
     */
    private $class;

    private $container;

    public function __construct(
        PasswordUpdaterInterface $passwordUpdater, CanonicalFieldsUpdater $canonicalFieldsUpdater,
        EntityManagerInterface $em, ContainerInterface $container
    )
    {
        $this->class = new User();
        $this->objectManager = $em;
        $this->container=$container;
        $this->passwordUpdater = $passwordUpdater;
        $this->canonicalFieldsUpdater = $canonicalFieldsUpdater;
    }

    public function createUserMailSetting(string $systemName): UserMailSetting
    {
        $locale = $this->container->getParameter('locale');
        $object = new UserMailSetting();
        $object->setSmtpHost($this->container->getParameter('MAILER_HOST'));
        $object->setSmtpPassword($this->container->getParameter('MAILER_PASSWORD'));
        $object->setSmtpPort('25');
        $object->setSmtpUsername($this->container->getParameter('MAILER_USERNAME'));
        $object->setSystemName($systemName);
        $object->translate($locale)->setSenderName($this->container->getParameter('MAILER_SENDER_NAME'));
        $object->translate($locale)->setManagerSubject('Manager Subject');
        $object->translate($locale)->setMessageSubject('Message Subject');
        $object->translate($locale)->setMessageBody('Message Body');
        $object->translate($locale)->setSuccessFlashTitle('Success Flash Title');
        $object->translate($locale)->setSuccessFlashMessage('Success Flash Message');
        $object->mergeNewTranslations();
        $this->objectManager->persist($object);
        $this->objectManager->flush();

        return $object;
    }

    public function getUserMailSettingElementBySystemName(string $systemName): UserMailSetting
    {
        $object = $this->objectManager->getRepository(UserMailSetting::class)
            ->getElementBySystemName($systemName);

        if (!$object) {
            $object = $this->createUserMailSetting($systemName);
        }

        return $object;
    }

    /**
     * @return ObjectRepository
     */
    protected function getRepository()
    {
        return $this->objectManager->getRepository(User::class);
    }

    /**
     * Creates an empty user instance.
     *
     * @return UserInterface
     */
    public function createUser(): UserInterface
    {
        $user = $this->class;

        return $user;
    }

    /**
     * Deletes a user.
     *
     * @param UserInterface $user
     */
    public function deleteUser(UserInterface $user)
    {
        $this->objectManager->remove($user);
        $this->objectManager->flush();
    }

    /**
     * Finds one user by the given criteria.
     *
     * @param array $criteria
     *
     * @return UserInterface
     */
    public function findUserBy(array $criteria)
    {
        return $this->getRepository()->findOneBy($criteria);
    }

    /**
     * Returns a collection with all user instances.
     */
    public function findUsers(): array
    {
        return $this->getRepository()->findAll();
    }

    /**
     * Reloads a user.
     *
     * @param UserInterface $user
     */
    public function reloadUser(UserInterface $user)
    {
        $this->objectManager->refresh($user);
    }

    /**
     * @param UserInterface $user
     * @param bool $andFlush
     */
    public function updateUser(UserInterface $user, bool $andFlush = true)
    {
        $this->updateCanonicalFields($user);
        $this->updatePassword($user);

        $this->objectManager->persist($user);
        if ($andFlush) {
            $this->objectManager->flush();
        }
    }

    /**
     * Finds a user by its email.
     *
     * @param string $email
     *
     * @return UserInterface or null if user does not exist
     */
    public function findUserByEmail(string $email): ?UserInterface
    {
        return $this->findUserBy(['emailCanonical' => $this->canonicalFieldsUpdater->canonicalizeEmail($email)]);
    }

    /**
     * Find a user by its username.
     *
     * @param string $username
     *
     * @return UserInterface or null if user does not exist
     */
    public function findUserByUsername(string $username): ?UserInterface
    {
        return $this->findUserBy(['usernameCanonical' => $this->canonicalFieldsUpdater->canonicalizeUsername($username)]);
    }

    /**
     * Finds a user by its username or email.
     *
     * @param string $usernameOrEmail
     *
     * @return UserInterface or null if user does not exist
     */
    public function findUserByUsernameOrEmail(string $usernameOrEmail): ?UserInterface
    {
        if (preg_match('/^.+\@\S+\.\S+$/', $usernameOrEmail)) {
            return $this->findUserByEmail($usernameOrEmail);
        }

        return $this->findUserByUsername($usernameOrEmail);
    }

    /**
     * Finds a user by its confirmationToken.
     *
     * @param string $token
     *
     * @return UserInterface or null if user does not exist
     */
    public function findUserByConfirmationToken(string $token): ?UserInterface
    {
        return $this->findUserBy(['emailVerificationToken' => $token]);
    }

    /**
     * @param string $token
     * @return UserInterface|null
     */
    public function findUserByPasswordResetConfirmationToken(string $token): ?UserInterface
    {
        return $this->findUserBy(['passwordResetToken' => $token]);
    }

    /**
     * Updates the canonical username and email fields for a user.
     *
     * @param UserInterface $user
     */
    public function updateCanonicalFields(UserInterface $user)
    {
        $this->canonicalFieldsUpdater->updateCanonicalFields($user);
    }

    /**
     * Updates a user password if a plain password is set.
     *
     * @param UserInterface $user
     */
    public function updatePassword(UserInterface $user)
    {
        $this->passwordUpdater->hashPassword($user);
    }

    /**
     * @return PasswordUpdaterInterface
     */
    protected function getPasswordUpdater()
    {
        return $this->passwordUpdater;
    }

    /**
     * @return CanonicalFieldsUpdater
     */
    protected function getCanonicalFieldsUpdater()
    {
        return $this->canonicalFieldsUpdater;
    }
}
