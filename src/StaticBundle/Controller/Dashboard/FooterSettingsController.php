<?php

namespace StaticBundle\Controller\Dashboard;

use Doctrine\ORM\EntityManagerInterface;
use StaticBundle\Entity\FooterSettings;
use StaticBundle\Form\Type\Dashboard\FooterSettingsType;
use DashboardBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

/**
 * @author Design studio origami <https://origami.ua>
 */
class FooterSettingsController extends CRUDController
{
    public function getHeadTitle(): string
    {
        return 'Статический раздел';
    }

    public function getHeadTitleForEdit($object)
    {
        return ' > Футер';
    }

    public function getGrantedRoles(): array
    {
        return [
            'index' => null, 'new' => 'ROLE_STATIC',
            'edit' => 'ROLE_STATIC', 'delete' => null,
        ];
    }

    public function getRouteElements(): array
    {
        return [
            'index' => null, 'new' => 'dashboard_footer_settings_new',
            'edit' => 'dashboard_footer_settings_edit', 'delete' => null,
        ];
    }

    public function getCaptionSubjectIcon(): string
    {
        return 'fa fa-dollar';
    }

    public function getFormType(): string
    {
        return FooterSettingsType::class;
    }

    public function getFormElement()
    {
        return new FooterSettings();
    }

    public function getRepository(EntityManagerInterface $em)
    {
        $repository = $em->getRepository(FooterSettings::class);

        return $repository;
    }

    /**
     * @return array
     */
    public function getListElementsForIndexDashboard(TranslatorInterface $translator): array
    {
        return [
        ];
    }

    public function createDataForList($item, Environment $twig): array
    {
        return [
        ];
    }

    public function getPortletBodyTemplateForForm(): string
    {
        return '@Static/dashboard/footer_settings/form/_portlet_body.html.twig';
    }
}