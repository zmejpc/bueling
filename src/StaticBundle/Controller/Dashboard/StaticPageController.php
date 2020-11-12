<?php

namespace StaticBundle\Controller\Dashboard;

use Twig\Environment;
use SeoBundle\Entity\Seo;
use StaticBundle\Entity\StaticPage;
use Doctrine\ORM\EntityManagerInterface;
use DashboardBundle\Controller\CRUDController;
use StaticBundle\Form\Type\Dashboard\StaticPageType;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @author Design studio origami <https://origami.ua>
 */
final class StaticPageController extends CRUDController
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
            'index' => 'dashboard_static_page_index', 'new' => 'dashboard_static_page_new',
            'edit' => 'dashboard_static_page_edit', 'delete' => 'dashboard_static_page_delete',
            'ajax_delete_group' => 'dashboard_static_page_ajax_delete_group', 
        ];
    }

    /**
     * @return \Doctrine\Common\Persistence\ObjectRepository|\StaticBundle\Entity\Repository\StaticPageRepository
     */
    public function getRepository(EntityManagerInterface $em)
    {
        return $em->getRepository(StaticPage::class);
    }

    /**
     * @return string
     */
    public function getHeadTitle(): string
    {
        return
            $this->translator->trans('sidebar.configuration.configuration', [], 'DashboardBundle')
            .' > '.
            $this->translator->trans('sidebar.configuration.static_partition.static_partition', [], 'StaticBundle')
            . ' > ' .
            $this->translator->trans('sidebar.configuration.static_partition.static_pages', [], 'StaticBundle');
    }

    /**
     * @return array
     */
    public function getPortletHeadIcon(): array
    {
        return [
            'useSvg' => true,
            'icon' => 'flaticon-file-2',
            'svg' => '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <rect id="bound" x="0" y="0" width="24" height="24"/>
        <path d="M8,3 L8,3.5 C8,4.32842712 8.67157288,5 9.5,5 L14.5,5 C15.3284271,5 16,4.32842712 16,3.5 L16,3 L18,3 C19.1045695,3 20,3.8954305 20,5 L20,21 C20,22.1045695 19.1045695,23 18,23 L6,23 C4.8954305,23 4,22.1045695 4,21 L4,5 C4,3.8954305 4.8954305,3 6,3 L8,3 Z" id="Combined-Shape" fill="#000000" opacity="0.3"/>
        <path d="M11,2 C11,1.44771525 11.4477153,1 12,1 C12.5522847,1 13,1.44771525 13,2 L14.5,2 C14.7761424,2 15,2.22385763 15,2.5 L15,3.5 C15,3.77614237 14.7761424,4 14.5,4 L9.5,4 C9.22385763,4 9,3.77614237 9,3.5 L9,2.5 C9,2.22385763 9.22385763,2 9.5,2 L11,2 Z" id="Combined-Shape" fill="#000000"/>
        <rect id="Rectangle-152" fill="#000000" opacity="0.3" x="10" y="9" width="7" height="2" rx="1"/>
        <rect id="Rectangle-152-Copy-2" fill="#000000" opacity="0.3" x="7" y="9" width="2" height="2" rx="1"/>
        <rect id="Rectangle-152-Copy-3" fill="#000000" opacity="0.3" x="7" y="13" width="2" height="2" rx="1"/>
        <rect id="Rectangle-152-Copy" fill="#000000" opacity="0.3" x="10" y="13" width="7" height="2" rx="1"/>
        <rect id="Rectangle-152-Copy-5" fill="#000000" opacity="0.3" x="7" y="17" width="2" height="2" rx="1"/>
        <rect id="Rectangle-152-Copy-4" fill="#000000" opacity="0.3" x="10" y="17" width="7" height="2" rx="1"/>
    </g>
</svg>'
        ];
    }

    /**
     * @return array
     */
    public function getListElementsForIndexDashboard(TranslatorInterface $translator): array
    {
        return [
            'translations-title' => $translator->trans('ui.title', [], 'DashboardBundle'),
            'systemName' => [
                'locked' => true,
                'title' => $translator->trans('form.system_name', [], 'DashboardBundle'),
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
            'systemName' => $item->getSystemName()
        ];
    }

    /**
     * @return string
     */
    public function getFormType(): string
    {
        return StaticPageType::class;
    }

    /**
     * @return StaticPage
     */
    public function getFormElement()
    {
        $seo = new Seo();
        $staticPage = new StaticPage();
        $staticPage->setSeo($seo);

        return $staticPage;
    }
}