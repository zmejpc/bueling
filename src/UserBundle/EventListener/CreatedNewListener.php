<?php

namespace UserBundle\EventListener;

use UserBundle\UserEvents;
use UserBundle\Event\UserEvent;
use UserBundle\Utils\PasswordUpdaterInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @author Design studio origami <https://origami.ua>
 */
class CreatedNewListener implements EventSubscriberInterface
{
    /**
     * @var PasswordUpdaterInterface
     */
    private $passwordUpdater;

    /**
     * CreatedNewListener constructor.
     *
     * @param PasswordUpdaterInterface $passwordUpdater
     */
    public function __construct(PasswordUpdaterInterface $passwordUpdater)
    {
        $this->passwordUpdater = $passwordUpdater;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            UserEvents::NEW_CREATED_SUCCESS => 'setPassword',
        ];
    }

    public function setPassword(UserEvent $event, $eventName)
    {
       $this->passwordUpdater->hashPassword($event->getUser());
    }
}
