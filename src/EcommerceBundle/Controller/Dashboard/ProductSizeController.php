<?php

namespace Ecommerce\Controller\Dashboard;

use DashboardBundle\Controller\CRUDController;
use Doctrine\ORM\EntityManagerInterface;
use Ecommerce\Entity\ProductSize;
use Twig\Environment;
use Ecommerce\Form\Type\Dashboard\ProductSizeType;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @author Design studio origami <https://origami.ua>
 */
class ProductSizeController extends CRUDController
{
    public function getHeadTitle(): string
    {
        return 'Размеры';
    }

    public function getGrantedRoles(): array
    {
        return [
            'index' => 'ROLE_PRODUCT_SIZE_LIST', 'new' => 'ROLE_PRODUCT_SIZE_CREATE',
            'edit' => 'ROLE_PRODUCT_SIZE_EDIT', 'delete' => 'ROLE_PRODUCT_SIZE_DELETE',
        ];
    }

    public function getRouteElements(): array
    {
        return [
            'index' => 'dashboard_product_size_index', 'new' => 'dashboard_product_size_new',
            'edit' => 'dashboard_product_size_edit', 'delete' => 'dashboard_product_size_delete',
        ];
    }

    public function getCaptionSubjectIcon(): string
    {
        return 'icon-flag';
    }

    public function getFormType(): string
    {
        return ProductSizeType::class;
    }

    public function getFormElement()
    {
        return new ProductSize();
    }

    public function getRepository(EntityManagerInterface $em)
    {
        $repository = $em->getRepository(ProductSize::class);

        return $repository;
    }

    public function getListElementsForIndexDashboard(TranslatorInterface $translator): array
    {
        return [
            'title' => $translator->trans('ui.title', [], 'DashboardBundle'),
        ];
    }

     public function createDataForList($item, Environment $twig): array
    {
        return [
            'title' => $twig->render('@Dashboard/default/crud/list/element/_link.html.twig', [
                'href' => $this->generateUrl($this->getRouteElements()['edit'], ['id' => $item->getId()]),
                'title' => $item->getTitle(),
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
