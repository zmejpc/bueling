<?php

namespace ComponentBundle\Utils;

/**
 * @author Design studio origami <https://origami.ua>
 */
interface MailerInterface
{
    /**
     * @param string $subject
     * @param string $toEmail
     * @param string $renderedTemplate
     * @return mixed
     */
    public function sendEmailMessage(string $subject, string $toEmail, string $renderedTemplate);

    /**
     * @param string $smtpHost
     * @param int $smtpPort
     * @param string $smtpUsername
     * @param string $senderName
     * @param string $smtpPassword
     * @param string $subject
     * @param string $toEmail
     * @param string $renderedTemplate
     * @return mixed
     */
    public function sendEmailMessageCustom(
        string $smtpHost, int $smtpPort, string $smtpUsername, string $senderName, string $smtpPassword,
        string $subject, string $toEmail, string $renderedTemplate
    );
}