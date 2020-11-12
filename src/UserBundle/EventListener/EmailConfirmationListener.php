<?php

namespace UserBundle\EventListener;

use UserBundle\UserEvents;
use UserBundle\Event\UserEvent;
use UserBundle\Entity\UserInterface;
use ComponentBundle\Event\FormEvent;
use UserBundle\Mailer\MailerInterface;
use ComponentBundle\Storage\SessionStorage;
use UserBundle\Utils\TokenGeneratorInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @author Design studio origami <https://origami.ua>
 */
class EmailConfirmationListener implements EventSubscriberInterface
{
    /**
     * @var MailerInterface
     */
    private $mailer;

    /**
     * @var TokenGeneratorInterface
     */
    private $tokenGenerator;

    /**
     * @var UrlGeneratorInterface
     */
    private $router;

    /**
     * @var SessionStorage
     */
    private $session;

    /**
     * EmailConfirmationListener constructor.
     * @param MailerInterface $mailer
     * @param TokenGeneratorInterface $tokenGenerator
     * @param UrlGeneratorInterface $router
     * @param SessionStorage $session
     */
    public function __construct(
        MailerInterface $mailer,
        TokenGeneratorInterface $tokenGenerator,
        UrlGeneratorInterface $router,
        SessionStorage $session
    )
    {
        $this->mailer = $mailer;
        $this->tokenGenerator = $tokenGenerator;
        $this->router = $router;
        $this->session = $session;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            UserEvents::REGISTRATION_SUCCESS => 'onRegistrationSuccess',
            UserEvents::AUTO_REGISTRATION_SUCCESS => 'onAutoRegistrationSuccess',
        ];
    }

    /**
     * @param FormEvent $event
     */
    public function onRegistrationSuccess(FormEvent $event)
    {
        $user = $event->getForm()->getData();
        $user->setEnabled(false);

        if (null === $user->getEmailVerificationToken()) {
            $user->setEmailVerificationToken($this->tokenGenerator->generateToken());
        }

        $this->mailer->sendConfirmationEmailMessage($user);

        $this->session->set('user_send_confirmation_email/email', $user->getEmail());

        $url = $this->router->generate('registration_check_email');
        $event->setResponse(new RedirectResponse($url));
    }

    public function onAutoRegistrationSuccess(UserEvent $event)
    {
        $user = $event->getUser();

        if (null === $user->getEmailVerificationToken()) {
            $user->setEmailVerificationToken($this->tokenGenerator->generateToken());
        }

        $this->mailer->sendConfirmationEmailMessage($user, $user->getPlainPassword());
    }
}
