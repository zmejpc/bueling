<?php

namespace Ecommerce\Controller\Dashboard;

use Doctrine\ORM\EntityManagerInterface;
use DashboardBundle\Controller\CRUDController;
use Ecommerce\Entity\ProductFeature;
use Ecommerce\Form\Type\Dashboard\ProductFeatureType;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

/**
 * @author Design studio origami <https://origami.ua>
 */
class ProductFeatureController extends CRUDController
{
    public function getHeadTitle(): string
    {
        return 'Характеристики';
    }

    public function getHeadTitleForEdit($object)
    {
        return ' > ' . $object->translate()->getTitle();
    }

    public function getGrantedRoles(): array
    {
        return [
            'index' => 'ROLE_PRODUCT_LIST', 'new' => 'ROLE_PRODUCT_CREATE',
            'edit' => 'ROLE_PRODUCT_EDIT', 'delete' => 'ROLE_PRODUCT_DELETE',
        ];
    }

    public function getRouteElements(): array
    {
        return [
            'index' => 'dashboard_product_feature_index', 'new' => 'dashboard_product_feature_new',
            'edit' => 'dashboard_product_feature_edit', 'delete' => 'dashboard_product_feature_delete',
        ];
    }

    public function getCaptionSubjectIcon(): string
    {
        return 'fa fa-barcode';
    }

    public function getFormType(): string
    {
        return ProductFeatureType::class;
    }

    public function getFormElement()
    {
        return new ProductFeature();
    }

    public function getRepository(EntityManagerInterface $em)
    {
        $repository = $em->getRepository(ProductFeature::class);

        return $repository;
    }

    /**
     * @return array
     */
    public function getListElementsForIndexDashboard(TranslatorInterface $translator): array
    {
        return [
            'translations-title' => $translator->trans('ui.title', [], 'DashboardBundle'),
            'poster' => $translator->trans('ui.poster', [], 'DashboardBundle'),
            'position' => [
                'title' => $translator->trans('ui.position', [], 'DashboardBundle'),
                'width' => 80
            ],
            'showOnWebsite' => [
                'title' => $translator->trans('ui.show_on_website', [], 'DashboardBundle'),
                'width' => 80
            ],
        ];
    }

    public function createDataForList($item, Environment $twig): array
    {
        return [
            'translations-title' => $twig->render('@Dashboard/default/crud/list/element/_link.html.twig', [
                'href' => $this->generateUrl($this->getRouteElements()['edit'], ['id' => $item->getId()]),
                'title' => $item->translate()->getTitle(),
            ]),
            'poster' => $twig->render('@Dashboard/default/crud/list/element/_img.html.twig', [
                'element' => $item->getPoster()
            ]),
            'position' => $twig->render('@Dashboard/default/crud/list/element/_position.html.twig', [
                'element' => $item
            ]),
            'showOnWebsite' => $twig->render('@Dashboard/default/crud/list/element/_yes_no.html.twig', [
                'element' => $item, 'fieldName' => 'showOnWebsite',
            ]),
        ];
    }

    public function getShowActionsColumn()
    {
        return true;
    }

    public function getConfigForIndexDashboard(): array
    {
        return [
            'pageLength' => 50,
            'lengthMenu' => '10, 20, 25, 50',
            'order_column' => 4,
            'order_by' => "asc"
        ];
    }
}
