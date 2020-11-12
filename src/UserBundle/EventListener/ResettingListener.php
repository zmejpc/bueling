<?php

namespace UserBundle\EventListener;

use UserBundle\UserEvents;
use ComponentBundle\Event\FormEvent;
use UserBundle\Event\GetResponseUserEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @author Design studio origami <https://origami.ua>
 */
class ResettingListener implements EventSubscriberInterface
{
    /**
     * @var UrlGeneratorInterface
     */
    private $router;

    /**
     * @var int
     */
    private $tokenTtl;

    /**
     * ResettingListener constructor.
     *
     * @param UrlGeneratorInterface $router
     */
    public function __construct(UrlGeneratorInterface $router)
    {
        $this->router = $router;
        $this->tokenTtl = 7200;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            UserEvents::RESETTING_RESET_INITIALIZE => 'onResettingResetInitialize',
            UserEvents::RESETTING_RESET_SUCCESS => 'onResettingResetSuccess',
            UserEvents::RESETTING_RESET_REQUEST => 'onResettingResetRequest',
        ];
    }

    /**
     * @param GetResponseUserEvent $event
     */
    public function onResettingResetInitialize(GetResponseUserEvent $event)
    {
        if (!$event->getUser()->isPasswordRequestNonExpired($this->tokenTtl)) {
            $event->setResponse(new RedirectResponse($this->router->generate('password_reset')));
        }
    }

    /**
     * @param FormEvent $event
     */
    public function onResettingResetSuccess(FormEvent $event)
    {
        $user = $event->getForm()->getData();

        $user->setPasswordResetToken(null);
        $user->setPasswordRequestedAt(null);
        $user->setEnabled(true);
    }

    /**
     * @param GetResponseUserEvent $event
     */
    public function onResettingResetRequest(GetResponseUserEvent $event)
    {
        if (!$event->getUser()->isAccountNonLocked()) {
            $event->setResponse(new RedirectResponse($this->router->generate('password_reset')));
        }
    }
}
