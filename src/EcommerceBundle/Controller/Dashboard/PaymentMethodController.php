<?php

namespace Ecommerce\Controller\Dashboard;

use DashboardBundle\Controller\CRUDController;
use Doctrine\ORM\EntityManagerInterface;
use Ecommerce\Entity\PaymentMethod;
use Ecommerce\Form\Type\Dashboard\PaymentMethodType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

/**
 * @author Design studio origami <https://origami.ua>
 */
class PaymentMethodController extends CRUDController
{
    /**
     * @return string
     */
    public function getHeadTitle(): string
    {
        return $this->translator->trans('sidebar.payment_shipping.payment_methods', [], 'DashboardBundle');
    }

    /**
     * @return array
     */
    public function getGrantedRoles(): array
    {
        return [
            'index' => 'ROLE_PAYMENT_METHODS_LIST', 'new' => 'ROLE_PAYMENT_METHODS_CREATE',
            'edit' => 'ROLE_PAYMENT_METHODS_EDIT', 'delete' => 'ROLE_PAYMENT_METHODS_DELETE',
        ];
    }

    /**
     * @return array
     */
    public function getRouteElements(): array
    {
        return [
            'index' => 'dashboard_payment_method_index', 'new' => 'dashboard_payment_method_new',
            'edit' => 'dashboard_payment_method_edit', 'delete' => 'dashboard_payment_method_delete',
            'ajax_delete_group' => 'dashboard_payment_method_ajax_delete_group', 
        ];
    }

    /**
     * @return string
     */
    public function getCaptionSubjectIcon(): string
    {
        return 'icon-wallet';
    }

    /**
     * @return string
     */
    public function getFormType(): string
    {
        return PaymentMethodType::class;
    }

    /**
     * @return PaymentMethod
     */
    public function getFormElement()
    {
        return new PaymentMethod();
    }

    public function getRepository(EntityManagerInterface $em)
    {
        $repository = $em->getRepository(PaymentMethod::class);

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
}
