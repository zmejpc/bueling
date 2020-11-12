<?php

namespace StaticBundle\Controller\Dashboard;

use Twig\Environment;
use StaticBundle\Entity\StaticContent;
use Doctrine\ORM\EntityManagerInterface;
use DashboardBundle\Controller\CRUDController;
use StaticBundle\Form\Type\Dashboard\StaticContentType;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @author Design studio origami <https://origami.ua>
 */
final class StaticContentController extends CRUDController
{
    /**
     * @return array
     */
    public function getGrantedRoles(): array
    {
        return [
            'index' => 'ROLE_STATIC_LIST', 'new' => 'ROLE_DEVELOPER',
            'edit' => 'ROLE_STATIC_EDIT', 'delete' => 'ROLE_DEVELOPER',
        ];
    }

    /**
     * @return array
     */
    public function getRouteElements(): array
    {
        return [
            'index' => 'dashboard_static_content_index', 'new' => 'dashboard_static_content_new',
            'edit' => 'dashboard_static_content_edit', 'delete' => 'dashboard_static_content_delete',
            'ajax_delete_group' => 'dashboard_static_content_ajax_delete_group', 
        ];
    }

    /**
     * @return \Doctrine\Common\Persistence\ObjectRepository|\StaticBundle\Entity\Repository\StaticContentRepository
     */
    public function getRepository(EntityManagerInterface $em)
    {
        return $em->getRepository(StaticContent::class);
    }

    /**
     * @return string
     */
    public function getHeadTitle(): string
    {
        return
            $this->translator->trans('sidebar.configuration.configuration', [], 'DashboardBundle')
            . ' > ' .
            $this->translator->trans('sidebar.configuration.static_partition.static_partition', [], 'StaticBundle')
            . ' > ' .
            $this->translator->trans('sidebar.configuration.static_partition.static_content', [], 'StaticBundle');
    }

    /**
     * @return array
     */
    public function getPortletHeadIcon(): array
    {
        return [
            'useSvg' => true,
            'icon' => 'flaticon-list-1',
            'svg' => '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect id="bound" x="0" y="0" width="24" height="24"/><path d="M3,19 L5,19 L5,21 L3,21 L3,19 Z M8,19 L10,19 L10,21 L8,21 L8,19 Z M13,19 L15,19 L15,21 L13,21 L13,19 Z M18,19 L20,19 L20,21 L18,21 L18,19 Z" id="Combined-Shape" fill="#000000" opacity="0.3"/><path d="M10.504,3.256 L12.466,3.256 L17.956,16 L15.364,16 L14.176,13.084 L8.65000004,13.084 L7.49800004,16 L4.96000004,16 L10.504,3.256 Z M13.384,11.14 L11.422,5.956 L9.42400004,11.14 L13.384,11.14 Z" id="A" fill="#000000"/></g></svg>'
        ];
    }

    /**
     * @return array
     */
    public function getListElementsForIndexDashboard(TranslatorInterface $translator): array
    {
        return [
            'translations-title' => $translator->trans('ui.title', [], 'DashboardBundle'),
            'linkName' => [
                'locked' => true,
                'title' => $translator->trans('form.static_content.link_name', [], 'DashboardBundle'),
            ],
            'page' => [
                'locked' => true,
                'title' => $translator->trans('form.static_content.page', [], 'DashboardBundle'),
            ]
        ];
    }

    /**
     * @param $item
     * @return array
     */
    public function createDataForList($item, Environment $twig): array
    {
        return [
            'translations-title' => $twig->render('@Dashboard/default/crud/list/element/_link.html.twig', [
                'href' => $this->generateUrl($this->getRouteElements()['edit'], ['id' => $item->getId()]),
                'title' => $item->translate()->getTitle(),
            ]),
            'linkName' => $item->getLinkName(),
            'page' => $item->getPage(),
        ];
    }

    /**
     * @return string
     */
    public function getFormType(): string
    {
        return StaticContentType::class;
    }

    /**
     * @return StaticContent
     */
    public function getFormElement()
    {
        return new StaticContent();
    }
}