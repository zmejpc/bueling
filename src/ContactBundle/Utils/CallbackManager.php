<?php

namespace ContactBundle\Utils;

use Doctrine\ORM\EntityManagerInterface;
use ContactBundle\Entity\CallbackMailSetting;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @author Design studio origami <https://origami.ua>
 */
class CallbackManager
{
    private $em;
    private $host;
    private $password;
    private $userName;
    private $locale;
    private $senderName;

    public function __construct(ContainerInterface $container, EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->host = $container->getParameter('MAILER_HOST');
        $this->password = $container->getParameter('MAILER_PASSWORD');
        $this->userName = $container->getParameter('MAILER_USERNAME');
        $this->locale = $container->getParameter('locale');
        $this->senderName = $container->getParameter('MAILER_SENDER_NAME');
    }

    public function createCallbackMailSetting()
    {
        $object = new CallbackMailSetting();
        $object->setSmtpHost($this->host);
        $object->setSmtpPassword($this->password);
        $object->setSmtpPort('25');
        $object->setSmtpUsername($this->userName);
        $object->translate($this->locale)->setSenderName($this->senderName);
        $object->translate($this->locale)->setManagerSubject('Manager Subject');
        $object->translate($this->locale)->setSuccessFlashTitle('Success Flash Title');
        $object->translate($this->locale)->setSuccessFlashMessage('Success Flash Message');
        $object->mergeNewTranslations();
        $this->em->persist($object);
        $this->em->flush();

        return $object;
    }
}