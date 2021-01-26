<?php

namespace Ecommerce\Controller\Dashboard;

use Doctrine\ORM\EntityManagerInterface;
use SeoBundle\Entity\Seo;
use DashboardBundle\Controller\CRUDController;
use Ecommerce\Entity\ActivityArea;
use Ecommerce\Form\Type\Dashboard\ActivityAreaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;
use Ecommerce\Entity\ActivityAreaGalleryImage;

/**
 * @author Design studio origami <https://origami.ua>
 */
class ActivityAreaController extends CRUDController
{
    public function getHeadTitle(): string
    {
        return 'Направления';
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
            'index' => 'dashboard_activity_area_index', 'new' => 'dashboard_activity_area_new',
            'edit' => 'dashboard_activity_area_edit', 'delete' => 'dashboard_activity_area_delete',
        ];
    }

    public function getCaptionSubjectIcon(): string
    {
        return 'fa fa-barcode';
    }

    public function getFormType(): string
    {
        return ActivityAreaType::class;
    }

    public function getFormElement()
    {
        $seo = new Seo();
        $activityArea = new ActivityArea();
        $activityArea->setSeo($seo);

        return $activityArea;
    }

    public function getRepository(EntityManagerInterface $em)
    {
        $repository = $em->getRepository(ActivityArea::class);

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
            'position' => [
                'title' => $translator->trans('ui.position', [], 'DashboardBundle'),
                'width' => 80
            ],
            'showOnWebsite' => [
                'title' => $translator->trans('ui.show_on_website', [], 'DashboardBundle'),
                'width' => 80
            ],
            'showOnHomepage' => [
                'title' => 'Отображать на главной?',
                'width' => 80
            ],
            'showInFilter' => [
                'title' => 'Отображать в фильтре?',
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
            'galleryImages-image' => $twig->render('@Dashboard/default/crud/list/element/_img.html.twig', [
                'element' => $item->getGalleryImages()->first() ? $item->getGalleryImages()->first()->getImage() : null
            ]),
            'position' => $twig->render('@Dashboard/default/crud/list/element/_position.html.twig', [
                'element' => $item
            ]),
            'showOnWebsite' => $twig->render('@Dashboard/default/crud/list/element/_yes_no.html.twig', [
                'element' => $item, 'fieldName' => 'showOnWebsite',
            ]),
            'showOnHomepage' => $twig->render('@Dashboard/default/crud/list/element/_yes_no.html.twig', [
                'element' => $item, 'fieldName' => 'showOnHomepage',
            ]),
            'showInFilter' => $twig->render('@Dashboard/default/crud/list/element/_yes_no.html.twig', [
                'element' => $item, 'fieldName' => 'showInFilter',
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

    public function getPortletBodyTemplateForForm(): string
    {
        return '@Ecommerce/dashboard/activity_area/form/_portlet_body.html.twig';
    }
}
