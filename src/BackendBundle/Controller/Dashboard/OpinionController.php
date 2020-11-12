<?php

namespace BackendBundle\Controller\Dashboard;

use BackendBundle\Entity\Opinion;
use BackendBundle\Form\Type\Dashboard\OpinionType;
use Doctrine\ORM\EntityManagerInterface;
use DashboardBundle\Controller\CRUDController;
use SeoBundle\Entity\Seo;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

/**
 * @author Design studio origami <https://origami.ua>
 */
final class OpinionController extends CRUDController
{
    /**
     * @param TranslatorInterface $translator
     * @return string
     */
    public function getHeadTitle(): string
    {
        return 'Мнения';
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
            'index' => 'dashboard_opinion_index', 'new' => 'dashboard_opinion_new',
            'edit' => 'dashboard_opinion_edit', 'delete' => 'dashboard_opinion_delete',
        ];
    }

    /**
     * @param EntityManagerInterface $em
     * @return \Doctrine\Common\Persistence\ObjectRepository
     */
    public function getRepository(EntityManagerInterface $em)
    {
        $repository = $em->getRepository(Opinion::class);

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
            'translations-title' => $translator->trans('ui.title', [], 'DashboardBundle'),
            'poster' => $translator->trans('ui.image', [], 'DashboardBundle'),
            'sharing' => $translator->trans('ui.sharing', [], 'DashboardBundle'),
            'position' => $translator->trans('ui.position', [], 'DashboardBundle'),
            'showOnWebsite' => $translator->trans('ui.show_on_website', [], 'DashboardBundle'),
            'publishAt' => $translator->trans('ui.date', [], 'DashboardBundle'),
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
            'translations-title' => $item->translate()->getTitle(),
            'poster' => $twig->render('@Dashboard/default/crud/list/element/_img.html.twig', [
                'element' => $item->getPoster()
            ]),
            'sharing' => $twig->render('@Backend/dashboard/opinion/list/_sharing.html.twig', [
                'element' => $item
            ]),
            'position' => $item->getPosition(),
            'showOnWebsite' => $twig->render('@Dashboard/default/crud/list/element/_yes_no.html.twig', [
                'element' => $item->getShowOnWebsite()
            ]),
            'publishAt' => $twig->render('@Dashboard/default/crud/list/element/_data.html.twig', [
                'element' => $item->getPublishAt()
            ])
        ];
    }

    public function getFormType(): string
    {
        return OpinionType::class;
    }

    public function getFormElement()
    {
        $seo = new Seo();
        $new = new Opinion();
        $new->setSeo($seo);

        return $new;
    }

    /**
     * @return string
     */
    public function getPortletBodyTemplateForForm(): string
    {
        return '@Backend/dashboard/opinion/form/_portlet_body.html.twig';
    }
}