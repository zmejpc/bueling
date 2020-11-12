<?php

namespace NewsBundle\Controller\Dashboard;

use Doctrine\ORM\EntityManagerInterface;
use NewsBundle\Entity\NewsComment;

/**
 * @author Design studio origami <https://origami.ua>
 */
trait sidebarNewsBundleTrait
{

    function __constuct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @return array
     */
    private function newsBundlePathForEdit(): array
    {
        return [
            'news' => 'news/edit',
            'quiz' => 'quiz/',
            'news_category' => 'news-category/edit',
            'news_author' => 'news-author/edit',
            'news_tags' => 'news-tag/edit',
            'news_comments' => 'news-comments/edit',
        ];
    }

    /**
     * @return array
     */
    private function newsBundleRouteName(): array
    {
        return [
            'news' => [
                'index' => 'dashboard_news_index', 'new' => 'dashboard_news_new'
            ],
            'news_category' => [
                'index' => 'dashboard_news_category_index', 'new' => 'dashboard_news_category_new'
            ],
            'news_author' => [
                'index' => 'dashboard_news_author_index', 'new' => 'dashboard_news_author_new'
            ],
            'news_tags' => [
                'index' => 'dashboard_news_tag_index', 'new' => 'dashboard_news_tag_new'
            ],
            'news_comments' => [
                'index' => 'dashboard_news_comments_index', 'new' => 'dashboard_news_comments_new'
            ]
        ];
    }

    /**
     * @return array
     */
    private function newsBundleRoles(): array
    {
        return [
            'news' => 'ROLE_NEWS_CREATE_EDIT',
            'news_tags' => 'ROLE_NEWS_CREATE_EDIT',
            'news_author' => 'ROLE_NEWS_AUTHOR_CREATE_EDIT',
            'news_category' => 'ROLE_NEWS_CATEGORY_CREATE_EDIT',
            'news_comments' => 'ROLE_NEWS_COMMENTS_EDIT',
        ];
    }

    /**
     * @return array|null
     */
    private function sidebarNewsBundle(): ?array
    {
        $blog = self::headingSidebar('', 'Блог', [
            self::newsBundleRoles()['news'], self::newsBundleRoles()['news_author'],
            self::newsBundleRoles()['news_category']
        ], [], 'NewsBundle');

        if (!is_null($blog)) {

            $newsCategory = self::itemSidebar([
                self::newsBundleRoles()['news_category']
            ], [
                self::newsBundlePathForEdit()['news_category']
            ], [
                self::newsBundleRouteName()['news_category']['index'], self::newsBundleRouteName()['news_category']['new']
            ], 'flaticon-map', false, null, null, 'sidebar.news.categories', [],
                self::newsBundleRouteName()['news_category']['index'], 'NewsBundle', true,
                '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <rect id="bound" x="0" y="0" width="24" height="24"/>
        <path d="M10.5,5 L19.5,5 C20.3284271,5 21,5.67157288 21,6.5 C21,7.32842712 20.3284271,8 19.5,8 L10.5,8 C9.67157288,8 9,7.32842712 9,6.5 C9,5.67157288 9.67157288,5 10.5,5 Z M10.5,10 L19.5,10 C20.3284271,10 21,10.6715729 21,11.5 C21,12.3284271 20.3284271,13 19.5,13 L10.5,13 C9.67157288,13 9,12.3284271 9,11.5 C9,10.6715729 9.67157288,10 10.5,10 Z M10.5,15 L19.5,15 C20.3284271,15 21,15.6715729 21,16.5 C21,17.3284271 20.3284271,18 19.5,18 L10.5,18 C9.67157288,18 9,17.3284271 9,16.5 C9,15.6715729 9.67157288,15 10.5,15 Z" id="Combined-Shape" fill="#000000"/>
        <path d="M5.5,8 C4.67157288,8 4,7.32842712 4,6.5 C4,5.67157288 4.67157288,5 5.5,5 C6.32842712,5 7,5.67157288 7,6.5 C7,7.32842712 6.32842712,8 5.5,8 Z M5.5,13 C4.67157288,13 4,12.3284271 4,11.5 C4,10.6715729 4.67157288,10 5.5,10 C6.32842712,10 7,10.6715729 7,11.5 C7,12.3284271 6.32842712,13 5.5,13 Z M5.5,18 C4.67157288,18 4,17.3284271 4,16.5 C4,15.6715729 4.67157288,15 5.5,15 C6.32842712,15 7,15.6715729 7,16.5 C7,17.3284271 6.32842712,18 5.5,18 Z" id="Combined-Shape" fill="#000000" opacity="0.3"/>
    </g>
</svg>');
            (!is_null($newsCategory)) ? $blog['items'][] = $newsCategory : null;

//             $newsAuthor = self::itemSidebar([
//                 self::newsBundleRoles()['news_author']
//             ], [
//                 self::newsBundlePathForEdit()['news_author']
//             ], [
//                 self::newsBundleRouteName()['news_author']['index'], self::newsBundleRouteName()['news_author']['new']
//             ], 'fa fa-sitemap', false, null, null, 'sidebar.news.author', [],
//                 self::newsBundleRouteName()['news_author']['index'], 'NewsBundle', true,
//                 '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
//     <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
//         <polygon id="Shape" points="0 0 24 0 24 24 0 24"/>
//         <path d="M18,14 C16.3431458,14 15,12.6568542 15,11 C15,9.34314575 16.3431458,8 18,8 C19.6568542,8 21,9.34314575 21,11 C21,12.6568542 19.6568542,14 18,14 Z M9,11 C6.790861,11 5,9.209139 5,7 C5,4.790861 6.790861,3 9,3 C11.209139,3 13,4.790861 13,7 C13,9.209139 11.209139,11 9,11 Z" id="Combined-Shape" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
//         <path d="M17.6011961,15.0006174 C21.0077043,15.0378534 23.7891749,16.7601418 23.9984937,20.4 C24.0069246,20.5466056 23.9984937,21 23.4559499,21 L19.6,21 C19.6,18.7490654 18.8562935,16.6718327 17.6011961,15.0006174 Z M0.00065168429,20.1992055 C0.388258525,15.4265159 4.26191235,13 8.98334134,13 C13.7712164,13 17.7048837,15.2931929 17.9979143,20.2 C18.0095879,20.3954741 17.9979143,21 17.2466999,21 C13.541124,21 8.03472472,21 0.727502227,21 C0.476712155,21 -0.0204617505,20.45918 0.00065168429,20.1992055 Z" id="Combined-Shape" fill="#000000" fill-rule="nonzero"/>
//     </g>
// </svg>');
//             (!is_null($newsAuthor)) ? $blog['items'][] = $newsAuthor : null;

//             $newsTags = self::itemSidebar([
//                 self::newsBundleRoles()['news_tags']
//             ], [
//                 self::newsBundlePathForEdit()['news_tags']
//             ], [
//                 self::newsBundleRouteName()['news_tags']['index'], self::newsBundleRouteName()['news_tags']['new']
//             ], 'flaticon-open-box', false, null, null, 'tags', [],
//                 self::newsBundleRouteName()['news_tags']['index'], 'NewsBundle', true,
//                 '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
//     <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
//         <rect id="Rectangle-5" x="0" y="0" width="24" height="24"/>
//         <path d="M6,7 C7.1045695,7 8,6.1045695 8,5 C8,3.8954305 7.1045695,3 6,3 C4.8954305,3 4,3.8954305 4,5 C4,6.1045695 4.8954305,7 6,7 Z M6,9 C3.790861,9 2,7.209139 2,5 C2,2.790861 3.790861,1 6,1 C8.209139,1 10,2.790861 10,5 C10,7.209139 8.209139,9 6,9 Z" id="Oval-7" fill="#000000" fill-rule="nonzero"/>
//         <path d="M7,11.4648712 L7,17 C7,18.1045695 7.8954305,19 9,19 L15,19 L15,21 L9,21 C6.790861,21 5,19.209139 5,17 L5,8 L5,7 L7,7 L7,8 C7,9.1045695 7.8954305,10 9,10 L15,10 L15,12 L9,12 C8.27142571,12 7.58834673,11.8052114 7,11.4648712 Z" id="Combined-Shape" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
//         <path d="M18,22 C19.1045695,22 20,21.1045695 20,20 C20,18.8954305 19.1045695,18 18,18 C16.8954305,18 16,18.8954305 16,20 C16,21.1045695 16.8954305,22 18,22 Z M18,24 C15.790861,24 14,22.209139 14,20 C14,17.790861 15.790861,16 18,16 C20.209139,16 22,17.790861 22,20 C22,22.209139 20.209139,24 18,24 Z" id="Oval-7-Copy" fill="#000000" fill-rule="nonzero"/>
//         <path d="M18,13 C19.1045695,13 20,12.1045695 20,11 C20,9.8954305 19.1045695,9 18,9 C16.8954305,9 16,9.8954305 16,11 C16,12.1045695 16.8954305,13 18,13 Z M18,15 C15.790861,15 14,13.209139 14,11 C14,8.790861 15.790861,7 18,7 C20.209139,7 22,8.790861 22,11 C22,13.209139 20.209139,15 18,15 Z" id="Oval-7-Copy-3" fill="#000000" fill-rule="nonzero"/>
//     </g>
// </svg>');
//             (!is_null($newsAuthor)) ? $blog['items'][] = $newsTags : null;

            $news = self::itemSidebar([
                self::newsBundleRoles()['news']
            ], [
                self::newsBundlePathForEdit()['news'], self::newsBundlePathForEdit()['quiz'],
            ], [
                self::newsBundleRouteName()['news']['index'], self::newsBundleRouteName()['news']['new']
            ], 'flaticon-notes', false, null, null, 'Записи', [],
                self::newsBundleRouteName()['news']['index'], 'NewsBundle', true,
                '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <rect id="bound" x="0" y="0" width="24" height="24"/>
        <path d="M9.61764706,5 L8.73529412,7 L3,7 C2.44771525,7 2,6.55228475 2,6 C2,5.44771525 2.44771525,5 3,5 L9.61764706,5 Z M14.3823529,5 L21,5 C21.5522847,5 22,5.44771525 22,6 C22,6.55228475 21.5522847,7 21,7 L15.2647059,7 L14.3823529,5 Z M6.08823529,13 L5.20588235,15 L3,15 C2.44771525,15 2,14.5522847 2,14 C2,13.4477153 2.44771525,13 3,13 L6.08823529,13 Z M17.9117647,13 L21,13 C21.5522847,13 22,13.4477153 22,14 C22,14.5522847 21.5522847,15 21,15 L18.7941176,15 L17.9117647,13 Z M7.85294118,9 L6.97058824,11 L3,11 C2.44771525,11 2,10.5522847 2,10 C2,9.44771525 2.44771525,9 3,9 L7.85294118,9 Z M16.1470588,9 L21,9 C21.5522847,9 22,9.44771525 22,10 C22,10.5522847 21.5522847,11 21,11 L17.0294118,11 L16.1470588,9 Z M4.32352941,17 L3.44117647,19 L3,19 C2.44771525,19 2,18.5522847 2,18 C2,17.4477153 2.44771525,17 3,17 L4.32352941,17 Z M19.6764706,17 L21,17 C21.5522847,17 22,17.4477153 22,18 C22,18.5522847 21.5522847,19 21,19 L20.5588235,19 L19.6764706,17 Z" id="Combined-Shape" fill="#000000" opacity="0.3"/>
        <path d="M11.044,5.256 L13.006,5.256 L18.5,19 L16,19 L14.716,15.084 L9.19,15.084 L7.5,19 L5,19 L11.044,5.256 Z M13.924,13.14 L11.962,7.956 L9.964,13.14 L13.924,13.14 Z" id="A" fill="#000000"/>
    </g>
</svg>');
            (!is_null($news)) ? $blog['items'][] = $news : null;

            // $video = self::itemSidebar(['ROLE_DIRECTOR'], ['video/edit'], [
            //     'dashboard_video_index', 'dashboard_video_new'
            // ], 'flaticon-notes', false, null, null, 'Видео', [],
            //     'dashboard_video_index');
            // (!is_null($video)) ? $blog['items'][] = $video : null;

            // $NewsCommentRepository = $this->em->getRepository(NewsComment::class);
            // $new_news_cnt = $NewsCommentRepository->getHiddenElementsCnt();

            // $comments = self::itemSidebar(
            //     [ self::newsBundleRoles()['news_comments'] ],
            //     [ self::newsBundlePathForEdit()['news_comments'] ],
            //     [ self::newsBundleRouteName()['news_comments']['index'], self::newsBundleRouteName()['news_comments']['new'] ],
            //     'flaticon-notes', true, $new_news_cnt, null, 'Комментарии', [],
            //     'dashboard_news_comments_index'
            // );
            // (!is_null($comments)) ? $blog['items'][] = $comments : null;

            return $blog;
        }

        return null;
    }
}
