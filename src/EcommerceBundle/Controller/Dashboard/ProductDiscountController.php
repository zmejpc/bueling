<?php

namespace Ecommerce\Controller\Dashboard;

use DashboardBundle\Controller\CRUDController;
use Doctrine\ORM\EntityManagerInterface;
use Ecommerce\Entity\ProductDiscount;
use Twig\Environment;
use Ecommerce\Form\Type\Dashboard\ProductDiscountType;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @author Design studio origami <https://origami.ua>
 */
class ProductDiscountController extends CRUDController
{
    public function getHeadTitle(): string
    {
        return 'Скидки';
    }

    public function getGrantedRoles(): array
    {
        return [
            'index' => 'ROLE_PRODUCT_CREATE_EDIT', 'new' => 'ROLE_PRODUCT_CREATE_EDIT',
            'edit' => 'ROLE_PRODUCT_CREATE_EDIT', 'delete' => 'ROLE_PRODUCT_CREATE_EDIT',
        ];
    }

    public function getRouteElements(): array
    {
        return [
            'index' => 'dashboard_product_discount_index', 'new' => 'dashboard_product_discount_new',
            'edit' => 'dashboard_product_discount_edit', 'delete' => 'dashboard_product_discount_delete',
        ];
    }

    public function getCaptionSubjectIcon(): string
    {
        return 'icon-flag';
    }

    public function getFormType(): string
    {
        return ProductDiscountType::class;
    }

    public function getFormElement()
    {
        return new ProductDiscount();
    }

    public function getRepository(EntityManagerInterface $em)
    {
        $repository = $em->getRepository(ProductDiscount::class);

        return $repository;
    }

    public function getListElementsForIndexDashboard(TranslatorInterface $translator): array
    {
        return [
            'translations-title' => $translator->trans('ui.title', [], 'DashboardBundle'),
        ];
    }

     public function createDataForList($item, Environment $twig): array
    {
        return [
            'translations-title' => $twig->render('@Dashboard/default/crud/list/element/_link.html.twig', [
                'href' => $this->generateUrl($this->getRouteElements()['edit'], ['id' => $item->getId()]),
                'title' => $item->translate()->getTitle(),
            ]),
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
