<?php

namespace UserBundle\Security;

use UserBundle\Entity\UserInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Http\RememberMe\RememberMeServicesInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Session\SessionAuthenticationStrategyInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * @author Design studio origami <https://origami.ua>
 */
class LoginManager implements LoginManagerInterface
{
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var UserChecker
     */
    private $userChecker;

    /**
     * @var SessionAuthenticationStrategyInterface
     */
    private $sessionStrategy;

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var RememberMeServicesInterface
     */
    private $rememberMeService;

    /**
     * LoginManager constructor.
     *
     * @param TokenStorageInterface $tokenStorage
     * @param UserChecker $userChecker
     * @param SessionAuthenticationStrategyInterface $sessionStrategy
     * @param RequestStack $requestStack
     * @param RememberMeServicesInterface|null $rememberMeService
     */
    public function __construct(
        TokenStorageInterface $tokenStorage, UserChecker $userChecker,
        SessionAuthenticationStrategyInterface $sessionStrategy, RequestStack $requestStack,
        RememberMeServicesInterface $rememberMeService = null
    )
    {
        $this->tokenStorage = $tokenStorage;
        $this->userChecker = $userChecker;
        $this->sessionStrategy = $sessionStrategy;
        $this->requestStack = $requestStack;
        $this->rememberMeService = $rememberMeService;
    }

    /**
     * {@inheritdoc}
     */
    final public function logInUser(string $firewallName, UserInterface $user, Response $response = null)
    {
        $firewallName = $firewallName ?? 'main';

        $this->userChecker->checkPreAuth($user);
        $this->userChecker->checkPostAuth($user);

        $token = $this->createToken($firewallName, $user);
        $request = $this->requestStack->getCurrentRequest();

        if (null !== $request) {
            $this->sessionStrategy->onAuthentication($request, $token);

            if (null !== $response && null !== $this->rememberMeService) {
                $this->rememberMeService->loginSuccess($request, $response, $token);
            }
        }

        $this->tokenStorage->setToken($token);
    }

    /**
     * @param string $firewall
     * @param UserInterface $user
     *
     * @return UsernamePasswordToken
     */
    protected function createToken(string $firewall, UserInterface $user): UsernamePasswordToken
    {
        return new UsernamePasswordToken($user, null, $firewall, $user->getRoles());
    }
}
