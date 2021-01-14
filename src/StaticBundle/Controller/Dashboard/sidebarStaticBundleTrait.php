<?php

namespace StaticBundle\Controller\Dashboard;

/**
 * @author Design studio origami <https://origami.ua>
 */
trait sidebarStaticBundleTrait
{
    /**
     * @return array
     */
    private function staticBundlePathForEdit(): array
    {
        return [
            'static_content' => 'static-content/edit/',
            'static_page' => 'static-page/edit/',
        ];
    }

    /**
     * @return array
     */
    private function staticBundleRouteName(): array
    {
        return [
            'static_page' => [
                'index' => 'dashboard_static_page_index', 'new' => 'dashboard_static_page_new'
            ],
            'static_content' => [
                'index' => 'dashboard_static_content_index', 'new' => 'dashboard_static_content_new'
            ],
        ];
    }

    /**
     * @return array
     */
    private function staticBundleRoles(): array
    {
        return [
            'static_content' => 'ROLE_STATIC',
            'static_page' => 'ROLE_STATIC',
        ];
    }

    /**
     * @return array|null
     */
    private function sidebarStaticBundle(): ?array
    {
        $static = self::itemSidebar([
            self::staticBundleRoles()['static_page']
        ], [
            self::staticBundlePathForEdit()['static_content'], self::staticBundlePathForEdit()['static_page']
        ], [
            self::staticBundleRouteName()['static_content']['index'], self::staticBundleRouteName()['static_content']['new'],
            self::staticBundleRouteName()['static_page']['index'], self::staticBundleRouteName()['static_page']['new'],
            'dashboard_slider_index', 'dashboard_slider_new', 'dashboard_slider_edit', 'dashboard_region_index', 'dashboard_region_edit', 'dashboard_documents_index', 'dashboard_documents_edit',
        ], 'flaticon-edit-1', false, null, null,
            'sidebar.configuration.static_partition.static_partition', [], null, 'StaticBundle',
            true, '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <rect id="bound" x="0" y="0" width="24" height="24"/>
        <path d="M12.2674799,18.2323597 L12.0084872,5.45852451 C12.0004303,5.06114792 12.1504154,4.6768183 12.4255037,4.38993949 L15.0030167,1.70195304 L17.5910752,4.40093695 C17.8599071,4.6812911 18.0095067,5.05499603 18.0083938,5.44341307 L17.9718262,18.2062508 C17.9694575,19.0329966 17.2985816,19.701953 16.4718324,19.701953 L13.7671717,19.701953 C12.9505952,19.701953 12.2840328,19.0487684 12.2674799,18.2323597 Z" id="Path-11" fill="#000000" fill-rule="nonzero" transform="translate(14.701953, 10.701953) rotate(-135.000000) translate(-14.701953, -10.701953) "/>
        <path d="M12.9,2 C13.4522847,2 13.9,2.44771525 13.9,3 C13.9,3.55228475 13.4522847,4 12.9,4 L6,4 C4.8954305,4 4,4.8954305 4,6 L4,18 C4,19.1045695 4.8954305,20 6,20 L18,20 C19.1045695,20 20,19.1045695 20,18 L20,13 C20,12.4477153 20.4477153,12 21,12 C21.5522847,12 22,12.4477153 22,13 L22,18 C22,20.209139 20.209139,22 18,22 L6,22 C3.790861,22 2,20.209139 2,18 L2,6 C2,3.790861 3.790861,2 6,2 L12.9,2 Z" id="Path-57" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
    </g>
</svg>');

        if (!is_null($static)) {
            $staticContent = self::itemSidebar([
                self::staticBundleRoles()['static_content']
            ], [
                self::staticBundlePathForEdit()['static_content']
            ], [
                self::staticBundleRouteName()['static_content']['index'], self::staticBundleRouteName()['static_content']['new']
            ],
                'flaticon-list-1', false, null, null,
                'sidebar.configuration.static_partition.static_content', [],
                self::staticBundleRouteName()['static_content']['index'], 'StaticBundle', true,
                '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <rect id="bound" x="0" y="0" width="24" height="24"/>
        <path d="M3,19 L5,19 L5,21 L3,21 L3,19 Z M8,19 L10,19 L10,21 L8,21 L8,19 Z M13,19 L15,19 L15,21 L13,21 L13,19 Z M18,19 L20,19 L20,21 L18,21 L18,19 Z" id="Combined-Shape" fill="#000000" opacity="0.3"/>
        <path d="M10.504,3.256 L12.466,3.256 L17.956,16 L15.364,16 L14.176,13.084 L8.65000004,13.084 L7.49800004,16 L4.96000004,16 L10.504,3.256 Z M13.384,11.14 L11.422,5.956 L9.42400004,11.14 L13.384,11.14 Z" id="A" fill="#000000"/>
    </g>
</svg>');
            (!is_null($staticContent)) ? $static['items'][] = $staticContent : null;

            $staticPage = self::itemSidebar([
                self::staticBundleRoles()['static_page']
            ], [
                self::staticBundlePathForEdit()['static_page']
            ], [
                self::staticBundleRouteName()['static_page']['index'],
                self::staticBundleRouteName()['static_page']['new']
            ],
                'flaticon-file-2', false, null, null,
                'sidebar.configuration.static_partition.static_pages', [],
                self::staticBundleRouteName()['static_page']['index'], 'StaticBundle', true,
                '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
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
</svg>');
            (!is_null($staticPage)) ? $static['items'][] = $staticPage : null;

            $slider = self::itemSidebar(['ROLE_SLIDER'], ['slider/edit/'], [
                'dashboard_slider_index', 'dashboard_slider_new', 'dashboard_slider_edit'
            ],
                'flaticon-file-2', false, null, null,
                'Слайдер', [],
                'dashboard_slider_index', 'DashboardBundle', true,
                '<i class="fa fa-image"></i>');
            (!is_null($slider)) ? $static['items'][] = $slider : null;

            $regions = self::itemSidebar(['ROLE_DIRECTOR'], ['region/edit/'], [
                'dashboard_region_index', 'dashboard_region_edit'
            ],
                'flaticon-file-2', false, null, null,
                'Регионы', [],
                'dashboard_region_index', 'DashboardBundle', true,
                '<i class="fa fa-map-signs"></i>');
            (!is_null($regions)) ? $static['items'][] = $regions : null;

            $documents = self::itemSidebar(['ROLE_DIRECTOR'], ['documents/edit/'], [
                'dashboard_documents_index', 'dashboard_documents_edit'
            ],
                'flaticon-file-2', false, null, null,
                'Документы', [],
                'dashboard_documents_index', 'DashboardBundle', true,
                '<i class="fa fa-book"></i>');
            (!is_null($documents)) ? $static['items'][] = $documents : null;

            return $static;
        }

        return null;
    }
}
