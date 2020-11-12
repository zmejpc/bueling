<?php

namespace BackendBundle\Controller\Dashboard;

use BackendBundle\Entity\Slider;
use BackendBundle\Form\Type\Dashboard\SliderType;
use Doctrine\ORM\EntityManagerInterface;
use DashboardBundle\Controller\CRUDController;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

/**
 * @author Design studio origami <https://origami.ua>
 */
final class SliderController extends CRUDController
{
    /**
     * @param TranslatorInterface $translator
     * @return string
     */
    public function getHeadTitle(): string
    {
        return 'Слайдер';
    }

    /**
     * @return array
     */
    public function getGrantedRoles(): array
    {
        return [
            'index' => 'ROLE_DIRECTOR', 'new' => 'ROLE_DIRECTOR',
            'edit' => 'ROLE_DIRECTOR', 'delete' => 'ROLE_DIRECTOR',
        ];
    }

    /**
     * @return array
     */
    public function getRouteElements(): array
    {
        return [
            'index' => 'dashboard_slider_index', 'new' => 'dashboard_slider_new',
            'edit' => 'dashboard_slider_edit', 'delete' => 'dashboard_slider_delete',
            'ajax_delete_group' => 'dashboard_slider_ajax_delete_group', 
        ];
    }

    /**
     * @param EntityManagerInterface $em
     * @return \Doctrine\Common\Persistence\ObjectRepository
     */
    public function getRepository(EntityManagerInterface $em)
    {
        $repository = $em->getRepository(Slider::class);

        return $repository;
    }

    /**
     * @return array
     */
    public function getConfigForIndexDashboard(): array
    {
        return [
            'pageLength' => 25,
            'lengthMenu' => '10, 20, 25, 50, 100, 150',
            'order_column' => 'publishAt',
            'order_by' => "desc"
        ];
    }

    /**
     * @param TranslatorInterface $translator
     * @return array
     */
    public function getListElementsForIndexDashboard(TranslatorInterface $translator): array
    {
        return [
            'title' => $translator->trans('ui.title', [], 'DashboardBundle'),
            'position' => $translator->trans('ui.position', [], 'DashboardBundle'),
            'showOnWebsite' => $translator->trans('ui.show_on_website', [], 'DashboardBundle'),
        ];
    }

    /**
     * @param $item
     * @param Environment $twig
     * @return array
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function createDataForList($item, Environment $twig): array
    {
        return [
            'title' => $twig->render('@Dashboard/default/crud/list/element/_link.html.twig', [
                'href' => $this->generateUrl($this->getRouteElements()['edit'], ['id' => $item->getId()]),
                'title' => $item->getTitle(),
            ]),
            'position' => $item->getPosition(),
            'showOnWebsite' => $twig->render('@Dashboard/default/crud/list/element/_yes_no.html.twig', [
                'element' => $item->getShowOnWebsite()
            ]),
        ];
    }

    public function getFormType(): string
    {
        return SliderType::class;
    }

    public function getFormElement()
    {
        return new Slider();
    }

    /**
     * @return string
     */
    public function getPortletBodyTemplateForForm(): string
    {
        return '@Backend/dashboard/slider/form/_portlet_body.html.twig';
    }
}