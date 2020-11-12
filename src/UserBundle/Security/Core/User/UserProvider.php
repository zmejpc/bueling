<?php

namespace UserBundle\Security\Core\User;

use UserBundle\Entity\User;
use UserBundle\Utils\UserManagerInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\Security\Core\User\UserInterface;
use HWI\Bundle\OAuthBundle\Connect\AccountConnectorInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use HWI\Bundle\OAuthBundle\Security\Core\Exception\AccountNotLinkedException;
use HWI\Bundle\OAuthBundle\Security\Core\User\OAuthAwareUserProviderInterface;

/**
 * Class providing a bridge to use the user provider with HWIOAuth.
 *
 * In order to use the class as a connector, the appropriate setters for the
 * property mapping should be available.
 *
 * @author Alexander <iam.asm89@gmail.com>
 */
final class UserProvider implements UserProviderInterface, AccountConnectorInterface, OAuthAwareUserProviderInterface
{
    /**
     * @var UserManagerInterface
     */
    protected $userManager;

    /**
     * @var array
     */
    protected $properties = ['identifier' => 'email'];

    /**
     * @var PropertyAccessor
     */
    protected $accessor;

//    /**
//     * Constructor.
//     *
//     * @param UserManagerInterface $userManager fOSUB user provider
//     * @param array                $properties  property mapping
//     */
//    public function __construct(UserManagerInterface $userManager, array $properties)

    /**
     * UserProvider constructor.
     * @param UserManagerInterface $userManager
     */
    public function __construct(UserManagerInterface $userManager)
    {
        $this->userManager = $userManager;
//        $this->properties = array_merge($this->properties, $properties);
        $this->accessor = PropertyAccess::createPropertyAccessor();
    }

    public function loadUserByUsername($username): ?UserInterface
    {
        return $this->userManager->findUserByUsername($username);
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        $username = $response->getUsername();

        $user = $this->userManager->findUserBy([$this->getProperty($response) => $username]);
        if (null === $user || null === $username) {
            throw new AccountNotLinkedException(sprintf("User '%s' not found.", $username));
        }

        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public function connect(UserInterface $user, UserResponseInterface $response)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Expected an instance of FOS\UserBundle\Model\User, but got "%s".', get_class($user)));
        }

        $property = $this->getProperty($response);
        $username = $response->getUsername();

        $previousUser = $this->userManager->findUserBy([$property => $username]);

        if (null !== $previousUser) {
            $this->disconnect($previousUser, $response);
        }

        if ($this->accessor->isWritable($user, $property)) {
            $this->accessor->setValue($user, $property, $username);
        } else {
            throw new \RuntimeException(sprintf('Could not determine access type for property "%s".', $property));
        }

        $this->userManager->updateUser($user);
    }

    /**
     * Disconnects a user.
     *
     * @param UserInterface $user
     * @param UserResponseInterface $response
     */
    public function disconnect(UserInterface $user, UserResponseInterface $response)
    {
        $property = $this->getProperty($response);

        $this->accessor->setValue($user, $property, null);
        $this->userManager->updateUser($user, true);
    }

    /**
     * {@inheritdoc}
     */
    public function refreshUser(UserInterface $user)
    {
        $identifier = $this->properties['identifier'];
        if (!$user instanceof User || !$this->accessor->isReadable($user, $identifier)) {
            throw new UnsupportedUserException(sprintf('Expected an instance of FOS\UserBundle\Model\User, but got "%s".', get_class($user)));
        }

        $userId = $this->accessor->getValue($user, $identifier);
        $user = $this->userManager->findUserBy([$identifier => $userId]);

        if (null === $user) {
            throw new UsernameNotFoundException(sprintf('User with ID "%d" could not be reloaded.', $userId));
        }

        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsClass($class)
    {
        $userClass = $this->userManager->getClass();

        return $userClass === $class || is_subclass_of($class, $userClass);
    }

    /**
     * Gets the property for the response.
     *
     * @param UserResponseInterface $response
     *
     * @return string
     *
     * @throws \RuntimeException
     */
    protected function getProperty(UserResponseInterface $response)
    {
        $resourceOwnerName = $response->getResourceOwner()->getName();

        if (!isset($this->properties[$resourceOwnerName])) {
            throw new \RuntimeException(sprintf("No property defined for entity for resource owner '%s'.", $resourceOwnerName));
        }

        return $this->properties[$resourceOwnerName];
    }
}
