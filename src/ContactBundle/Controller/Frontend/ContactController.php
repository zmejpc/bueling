<?php

namespace ContactBundle\Controller\Frontend;

use ComponentBundle\Utils\BreadcrumbsGenerator;
use Doctrine\ORM\EntityManagerInterface;
use SeoBundle\Entity\SeoPage;
use ContactBundle\Entity\Contact;
use ContactBundle\Entity\ContactMailSetting;
use ContactBundle\Entity\ContactManager;
use StaticBundle\Entity\StaticContent;
use ContactBundle\Entity\ContactPhoneType;
use ContactBundle\Form\Type\Frontend\ContactType;
use StaticBundle\Entity\StaticPage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Ecommerce\Entity\Product;
use ComponentBundle\Entity\MailSetting\MailSettingTranslationTrait;

/**
 * @author Ihor Drevetskyi <ihor.drevetskyi@gmail.com>
 */
class ContactController extends AbstractController
{
    private function save(Contact $contact, EntityManagerInterface $em, \ContactBundle\Utils\ContactManager $contactManager)
    {
        $em->persist($contact);
        $em->flush();

        $object = $em->getRepository(ContactMailSetting::class)->getElement();

        if (!$object) {
            $object = $contactManager->createContactMailSetting();
        }

        try {
            $transport = (new \Swift_SmtpTransport($object->getSmtpHost(), $object->getSmtpPort()))
                ->setUsername($object->getSmtpUsername())->setPassword($object->getSmtpPassword());
            $mailer = new \Swift_Mailer($transport);

            $sendTo = $em->getRepository(ContactManager::class)
                ->findBy(['isSendForEmail' => ContactManager::YES]);

            $body = $this->renderView('mail/support/contact/_for_manager.html.twig', [
                'entity' => $contact
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

            // Пользователю
            if (!is_null($contact->getEmail())) {
                $body = $this->renderView('mail/support/contact/_for_user.html.twig', [
                    'body' => $translate->getMessageBody(),
                    'entity' => $contact
                ]);

                $message = (new \Swift_Message($translate->getMessageSubject()))
                    ->setFrom([$object->getSmtpUsername() => $translate->getSenderName()])
                    ->setTo($contact->getEmail())
                    ->setBody($body, 'text/html; charset=utf-8');
                $mailer->send($message);
            }

            $status = true;
            $message = [
                'title' => $translate->getSuccessFlashTitle(),
                'body' => $translate->getSuccessFlashMessage()
            ];
        } catch (\Exception $e) {
            $status = false;
            $message = [];
            dump($e->getMessage());exit();
        }

        return $this->forward('FrontendBundle\Controller\DefaultController::ajaxDialogAction', [
            'status'  => $status,
            'message' => $message,
        ]);
    }

    public function indexAction(Request $request, BreadcrumbsGenerator $breadcrumbsGenerator, EntityManagerInterface $em, \ContactBundle\Utils\ContactManager $contactManager)
    {
        $contact = new Contact();

        $user = $this->getUser();
        if ($user) {
            $contact->setCreatedBy($user);
            $contact->setEmail($user->getEmail());
        }

        $form = $this->createForm(ContactType::class, $contact, [
            'action' => $this->generateUrl('frontend_contact_save'),
            'method' => 'POST',
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() and $form->isValid()) {
            return self::save($contact, $em, $contactManager);
        }

        $breadcrumbsArr = [];
        $seoPageRepository = $em->getRepository(SeoPage::class);
        $seoHomepage = $seoPageRepository->getSeoForPageBySystemName('homepage');
        $seo = $seoPageRepository->getSeoForPageBySystemName('contact');

        $breadcrumbsArr['frontend_homepage'][] = [
            'parameters' => [],
            'title' => (!empty($seoHomepage) and !empty($seoHomepage->breadcrumb)) ? $seoHomepage->breadcrumb : ''
        ];

        $breadcrumbsArr['frontend_contact_index'][] = [
            'parameters' => [],
            'title' => (!empty($seo) and !empty($seo->breadcrumb)) ? $seo->breadcrumb : ''
        ];

        $breadcrumbs = $breadcrumbsGenerator->generateBreadcrumbs($breadcrumbsArr);

        $contactPhoneType = $em->getRepository(ContactPhoneType::class)->getContactPhoneType();
        $result = [];
        foreach ($contactPhoneType as $item) {
            $result[$item->getId()]['title'] = $item->translate()->getTitle();
            if ($item->hasContactPhones()) {
                foreach ($item->getContactPhones() as $phone) {
                    $result[$item->getId()]['phones'][] = $phone->getPhone();
                }
            }
        }

        $static = $em->getRepository(StaticContent::class)->getByPage('contact');

        $staticContent = [];
        foreach ($static as $item) {
            $staticContent[$item->getLinkName()]['shortDescription'] = $item->translate()->getShortDescription();
            $staticContent[$item->getLinkName()]['description'] = $item->translate()->getDescription();
        }

        if (empty($static)) {
            return $this->redirectToRoute('frontend_homepage');
        }

        return $this->render('support/contact/index.html.twig', [
            'form' => $form->createView(),
            'seo' => $seo,
            'page' => $static,
            'breadcrumbs' => $breadcrumbs,
            'contactPhoneType' => $result,
            'staticContent' => $staticContent
        ]);
    }

    public function getContactInfoJson(EntityManagerInterface $em)
    {
        $contactPhoneType = $em->getRepository(ContactPhoneType::class)->getContactPhoneType();

        $result = [];
        foreach ($contactPhoneType as $item) {
            $result[$item->getId()]['title'] = $item->translate()->getTitle();
            if ($item->hasContactPhones()) {
                foreach ($item->getContactPhones() as $phone) {
                    $result[$item->getId()]['phones'][] = $phone->getPhone();
                }
            }
        }

        $static = $em->getRepository(StaticContent::class)->getByPage('contact');

        $staticResult = [];
        foreach ($static as $item) {
            $staticResult[$item->getLinkName()]['text'] = $item->translate()->getDescription();
            $staticResult[$item->getLinkName()]['poster'] = $item->getImg();
        }

        return new JsonResponse([
            'phones' => $result,
            'others' => $staticResult,
        ]);
    }

    public function PopupFormAction(Request $request, EntityManagerInterface $em, \ContactBundle\Utils\ContactManager $contactManager, int $product_id)
    {
        $contact = new Contact();

        $user = $this->getUser();
        if ($user) {
            $contact->setCreatedBy($user);
            $contact->setEmail($user->getEmail());
        }

        $product = $em->getRepository(Product::class)->find($product_id);

        $form = $this->createForm(ContactType::class, $contact, [
            'action' => $this->generateUrl('frontend_contact_popup', ['product_id' => $product_id]),
            'method' => 'POST', 'product' => $product, 'repository' => $em->getRepository(Product::class)
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
            return self::save($contact, $em, $contactManager);
        }

        $response = [
            'dialog' => $this->renderView('support/contact/dialog.html.twig', [
                'class' => 'page__dialog--cart',
                'form' => $form->createView(),
                'title' => $product ? 'Задать вопрос о "' . $product->getTitle() . '"' : 'Отправить сообщение'
            ])
        ];

        return new JsonResponse($response);
    }

    public function renderForm()
    {
        $form = $this->createForm(ContactType::class, new Contact, [
            'action' => $this->generateUrl('frontend_contact_save'),
            'method' => 'POST'
        ]);

        return $this->render('support/contact/_form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
