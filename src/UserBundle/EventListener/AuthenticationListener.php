<?php

namespace UserBundle\EventListener;

use UserBundle\UserEvents;
use UserBundle\Event\UserEvent;
use UserBundle\Event\FilterUserResponseEvent;
use UserBundle\Security\LoginManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Exception\AccountStatusException;

/**
 * @author Design studio origami <https://origami.ua>
 */
class AuthenticationListener implements EventSubscriberInterface
{
    /**
     * @var LoginManagerInterface
     */
    private $loginManager;

    /**
     * @var string
     */
    private $firewallName;

    /**
     * AuthenticationListener constructor.
     *
     * @param LoginManagerInterface $loginManager
     */
    public function __construct(LoginManagerInterface $loginManager)
    {
        $this->loginManager = $loginManager;
        $this->firewallName = 'main';
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            UserEvents::REGISTRATION_COMPLETED => 'authenticate',
            UserEvents::REGISTRATION_CONFIRMED => 'authenticate',
            UserEvents::RESETTING_RESET_COMPLETED => 'authenticate',
        ];
    }

    /**
     * @param FilterUserResponseEvent $event
     * @param string $eventName
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function authenticate(
        FilterUserResponseEvent $event, $eventName, EventDispatcherInterface $eventDispatcher
    )
    {
        try {
            $this->loginManager->logInUser($this->firewallName, $event->getUser(), $event->getResponse());
            $eventDispatcher->dispatch(
                UserEvents::SECURITY_IMPLICIT_LOGIN,
                new UserEvent($event->getUser(), $event->getRequest())
            );
        } catch (AccountStatusException $ex) {
            // We simply do not authenticate users which do not pass the user
            // checker (not enabled, expired, etc.).
        }
    }
}
