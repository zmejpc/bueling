<?php

namespace UserBundle\Mailer;

use Twig\Environment;
use UserBundle\Utils\UserManager;
use UserBundle\Entity\UserInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * @author Design studio origami <https://origami.ua>
 */
class Mailer implements MailerInterface
{
    /**
     * @var UrlGeneratorInterface
     */
    protected $router;

    /**
     * @var \Twig_Environment
     */
    protected $twig;

    /**
     * @var \ComponentBundle\Utils\Mailer
     */
    protected $mailer;

    /**
     * @var ParameterBagInterface
     */
    private $params;

    /**
     * @var null|TranslatorInterface
     */
    protected $translator = null;

    /**
     * @var UserManager
     */
    protected $userManager;

    /**
     * Mailer constructor.
     * @param UrlGeneratorInterface $router
     * @param Environment $twig
     * @param ParameterBagInterface $params
     * @param TranslatorInterface $translator
     * @param \ComponentBundle\Utils\Mailer $mailer
     * @param UserManager $userManager
     */
    public function __construct(
        UrlGeneratorInterface $router, Environment $twig, ParameterBagInterface $params,
        TranslatorInterface $translator, \ComponentBundle\Utils\Mailer $mailer,
        UserManager $userManager
    )
    {
        $this->mailer = $mailer;
        $this->router = $router;
        $this->twig = $twig;
        $this->params = $params;
        $this->translator = $translator;
        $this->userManager = $userManager;
    }

    /**
     * @param UserInterface $user
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function sendConfirmationEmailMessage(UserInterface $user, $password = '')
    {
        $url = $this->router->generate('registration_confirm', ['token' => $user->getEmailVerificationToken()],
            UrlGeneratorInterface::ABSOLUTE_URL);

        $object = $this->userManager->getUserMailSettingElementBySystemName('registration_confirm');

        $rendered = $this->twig->render('user/emails/_registration_confirmation_email.html.twig', [
            'user' => $user, 'confirmationUrl' => $url, 'object' => $object, 'password' => $password,
        ]);

        $translate = $object->translate();

        $this->mailer->sendEmailMessageCustom(
            $object->getSmtpHost(), $object->getSmtpPort(), $object->getSmtpUsername(), $translate->getSenderName(),
            $object->getSmtpPassword(), $translate->getMessageSubject(), (string)$user->getEmail(), $rendered
        );
    }

    /**
     * @param UserInterface $user
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function sendResettingEmailMessage(UserInterface $user)
    {
        $url = $this->router->generate('password_reset_token', ['token' => $user->getPasswordResetToken()],
            UrlGeneratorInterface::ABSOLUTE_URL);

        $object = $this->userManager->getUserMailSettingElementBySystemName('password_reset');

        $rendered = $this->twig->render('user/emails/_password_reset.html.twig', [
            'user' => $user, 'confirmationUrl' => $url, 'object' => $object
        ]);

        $translate = $object->translate();

        $this->mailer->sendEmailMessageCustom(
            $object->getSmtpHost(), $object->getSmtpPort(), $object->getSmtpUsername(), $translate->getSenderName(),
            $object->getSmtpPassword(), $translate->getMessageSubject(), (string)$user->getEmail(), $rendered
        );
    }
}
