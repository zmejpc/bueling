<?php

namespace Ecommerce\Controller\Dashboard;

use Doctrine\ORM\EntityManagerInterface;
use SeoBundle\Entity\Seo;
use UploadBundle\Controller\UploadFilesTrait;
use DashboardBundle\Controller\CRUDController;
use Ecommerce\Entity\Product;
use Ecommerce\Entity\ProductCapsule;
use Ecommerce\Entity\CategoryHasAttribute;
use Ecommerce\Entity\ProductAttributeValue;
use Ecommerce\Form\Type\Dashboard\ProductCapsuleType;
use Symfony\Component\HttpFoundation\Request;
use UploadBundle\Services\FileHandler;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

/**
 * @author Design studio origami <https://origami.ua>
 */
class ProductCapsuleController extends CRUDController
{
    public function getHeadTitle(): string
    {
        return 'Капсулы';
    }

    public function getHeadTitleForEdit($object)
    {
        return ' > ' . $object->translate()->getTitle();
    }

    public function getGrantedRoles(): array
    {
        return [
            'index' => 'ROLE_PRODUCT_CAPSULE_LIST', 'new' => 'ROLE_PRODUCT_CAPSULE_CREATE',
            'edit' => 'ROLE_PRODUCT_CAPSULE_EDIT', 'delete' => 'ROLE_PRODUCT_CAPSULE_DELETE',
        ];
    }

    public function getRouteElements(): array
    {
        return [
            'index' => 'dashboard_product_capsule_index', 'new' => 'dashboard_product_capsule_new',
            'edit' => 'dashboard_product_capsule_edit', 'delete' => 'dashboard_product_capsule_delete',
            'ajax_sort' => 'dashboard_product_capsule_ajax_sort',
        ];
    }

    public function getCaptionSubjectIcon(): string
    {
        return 'fa fa-sitemap';
    }

    public function getFormType(): string
    {
        return ProductCapsuleType::class;
    }

    public function getFormElement()
    {
        $seo = new Seo();
        $productCapsule = new ProductCapsule();
        $productCapsule->setSeo($seo);

        return $productCapsule;
    }

    public function getRepository(EntityManagerInterface $em)
    {
        $repository = $em->getRepository(ProductCapsule::class);

        return $repository;
    }

    /**
     * @return array
     */
    public function getListElementsForIndexDashboard(TranslatorInterface $translator): array
    {
        return [
            'translations-title' => $translator->trans('list.product_category.tree_title', [], 'DashboardBundle'),
            'poster' => $translator->trans('ui.poster', [], 'DashboardBundle'),
            'position' => $translator->trans('ui.position', [], 'DashboardBundle'),
            'showOnWebsite' => $translator->trans('ui.show_on_website', [], 'DashboardBundle'),
        ];
    }

    public function createDataForList($item, Environment $twig): array
    {
        return [
            'translations-title' => $item->translate()->getTitle(),
            'poster' => $twig->render('@Dashboard/default/crud/list/element/_img.html.twig', [
                'element' => $item->getPoster()
            ]),
            'position' => $twig->render('@Dashboard/default/crud/list/element/_position.html.twig', [
                'element' => $item
            ]),
            'showOnWebsite' => $twig->render('@Dashboard/default/crud/list/element/_yes_no.html.twig', [
                'element' => $item->getShowOnWebsite()
            ]),
        ];
    }

    public function getConfigForIndexDashboard(): array
    {
        return [
            'pageLength' => 100,
            'lengthMenu' => '10, 20, 25, 50, 100, 150',
            'order_column' => 5,
            'order_by' => "asc"
        ];
    }

    public function getPortletBodyTemplateForForm(): string
    {
        return '@Ecommerce/dashboard/product_capsule/form/_portlet_body.html.twig';
    }

    public function getShowActionsColumn()
    {
        return true;
    }

    private function helperForNewEditElement($object)
    {
        $this->em->persist($object);
        $this->em->flush();

        $slug = $object->translate($object->getDefaultLocale())->getSlug();
        $object->setSlug($slug);

        return $object;
    }

    public function customActionInNewAction($object)
    {
        $object = self::helperForNewEditElement($object);

        return $object;
    }
}