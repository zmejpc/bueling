<?php

namespace ContactBundle\Controller\Dashboard;

use DashboardBundle\Controller\CRUDController;
use Doctrine\ORM\EntityManagerInterface;
use ContactBundle\Entity\CallbackStatus;
use ContactBundle\Form\Type\Dashboard\CallbackStatusType;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

/**
 * @author Design studio origami <https://origami.ua>
 */
class CallbackStatusController extends CRUDController
{
    public function getHeadTitle(): string
    {
        return $this->translator->trans('sidebar.callback.callback', [], 'DashboardBundle') . ' > '
            . $this->translator->trans('sidebar.callback.callback_settings.callback_settings', [], 'DashboardBundle') . ' > ' .
            $this->translator->trans('sidebar.callback.callback_settings.callback_statuses', [], 'DashboardBundle');
    }

    public function getGrantedRoles(): array
    {
        return [
            'index' => 'ROLE_CALLBACK_STATUS_LIST', 'new' => 'ROLE_CALLBACK_STATUS_CREATE',
            'edit' => 'ROLE_CALLBACK_STATUS_EDIT', 'delete' => 'ROLE_CALLBACK_STATUS_DELETE',
        ];
    }

    public function getRouteElements(): array
    {
        return [
            'index' => 'dashboard_callback_status_index', 'new' => 'dashboard_callback_status_new',
            'edit' => 'dashboard_callback_status_edit', 'delete' => 'dashboard_callback_status_delete',
            'ajax_delete_group' => 'dashboard_callback_status_ajax_delete_group', 
        ];
    }

    public function getCaptionSubjectIcon(): string
    {
        return 'icon-flag';
    }

    public function getFormType(): string
    {
        return CallbackStatusType::class;
    }

    public function getFormElement()
    {
        return new CallbackStatus();
    }

    public function getRepository(EntityManagerInterface $em)
    {
        $repository = $em->getRepository(CallbackStatus::class);

        return $repository;
    }

    public function getListElementsForIndexDashboard(TranslatorInterface $translator): array
    {
        return [
            'translations-title' => $translator->trans('ui.title', [], 'DashboardBundle'),
            'position' => $translator->trans('ui.position', [], 'DashboardBundle'),
            'systemName' => $translator->trans('form.system_name', [], 'DashboardBundle'),
        ];
    }

    public function createDataForList($item, Environment $twig): array
    {
        return [
            'translations-title' => $twig->render('@Dashboard/default/crud/list/element/_link.html.twig', [
                'href' => $this->generateUrl($this->getRouteElements()['edit'], ['id' => $item->getId()]),
                'title' => $item->translate()->getTitle(),
            ]),
            'position' => $item->getPosition(),
            'systemName' => $item->getSystemName(),
        ];
    }

    public function getConfigForIndexDashboard(): array
    {
        return [
            'pageLength' => 25,
            'lengthMenu' => '10, 20, 25, 50, 100, 150',
            'order_column' => 2,
            'order_by' => "asc"
        ];
    }
}
