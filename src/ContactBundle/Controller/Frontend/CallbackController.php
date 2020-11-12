<?php

namespace ContactBundle\Controller\Frontend;

use Doctrine\ORM\EntityManagerInterface;
use ContactBundle\Entity\Callback;
use ContactBundle\Entity\CallbackManager;
use ContactBundle\Form\Type\Frontend\CallbackType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use ContactBundle\Entity\CallbackMailSetting;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * @author Design studio origami <https://origami.ua>
 */
class CallbackController extends AbstractController
{
    public function initFormAction()
    {
        $callback = new Callback();

        $user = $this->getUser();
        if ($user && $user->getPhoneNumber()) {
            $callback->setCreatedBy($user);
            $callback->setPhone($user->getPhoneNumber());
        }

        $form = $this->createForm(CallbackType::class, $callback, [
            'action' => $this->generateUrl('frontend_callback_save'),
            'method' => 'POST',
        ]);

        return $this->render('support/callback/dialog.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function saveFormAction(Request $request, EntityManagerInterface $em, SessionInterface $session, \ContactBundle\Utils\CallbackManager $manager)
    {
        $callback = new Callback();

        $user = $this->getUser();
        if ($user) {
            $callback->setCreatedBy($user);
            $callback->setUpdatedBy($user);
        }

        $callback->setRemoteUrl($request->server->get('HTTP_REFERER'));

        $form = $this->createForm(CallbackType::class, $callback, [
            'action' => $this->generateUrl('frontend_callback_save'),
            'method' => 'POST',
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
            self::save($callback, $em, $session, $manager);

            $settings = $em->getRepository(CallbackMailSetting::class)->getElement();
            if (!$settings) {
                $settings = $callbackManager->createCallbackMailSetting();
            }

            $response = [
                'status' => true,
                'message' => [
                    'title' => $settings->translate()->getSuccessFlashTitle(),
                    'body' => $settings->translate()->getSuccessFlashMessage(),
                ]
            ];
        } else {
            $response = [
                'status' => false,
                'message' => [
                    'title' => 'Форма связи',
                    'body' => 'произошла ошибка'
                ]
            ];
        }

        return $this->forward('FrontendBundle\Controller\DefaultController::ajaxDialogAction', $response);
    }

    private function save(Callback $callback, EntityManagerInterface $em, SessionInterface $session, \ContactBundle\Utils\CallbackManager $callbackManager)
    {
        $em->persist($callback);
        $em->flush();

        $object = $em->getRepository(CallbackMailSetting::class)->getElement();
        if (!$object) {
            $object = $callbackManager->createCallbackMailSetting();
        }

        try {
            $transport = (new \Swift_SmtpTransport($object->getSmtpHost(), $object->getSmtpPort()))
                ->setUsername($object->getSmtpUsername())->setPassword($object->getSmtpPassword());
            $mailer = new \Swift_Mailer($transport);

            $sendTo = $em->getRepository(CallbackManager::class)
                ->findBy(['isSendForEmail' => CallbackManager::YES]);

            $body = $this->renderView('mail/support/callback/_for_manager.html.twig', [
                'entity' => $callback
            ]);

            $translate = $object->translate();

            // Менеджерам
            foreach ($sendTo as $email) {
                $message = (new \Swift_Message($translate->getManagerSubject()))
                    ->setFrom([$object->getSmtpUsername() => $translate->getSenderName()])
                    ->setTo($email->getEmail())
                    ->setBody($body, 'text/html; charset=utf-8');
                $mailer->send($message);
            }
        } catch (\Exception $e) {
        }
    }
}
