<?php

namespace Ecommerce\Controller\Dashboard;

use DashboardBundle\Controller\CRUDController;
use Doctrine\ORM\EntityManagerInterface;
use Ecommerce\Entity\OrderStatus;
use Twig\Environment;
use Ecommerce\Form\Type\Dashboard\OrderStatusType;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @author Design studio origami <https://origami.ua>
 */
class OrderStatusController extends CRUDController
{
    public function getHeadTitle(): string
    {
        return $this->translator->trans('sidebar.sales.orders.orders', [], 'DashboardBundle') . ' > '
            . $this->translator->trans('sidebar.sales.orders.orders_settings.orders_settings', [], 'DashboardBundle') . ' > ' .
            $this->translator->trans('sidebar.sales.orders.orders_settings.orders_statuses', [], 'DashboardBundle');
    }

    public function getGrantedRoles(): array
    {
        return [
            'index' => 'ROLE_ORDER_STATUS_LIST', 'new' => 'ROLE_ORDER_STATUS_CREATE',
            'edit' => 'ROLE_ORDER_STATUS_EDIT', 'delete' => 'ROLE_ORDER_STATUS_DELETE',
        ];
    }

    public function getRouteElements(): array
    {
        return [
            'index' => 'dashboard_order_status_index', 'new' => 'dashboard_order_status_new',
            'edit' => 'dashboard_order_status_edit', 'delete' => 'dashboard_order_status_delete',
            'ajax_delete_group' => 'dashboard_order_status_ajax_delete_group', 
        ];
    }

    public function getCaptionSubjectIcon(): string
    {
        return 'icon-flag';
    }

    public function getFormType(): string
    {
        return OrderStatusType::class;
    }

    public function getFormElement()
    {
        return new OrderStatus();
    }

    public function getRepository(EntityManagerInterface $em)
    {
        $repository = $em->getRepository(OrderStatus::class);

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
