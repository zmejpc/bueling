<?php

namespace UserBundle\EventListener;

use UserBundle\UserEvents;
use UserBundle\Event\UserEvent;
use UserBundle\Entity\UserInterface;
use UserBundle\Utils\UserManagerInterface;
use Symfony\Component\Security\Http\SecurityEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

/**
 * @author Design studio origami <https://origami.ua>
 */
class LastLoginListener implements EventSubscriberInterface
{
    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @var UserManagerInterface
     */
    protected $userManager;


    /**
     * LastLoginListener constructor.
     * @param UserManagerInterface $userManager
     * @throws \Exception
     */
    public function __construct(UserManagerInterface $userManager)
    {
        $this->userManager = $userManager;
        $this->date = new \DateTime();
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            UserEvents::SECURITY_IMPLICIT_LOGIN => 'onImplicitLogin',
            SecurityEvents::INTERACTIVE_LOGIN => 'onSecurityInteractiveLogin',
        ];
    }

    /**
     * @param UserEvent $event
     * @throws \Exception
     */
    public function onImplicitLogin(UserEvent $event)
    {
        $user = $event->getUser();

        $user->setLastLogin($this->date);
        $this->userManager->updateUser($user);
    }

    /**
     * @param InteractiveLoginEvent $event
     * @throws \Exception
     */
    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {
        $user = $event->getAuthenticationToken()->getUser();

        if ($user instanceof UserInterface) {
            $user->setLastLogin($this->date);
            $this->userManager->updateUser($user);
        }
    }
}
