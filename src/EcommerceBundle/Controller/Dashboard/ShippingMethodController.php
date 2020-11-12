<?php

namespace Ecommerce\Controller\Dashboard;

use DashboardBundle\Controller\CRUDController;
use Doctrine\ORM\EntityManagerInterface;
use Ecommerce\Entity\ShippingMethod;
use Ecommerce\Form\Type\Dashboard\ShippingMethodType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

/**
 * @author Design studio origami <https://origami.ua>
 */
class ShippingMethodController extends CRUDController
{
    /**
     * @return string
     */
    public function getHeadTitle(): string
    {
        return $this->translator->trans('sidebar.payment_shipping.shipping_methods', [], 'DashboardBundle');
    }

    /**
     * @return array
     */
    public function getGrantedRoles(): array
    {
        return [
            'index' => 'ROLE_SHIPPING_METHODS_LIST', 'new' => 'ROLE_SHIPPING_METHODS_CREATE',
            'edit' => 'ROLE_SHIPPING_METHODS_EDIT', 'delete' => 'ROLE_SHIPPING_METHODS_DELETE',
        ];
    }

    /**
     * @return array
     */
    public function getRouteElements(): array
    {
        return [
            'index' => 'dashboard_shipping_method_index', 'new' => 'dashboard_shipping_method_new',
            'edit' => 'dashboard_shipping_method_edit', 'delete' => 'dashboard_shipping_method_delete',
            'ajax_delete_group' => 'dashboard_shipping_method_ajax_delete_group', 
        ];
    }

    /**
     * @return string
     */
    public function getCaptionSubjectIcon(): string
    {
        return 'fa fa-bus';
    }

    /**
     * @return string
     */
    public function getFormType(): string
    {
        return ShippingMethodType::class;
    }

    /**
     * @return ShippingMethod
     */
    public function getFormElement()
    {
        return new ShippingMethod();
    }

    public function getRepository(EntityManagerInterface $em)
    {
        $repository = $em->getRepository(ShippingMethod::class);

        return $repository;
    }

    /**
     * @return array
     */
    public function getListElementsForIndexDashboard(TranslatorInterface $translator): array
    {
        return [
            'translations-title' => $translator->trans('ui.title', [], 'DashboardBundle'),
            'isEnable' => $translator->trans('ui.show_on_website', [], 'DashboardBundle'),
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
            'isEnable' => $twig->render('@Dashboard/default/crud/list/element/_yes_no.html.twig', [
                'element' => $item->getIsEnable()
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
            'order_column' => 3,
            'order_by' => "asc"
        ];
    }

    /**
     * @return string
     */
    public function getPortletBodyTemplateForForm(): string
    {
        return '@Ecommerce/dashboard/shipping_method/form/_portlet_body.html.twig';
    }
}
