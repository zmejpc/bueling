<?php

namespace NewsBundle\Controller\Dashboard;

use Twig\Environment;
use NewsBundle\Entity\NewsComment;
use Doctrine\ORM\EntityManagerInterface;
use DashboardBundle\Controller\CRUDController;
use NewsBundle\Form\Type\Dashboard\NewsCommentType;
use Symfony\Contracts\Translation\TranslatorInterface;

class NewsCommentsController extends CRUDController
{
    /**
     * @return string
     */
    public function getHeadTitle(): string
    {
        return
            $this->translator->trans('sidebar.news.news', [], 'NewsBundle')
            . ' > ' .
            $this->translator->trans('sidebar.news.comments', [], 'NewsBundle');
    }

    /**
     * @return array
     */
    public function getGrantedRoles(): array
    {
        return [
            'index' => 'ROLE_NEWS_COMMENTS_LIST', 'new' => '',
            'edit' => 'ROLE_NEWS_COMMENTS_EDIT', 'delete' => 'ROLE_NEWS_COMMENTS_DELETE',
        ];
    }

    /**
     * @return array
     */
    public function getRouteElements(): array
    {
        return [
            'index' => 'dashboard_news_comments_index', 'new' => 'dashboard_news_comments_new',
            'edit' => 'dashboard_news_comments_edit', 'delete' => 'dashboard_news_comments_delete',
        ];
    }

    /**
     * @return array
     */
    public function getPortletHeadIcon(): array
    {
        return [
            'useSvg' => true,
            'icon' => 'fa fa-sitemap',
            'svg' => '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <polygon id="Shape" points="0 0 24 0 24 24 0 24"/>
        <path d="M18,14 C16.3431458,14 15,12.6568542 15,11 C15,9.34314575 16.3431458,8 18,8 C19.6568542,8 21,9.34314575 21,11 C21,12.6568542 19.6568542,14 18,14 Z M9,11 C6.790861,11 5,9.209139 5,7 C5,4.790861 6.790861,3 9,3 C11.209139,3 13,4.790861 13,7 C13,9.209139 11.209139,11 9,11 Z" id="Combined-Shape" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
        <path d="M17.6011961,15.0006174 C21.0077043,15.0378534 23.7891749,16.7601418 23.9984937,20.4 C24.0069246,20.5466056 23.9984937,21 23.4559499,21 L19.6,21 C19.6,18.7490654 18.8562935,16.6718327 17.6011961,15.0006174 Z M0.00065168429,20.1992055 C0.388258525,15.4265159 4.26191235,13 8.98334134,13 C13.7712164,13 17.7048837,15.2931929 17.9979143,20.2 C18.0095879,20.3954741 17.9979143,21 17.2466999,21 C13.541124,21 8.03472472,21 0.727502227,21 C0.476712155,21 -0.0204617505,20.45918 0.00065168429,20.1992055 Z" id="Combined-Shape" fill="#000000" fill-rule="nonzero"/>
    </g>
</svg>'
        ];
    }

    /**
     * @return string
     */
    public function getFormType(): string
    {
        return NewsCommentType::class;
    }

    /**
     * @return mixed|NewsComment
     */
    public function getFormElement()
    {
        $NewsComment = new NewsComment();

        return $NewsComment;
    }

    /**
     * @return \Doctrine\Common\Persistence\ObjectRepository|mixed
     */
    public function getRepository(EntityManagerInterface $em)
    {
        return $em->getRepository(NewsComment::class);
    }

    /**
     * @return array
     */
    public function getListElementsForIndexDashboard(TranslatorInterface $translator): array
    {
        return [
            'news_title' => $translator->trans('ui.title', [], 'DashboardBundle'),
            'title' => $translator->trans('ui.author', [], 'DashboardBundle'),
            'showOnWebsite' => $translator->trans('ui.show_on_website', [], 'DashboardBundle'),
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
            'order_column' => 'showOnWebsite',
            'order_by' => "asc"
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
        return [
            'title' => $item->getName(),
            'news_title' => $this->twig->render('@Dashboard/default/crud/list/element/_link.html.twig', [
                'href' => $this->generateUrl('dashboard_news_edit', ['id' => $item->getNews()->getId()]),
                'title' => $item->getNews()->translate()->getTitle()
            ]),
            'showOnWebsite' => $this->twig->render('@Dashboard/default/crud/list/element/_yes_no.html.twig', [
                'element' => $item->getShowOnWebsite()
            ])
        ];
    }
}
