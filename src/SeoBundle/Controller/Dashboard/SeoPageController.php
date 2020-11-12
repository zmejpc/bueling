<?php

namespace SeoBundle\Controller\Dashboard;

use Twig\Environment;
use SeoBundle\Entity\Seo;
use SeoBundle\Entity\SeoPage;
use Doctrine\ORM\EntityManagerInterface;
use SeoBundle\Form\Type\Dashboard\SeoPageType;
use DashboardBundle\Controller\CRUDController;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @author Design studio origami <https://origami.ua>
 */
final class SeoPageController extends CRUDController
{
    /**
     * @return array
     */
    public function getRouteElements(): array
    {
        return [
            'index' => 'dashboard_seo_page_index', 'new' => 'dashboard_seo_page_new',
            'edit' => 'dashboard_seo_page_edit', 'delete' => 'dashboard_seo_page_delete',
            'ajax_delete_group' => 'dashboard_seo_page_ajax_delete_group', 
        ];
    }

    /**
     * @return array
     */
    public function getGrantedRoles(): array
    {
        return [
            'index' => 'ROLE_SEO_LIST', 'new' => 'ROLE_DEVELOPER',
            'edit' => 'ROLE_SEO_EDIT', 'delete' => 'ROLE_DEVELOPER'
        ];
    }

    /**
     * @return string
     */
    public function getHeadTitle(): string
    {
        return
            $this->translator->trans('sidebar.configuration.configuration', [], 'DashboardBundle')
            . ' > ' .
            $this->translator->trans('sidebar.configuration.seo', [], 'SeoBundle');
    }

    /**
     * @return array
     */
    public function getPortletHeadIcon(): array
    {
        return [
            'useSvg' => true,
            'icon' => 'flaticon-stopwatch',
            'svg' => '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <rect id="bound" x="0" y="0" width="24" height="24"/>
        <path d="M5,19 L20,19 C20.5522847,19 21,19.4477153 21,20 C21,20.5522847 20.5522847,21 20,21 L4,21 C3.44771525,21 3,20.5522847 3,20 L3,4 C3,3.44771525 3.44771525,3 4,3 C4.55228475,3 5,3.44771525 5,4 L5,19 Z" id="Path-95" fill="#000000" fill-rule="nonzero"/>
        <path d="M8.7295372,14.6839411 C8.35180695,15.0868534 7.71897114,15.1072675 7.31605887,14.7295372 C6.9131466,14.3518069 6.89273254,13.7189711 7.2704628,13.3160589 L11.0204628,9.31605887 C11.3857725,8.92639521 11.9928179,8.89260288 12.3991193,9.23931335 L15.358855,11.7649545 L19.2151172,6.88035571 C19.5573373,6.44687693 20.1861655,6.37289714 20.6196443,6.71511723 C21.0531231,7.05733733 21.1271029,7.68616551 20.7848828,8.11964429 L16.2848828,13.8196443 C15.9333973,14.2648593 15.2823707,14.3288915 14.8508807,13.9606866 L11.8268294,11.3801628 L8.7295372,14.6839411 Z" id="Path-97" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
    </g>
</svg>'
        ];
    }

    /**
     * @return \Doctrine\Common\Persistence\ObjectRepository|\SeoBundle\Entity\Repository\SeoPageRepository
     */
    public function getRepository(EntityManagerInterface $em)
    {
        return $em->getRepository(SeoPage::class);
    }

    /**
     * @return array
     */
    public function getListElementsForIndexDashboard(TranslatorInterface $translator): array
    {
        return [
            'seo-translations-metaTitle' => $translator->trans('form.seo.meta_title', [], 'SeoBundle'),
            'seo-translations-h1' => $translator->trans('form.seo.h1', [], 'SeoBundle'),
            'seo-translations-breadcrumb' => $translator->trans('form.seo.bread_crumbs', [], 'SeoBundle'),
            'systemName' => [
                'locked' => true,
                'title' => $translator->trans('form.system_name', [], 'DashboardBundle')
            ],
        ];
    }

    /**
     * @param $item
     * @return array
     */
    public function createDataForList($item, Environment $twig): array
    {
        $seo = $item->getSeo();
        $seoT = $seo->translate();

        return [
            'seo-translations-metaTitle' => $twig->render('@Dashboard/default/crud/list/element/_link.html.twig', [
                'href' => $this->generateUrl($this->getRouteElements()['edit'], ['id' => $item->getId()]),
                'title' => $seoT->getMetaTitle(),
            ]),
            'seo-translations-h1' => $seoT->getH1(),
            'seo-translations-breadcrumb' => $seoT->getBreadcrumb(),
            'systemName' => $item->getSystemName(),
        ];
    }

    /**
     * @return SeoPage
     */
    public function getFormElement()
    {
        $seo = new Seo();
        $seoPage = new SeoPage();
        $seoPage->setSeo($seo);

        return $seoPage;
    }

    /**
     * @return string
     */
    public function getFormType(): string
    {
        return SeoPageType::class;
    }
}