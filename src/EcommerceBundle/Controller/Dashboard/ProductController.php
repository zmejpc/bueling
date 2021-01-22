<?php

namespace Ecommerce\Controller\Dashboard;

use Doctrine\ORM\EntityManagerInterface;
use SeoBundle\Entity\Seo;
use DashboardBundle\Controller\CRUDController;
use Ecommerce\Entity\Product;
use Ecommerce\Entity\ProductCategory;
use Ecommerce\Form\Type\Dashboard\ProductType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;
use Ecommerce\Entity\ProductGalleryImage;

/**
 * @author Design studio origami <https://origami.ua>
 */
class ProductController extends CRUDController
{
    public function getHeadTitle(): string
    {
        return $this->translator->trans('sidebar.catalog.products.products', [], 'DashboardBundle');
    }

    public function getHeadTitleForEdit($object)
    {
        return ' > ' . $object->translate()->getTitle();
    }

    public function customActionForList($item): string
    {
        return $this->twig->render('@Ecommerce/dashboard/product/list/_actions.html.twig', [
            'element' => $item,
            'productId' => $item->getId(),
            'action_edit_url' => is_null($this->getRouteElements()['edit']) ? null : $this->generateUrl($this->getRouteElements()['edit'], ['id' => $item->getId()]),
            'action_edit_role' => $this->getGrantedRoles()['edit'],
            'action_delete_url' => is_null($this->getRouteElements()['delete']) ? null : $this->generateUrl($this->getRouteElements()['delete'], ['id' => $item->getId()]),
            'action_delete_role' => $this->getGrantedRoles()['delete'],
            'action_copy_url' => is_null($this->getRouteElements()['copy']) ? null :
                $this->generateUrl($this->getRouteElements()['copy'], ['id' => $item->getId()]),
        ]);
    }

    public function isCustomActionForList(): bool
    {
        return false;
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
            'index' => 'dashboard_product_index', 'new' => 'dashboard_product_new',
            'edit' => 'dashboard_product_edit', 'copy' => 'dashboard_product_copy', 'delete' => 'dashboard_product_delete',
            'ajax_delete_group' => 'dashboard_product_ajax_delete_group', 'ajax_sort' => 'dashboard_product_ajax_sort',
        ];
    }

    public function getCaptionSubjectIcon(): string
    {
        return 'fa fa-barcode';
    }

    public function getFormType(): string
    {
        return ProductType::class;
    }

    public function getFormGroupType(): string
    {
        return ProductGroupType::class;
    }

    public function getFormElement()
    {
        $seo = new Seo();
        $product = new Product();
        $product->setSeo($seo);

        return $product;
    }

    public function getRepository(EntityManagerInterface $em)
    {
        $repository = $em->getRepository(Product::class);

        return $repository;
    }

    /**
     * @return array
     */
    public function getListElementsForIndexDashboard(TranslatorInterface $translator): array
    {
        return [
            'translations-title' => $translator->trans('ui.title', [], 'DashboardBundle'),
            'galleryImages-image' => $translator->trans('ui.poster', [], 'DashboardBundle'),
            // 'price' => 'Цена',
            'categories-translations-title' => 'Бренд',
            'categories-slug' => '',
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

    public function createCategoryTree($categories, string $indent = '')
    {
        $tree = [];

        foreach($categories as $category) {
            $tree[] = ['title' => $indent . $category->getTitle(), 'value' => $category->getSlug()];

            if($category->hasChildren()) {
                $tree = array_merge($tree, $this->createCategoryTree($category->getChildren(), $indent . '&emsp;'));
            }
        }

        return $tree;
    }

    public function createDataForList($item, Environment $twig): array
    {
        return [
            'translations-title' => $twig->render('@Dashboard/default/crud/list/element/_link.html.twig', [
                'href' => $this->generateUrl($this->getRouteElements()['edit'], ['id' => $item->getId()]),
                'title' => $item->getTitle(),
            ]),
            'galleryImages-image' => $twig->render('@Dashboard/default/crud/list/element/_img.html.twig', [
                'element' => $item->getGalleryImages()->first() ? $item->getGalleryImages()->first()->getImage() : null
            ]),
            // 'price' => $twig->render('@Ecommerce/dashboard/product/list/_price.html.twig', [
            //     'element' => $item
            // ]),
            // 'residual' => $item->getResidual(),
            'categories-translations-title' => $twig->render('@Dashboard/default/list/_categories.html.twig', [
                'elements' => $item->getCategories()
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

    public function getPortletBodyTemplateForForm(): string
    {
        return '@Ecommerce/dashboard/product/form/_portlet_body.html.twig';
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

    public function customActionInEditAction($object)
    {
        $object = self::helperForNewEditElement($object);

        return $object;
    }
}
