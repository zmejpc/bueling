<?php

namespace ContactBundle\Controller\Dashboard;

use DashboardBundle\Controller\CRUDController;
use Doctrine\ORM\EntityManagerInterface;
use ContactBundle\Entity\ContactMailSetting;
use ContactBundle\Form\Type\Dashboard\ContactMailSettingType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

/**
 * @author Design studio origami <https://origami.ua>
 */
class ContactMailSettingController extends CRUDController
{
    public function getHeadTitle(): string
    {
        return $this->translator->trans('sidebar.callback.callback', [], 'DashboardBundle') . ' > '
            . $this->translator->trans('sidebar.callback.callback_settings.callback_settings', [], 'DashboardBundle') . ' > ' .
            $this->translator->trans('sidebar.callback.callback_settings.callback_mail_setting', [], 'DashboardBundle');
    }

    public function getGrantedRoles(): array
    {
        return [
            'index' => null, 'new' => null,
            'edit' => 'ROLE_CALLBACK_MAIL_SETTING_EDIT', 'delete' => null,
        ];
    }

    public function getRouteElements(): array
    {
        return [
            'index' => null, 'new' => null,
            'edit' => 'dashboard_contact_mail_setting', 'delete' => null,
        ];
    }

    public function getCaptionSubjectIcon(): string
    {
        return 'icon-settings';
    }

    public function getFormType(): string
    {
        return ContactMailSettingType::class;
    }

    public function getFormElement()
    {
        return new ContactMailSetting();
    }

    public function getRepository(EntityManagerInterface $em)
    {
        $repository = $em->getRepository(ContactMailSetting::class);

        return $repository;
    }

    public function getListElementsForIndexDashboard(TranslatorInterface $translator): array
    {
        return [];
    }

    public function createDataForList($item, Environment $twig): array
    {
        return [];
    }

    public function indexAction(Request $request, EntityManagerInterface $em, AuthorizationCheckerInterface $authChecker, TranslatorInterface $translator, Environment $twig)
    {
        if (!$this->isGranted($this->getGrantedRoles()['edit'])) {
            throw $this->createAccessDeniedException($translator->trans('ui.accessDenied', [], 'DashboardBundle'));
        }

        $repository = $this->getRepository($em);
        $object = $repository->getElementForEditForm();

        if (!$object) {
            $object = new ContactMailSetting();
            $object->setSmtpHost($this->getParameter('MAILER_HOST'));
            $object->setSmtpPassword($this->getParameter('MAILER_PASSWORD'));
            $object->setSmtpPort('25');
            $object->setSmtpUsername($this->getParameter('MAILER_USERNAME'));
            $locale = $this->getParameter('locale');
            $object->translate($locale)->setSenderName($this->getParameter('MAILER_SENDER_NAME'));
            $object->translate($locale)->setManagerSubject('Manager Subject');
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
            'headTitle' => $this->getHeadTitle($translator),
            'captionSubjectIcon' => $this->getCaptionSubjectIcon(),
            'routeForGetElementsForIndex' => (is_null($this->getRouteElements()['index'])) ? null : $this->generateUrl($this->getRouteElements()['index']),
            'routeForGetElementsForDelete' => null,
            'portletBodyTemplateForNewForm' => $this->getPortletBodyTemplateForForm(),
            'templateNumber' => 1,
        ]);
    }
}