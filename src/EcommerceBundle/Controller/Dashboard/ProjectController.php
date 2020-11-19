<?php

namespace Ecommerce\Controller\Dashboard;

use Doctrine\ORM\EntityManagerInterface;
use SeoBundle\Entity\Seo;
use DashboardBundle\Controller\CRUDController;
use Ecommerce\Entity\Project;
use Ecommerce\Form\Type\Dashboard\ProjectType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;
use Ecommerce\Entity\ProjectGalleryImage;

/**
 * @author Design studio origami <https://origami.ua>
 */
class ProjectController extends CRUDController
{
    public function getHeadTitle(): string
    {
        return 'Проекты';
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
            'index' => 'dashboard_project_index', 'new' => 'dashboard_project_new',
            'edit' => 'dashboard_project_edit', 'delete' => 'dashboard_project_delete',
        ];
    }

    public function getCaptionSubjectIcon(): string
    {
        return 'fa fa-barcode';
    }

    public function getFormType(): string
    {
        return ProjectType::class;
    }

    public function getFormElement()
    {
        $seo = new Seo();
        $project = new Project();
        $project->setSeo($seo);

        return $project;
    }

    public function getRepository(EntityManagerInterface $em)
    {
        $repository = $em->getRepository(Project::class);

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
        ];
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
            'position' => $twig->render('@Dashboard/default/crud/list/element/_position.html.twig', [
                'element' => $item
            ]),
            'showOnWebsite' => $twig->render('@Dashboard/default/crud/list/element/_yes_no.html.twig', [
                'element' => $item, 'fieldName' => 'showOnWebsite',
            ]),
            'showOnHomepage' => $twig->render('@Dashboard/default/crud/list/element/_yes_no.html.twig', [
                'element' => $item, 'fieldName' => 'showOnHomepage',
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
        return '@Ecommerce/dashboard/project/form/_portlet_body.html.twig';
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
