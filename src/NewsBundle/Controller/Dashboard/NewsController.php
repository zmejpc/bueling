<?php

namespace NewsBundle\Controller\Dashboard;

use Twig\Environment;
use SeoBundle\Entity\Seo;
use NewsBundle\Entity\News;
use Doctrine\ORM\EntityManagerInterface;
use NewsBundle\Form\Type\Dashboard\NewsType;
use DashboardBundle\Controller\CRUDController;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @author Design studio origami <https://origami.ua>
 */
final class NewsController extends CRUDController
{
    /**
     * @return array
     */
    public function getGrantedRoles(): array
    {
        return [
            'index' => 'ROLE_NEWS_LIST', 'new' => 'ROLE_NEWS_CREATE',
            'edit' => 'ROLE_NEWS_EDIT', 'delete' => 'ROLE_NEWS_DELETE',
        ];
    }

    /**
     * @return array
     */
    public function getRouteElements(): array
    {
        return [
            'index' => 'dashboard_news_index', 'new' => 'dashboard_news_new',
            'edit' => 'dashboard_news_edit', 'delete' => 'dashboard_news_delete',
            'ajax_delete_group' => 'dashboard_news_ajax_delete_group', 
        ];
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
     * @return string
     */
    public function getHeadTitle(): string
    {
        return
            $this->translator->trans('sidebar.news.news', [], 'NewsBundle');
    }

    /**
     * @return array
     */
    public function getPortletHeadIcon(): array
    {
        return [
            'useSvg' => true,
            'icon' => 'flaticon-notes',
            'svg' => '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <rect id="bound" x="0" y="0" width="24" height="24"/>
        <path d="M9.61764706,5 L8.73529412,7 L3,7 C2.44771525,7 2,6.55228475 2,6 C2,5.44771525 2.44771525,5 3,5 L9.61764706,5 Z M14.3823529,5 L21,5 C21.5522847,5 22,5.44771525 22,6 C22,6.55228475 21.5522847,7 21,7 L15.2647059,7 L14.3823529,5 Z M6.08823529,13 L5.20588235,15 L3,15 C2.44771525,15 2,14.5522847 2,14 C2,13.4477153 2.44771525,13 3,13 L6.08823529,13 Z M17.9117647,13 L21,13 C21.5522847,13 22,13.4477153 22,14 C22,14.5522847 21.5522847,15 21,15 L18.7941176,15 L17.9117647,13 Z M7.85294118,9 L6.97058824,11 L3,11 C2.44771525,11 2,10.5522847 2,10 C2,9.44771525 2.44771525,9 3,9 L7.85294118,9 Z M16.1470588,9 L21,9 C21.5522847,9 22,9.44771525 22,10 C22,10.5522847 21.5522847,11 21,11 L17.0294118,11 L16.1470588,9 Z M4.32352941,17 L3.44117647,19 L3,19 C2.44771525,19 2,18.5522847 2,18 C2,17.4477153 2.44771525,17 3,17 L4.32352941,17 Z M19.6764706,17 L21,17 C21.5522847,17 22,17.4477153 22,18 C22,18.5522847 21.5522847,19 21,19 L20.5588235,19 L19.6764706,17 Z" id="Combined-Shape" fill="#000000" opacity="0.3"/>
        <path d="M11.044,5.256 L13.006,5.256 L18.5,19 L16,19 L14.716,15.084 L9.19,15.084 L7.5,19 L5,19 L11.044,5.256 Z M13.924,13.14 L11.962,7.956 L9.964,13.14 L13.924,13.14 Z" id="A" fill="#000000"/>
    </g>
</svg>'
        ];
    }

    /**
     * @return \Doctrine\Common\Persistence\ObjectRepository|\NewsBundle\Entity\Repository\NewsRepository
     */
    public function getRepository(EntityManagerInterface $em)
    {
        return $em->getRepository(News::class);
    }

    /**
     * @return array
     */
    public function getListElementsForIndexDashboard(TranslatorInterface $translator): array
    {
        return [
            'translations-title' => $this->translator->trans('ui.title', [], 'DashboardBundle'),
            'poster' => $this->translator->trans('ui.image', [], 'DashboardBundle'),
            'newsCategory-translations-title' => $this->translator->trans('ui.category', [], 'DashboardBundle'),
            'position' => $this->translator->trans('ui.position', [], 'DashboardBundle'),
            'showOnWebsite' => $this->translator->trans('ui.show_on_website', [], 'DashboardBundle'),
            'publishAt' => [
                'locked' => true,
                'title' => $this->translator->trans('ui.date', [], 'DashboardBundle'),
            ]
        ];
    }

    /**
     * @param $item
     * @return array
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function createDataForList($item, Environment $twig): array
    {
        $category = $item->getNewsCategory();
        $galleryImage = $item->getGalleryImages()->first();

        return [
            'newsCategory-translations-title' => ($category) ? $category->translate()->getTitle() : '',
            'translations-title' => $twig->render('@Dashboard/default/crud/list/element/_link.html.twig', [
                'href' => $this->generateUrl($this->getRouteElements()['edit'], ['id' => $item->getId()]),
                'title' => $item->translate()->getTitle(),
            ]),
            'poster' => $this->twig->render('@Dashboard/default/crud/list/element/_img.html.twig', [
                'element' => $galleryImage ? $galleryImage->getImg(): null,
            ]),
            'position' => $item->getPosition(),
            'showOnWebsite' => $this->twig->render('@Dashboard/default/crud/list/element/_yes_no.html.twig', [
                'element' => $item->getShowOnWebsite()
            ]),
            'publishAt' => $this->twig->render('@Dashboard/default/crud/list/element/_data.html.twig', [
                'element' => $item->getPublishAt()
            ])
        ];
    }

    /**
     * @return string
     */
    public function getFormType(): string
    {
        return NewsType::class;
    }

    /**
     * @return mixed|News
     */
    public function getFormElement()
    {
        $seo = new Seo();
        $new = new News();
        $new->setSeo($seo);

        return $new;
    }
}