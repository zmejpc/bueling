<?php

namespace ComponentBundle\EventListener;

use Symfony\Component\Security\Http\SecurityEvents;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

/**
 * @author Design studio origami <https://origami.ua>
 */
final class UserLocaleListener
{
    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * UserLocaleListener constructor.
     * @param SessionInterface $session
     */
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * @param InteractiveLoginEvent $event
     */
    public function onInteractiveLogin(InteractiveLoginEvent $event)
    {
        $user = $event->getAuthenticationToken()->getUser();

        if (null !== $user->getLocale()) {
            $this->session->set('_locale', $user->getLocale());
        }
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            SecurityEvents::INTERACTIVE_LOGIN => [['onInteractiveLogin', 15]],
        ];
    }
}
