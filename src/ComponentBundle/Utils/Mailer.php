<?php

namespace ComponentBundle\Utils;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * @author Design studio origami <https://origami.ua>
 */
class Mailer implements MailerInterface
{
    /**
     * @var \Swift_Mailer
     */
    protected $mailer;

    /**
     * @var ParameterBagInterface
     */
    private $params;

    /**
     * Mailer constructor.
     * @param \Swift_Mailer $mailer
     * @param ParameterBagInterface $params
     */
    public function __construct(\Swift_Mailer $mailer, ParameterBagInterface $params)
    {
        $this->mailer = $mailer;
        $this->params = $params;
    }

    /**
     * @param string $subject
     * @param string $toEmail
     * @param string $renderedTemplate
     * @return mixed|void
     */
    public function sendEmailMessage(string $subject, string $toEmail, string $renderedTemplate)
    {
        try {
            $transport = (new \Swift_SmtpTransport($this->params->get('MAILER_HOST'), 25))
                ->setUsername($this->params->get('MAILER_USERNAME'))->setPassword($this->params->get('MAILER_PASSWORD'));
            $mailer = new \Swift_Mailer($transport);
            $message = (new \Swift_Message($subject))
                ->setFrom([$this->params->get('MAILER_USERNAME') => $this->params->get('MAILER_SENDER_NAME')])
                ->setTo($toEmail)
                ->setBody($renderedTemplate, 'text/html; charset=utf-8');
            $message->setPriority(\Swift_Mime_SimpleMessage::PRIORITY_HIGHEST);
            $mailer->send($message);
        } catch (\Exception $e) {
        }
    }


    /**
     * @param string $smtpHost
     * @param int $smtpPort
     * @param string $smtpUsername
     * @param string $senderName
     * @param string $smtpPassword
     * @param string $subject
     * @param string $toEmail
     * @param string $renderedTemplate
     * @return mixed|void
     */
    public function sendEmailMessageCustom(
        string $smtpHost, int $smtpPort, string $smtpUsername, string $senderName, string $smtpPassword,
        string $subject, string $toEmail, string $renderedTemplate
    )
    {
        try {
            $transport = (new \Swift_SmtpTransport($smtpHost, $smtpPort))
                ->setUsername($smtpUsername)->setPassword($smtpPassword);
            $mailer = new \Swift_Mailer($transport);
            $message = (new \Swift_Message($subject))
                ->setFrom([$smtpUsername => $senderName])
                ->setTo($toEmail)
                ->setBody($renderedTemplate, 'text/html; charset=utf-8');
            $message->setPriority(\Swift_Mime_SimpleMessage::PRIORITY_HIGHEST);
            $mailer->send($message);
        } catch (\Exception $e) {
        }
    }
}