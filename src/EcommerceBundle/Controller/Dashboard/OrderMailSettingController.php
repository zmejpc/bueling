<?php

namespace Ecommerce\Controller\Dashboard;

use DashboardBundle\Controller\CRUDController;
use Doctrine\ORM\EntityManagerInterface;
use Ecommerce\Entity\OrderMailSetting;
use Ecommerce\Form\Type\Dashboard\OrderMailSettingType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;
use UploadBundle\Services\FileHandler;

/**
 * @author Design studio origami <https://origami.ua>
 */
class OrderMailSettingController extends CRUDController
{
    public function getHeadTitle(): string
    {
        return $this->translator->trans('sidebar.sales.orders.orders', [], 'DashboardBundle') . ' > '
            . $this->translator->trans('sidebar.sales.orders.orders_settings.orders_settings', [], 'DashboardBundle') . ' > ' .
            $this->translator->trans('sidebar.contacts.contact_settings.contact_mail_setting', [], 'DashboardBundle');
    }

    public function getGrantedRoles(): array
    {
        return [
            'index' => null, 'new' => null,
            'edit' => 'ROLE_ORDER_MAIL_SETTING_EDIT', 'delete' => null,
        ];
    }

    public function getRouteElements(): array
    {
        return [
            'index' => null, 'new' => null,
            'edit' => 'dashboard_order_mail_setting_edit', 'delete' => null,
        ];
    }

    public function getCaptionSubjectIcon(): string
    {
        return 'icon-settings';
    }

    public function getFormType(): string
    {
        return OrderMailSettingType::class;
    }

    public function getFormElement()
    {
        return new OrderMailSetting();
    }

    public function getRepository(EntityManagerInterface $em)
    {
        $repository = $em->getRepository(OrderMailSetting::class);

        return $repository;
    }

    public function getListElementsForIndexDashboard(TranslatorInterface $translator): array
    {
        return [];
    }

    public function createDataForList($item, Environment $twig): array
    {
        return [
        ];
    }

    public function redactAction(Request $request, FileHandler $fileHandler, EntityManagerInterface $em, AuthorizationCheckerInterface $authChecker, TranslatorInterface $translator)
    {
        if (!$this->isGranted($this->getGrantedRoles()['edit'])) {
            throw $this->createAccessDeniedException($translator->trans('ui.accessDenied', [], 'DashboardBundle'));
        }

        $repository = $this->getRepository($em);
        $object = $repository->getElementForEditForm();

        if (!$object) {
            $object = new OrderMailSetting();
            $object->setSmtpHost($this->getParameter('MAILER_HOST'));
            $object->setSmtpPassword($this->getParameter('MAILER_PASSWORD'));
            $object->setSmtpPort('25');
            $object->setSmtpUsername($this->getParameter('MAILER_USERNAME'));
            $locale = $this->getParameter('locale');
            $object->translate($locale)->setSenderName($this->getParameter('MAILER_SENDER_NAME'));
            $object->translate($locale)->setManagerSubject('Manager Subject');
            $object->translate($locale)->setMessageBody('Message Body');
            $object->translate($locale)->setMessageSubject('Message Subject');
            $object->translate($locale)->setSuccessFlashTitle('Success Flash Title');
            $object->translate($locale)->setSuccessFlashMessage('Success Flash Message');
            $object->mergeNewTranslations();
            $em->persist($object);
            $em->flush();
        }

        $form = $this->createForm($this->getFormType(), $object, [
            'action' => $this->generateUrl($this->getRouteElements()['edit']),
            'method' => 'POST', 'grantedRoles' => $this->getGrantedRoles()
        ]);

        if ('POST' == $request->getMethod()) {
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $em->persist($object);
                $em->flush();

                return $this->redirectToRoute($this->getRouteElements()['edit']);
            }
        }

        return $this->render('@Dashboard/default/crud/new_edit/index.html.twig', [
            'form' => $form->createView(),
            'templateNumber' => 1,
            'headTitle' => $this->getHeadTitle($translator),
            'captionSubjectIcon' => $this->getCaptionSubjectIcon(),
            'routeForGetElementsForIndex' => (is_null($this->getRouteElements()['index'])) ? null : $this->generateUrl($this->getRouteElements()['index']),
            'routeForGetElementsForDelete' => (is_null($this->getRouteElements()['delete'])) ? null : $this->generateUrl($this->getRouteElements()['delete'], ['id' => $id]),
            'portletBodyTemplateForNewForm' => $this->getPortletBodyTemplateForForm()
        ]);
    }
}