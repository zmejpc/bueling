<?php

namespace Ecommerce\Controller\Dashboard;

use Doctrine\ORM\EntityManagerInterface;

/**
 * @author Design studio origami <https://origami.ua>
 */
trait sidebarEcommerceBundleTrait
{   
    private function sidebarEcommerceBundle()
    {
    	$menu = self::headingSidebar('', 'E-commerce', [
            self::eCommerceBundleRoles()['product_catogory']
        ], [], 'DashboardBundle');

        if(!is_null($menu)) {
	        $productCategories = self::itemSidebar(['ROLE_PRODUCT_CATEGORY_CREATE_EDIT'], [], ['dashboard_product_category_index', 'dashboard_product_category_new', 'dashboard_product_category_edit'], 'fa fa-warehouse', false, null, null, 'Бренды', [], 'dashboard_product_category_index');

            (!is_null($productCategories)) ? $menu['items'][] = $productCategories : null;

            $products = self::itemSidebar(['ROLE_PRODUCT_CREATE_EDIT'], ['product/edit/'], ['dashboard_product_index', 'dashboard_product_new', 'dashboard_product_edit'], 'fa fa-gem', false, null, null, 'sidebar.catalog.products.products', [], 'dashboard_product_index');

            (!is_null($products)) ? $menu['items'][] = $products : null;

            $features = self::itemSidebar(['ROLE_PRODUCT_CREATE_EDIT'], ['product/feature/edit/'], ['dashboard_product_feature_index', 'dashboard_product_feature_new', 'dashboard_product_feature_edit'], 'fa fa-ankh', false, null, null, 'Характеристики', [], 'dashboard_product_feature_index');

            (!is_null($features)) ? $menu['items'][] = $features : null;

            $activityAreas = self::itemSidebar(['ROLE_PRODUCT_CREATE_EDIT'], ['activity-area/edit/'], ['dashboard_activity_area_index', 'dashboard_activity_area_new', 'dashboard_activity_area_edit'], 'fa fa-won-sign', false, null, null, 'Направления', [], 'dashboard_activity_area_index');

            (!is_null($activityAreas)) ? $menu['items'][] = $activityAreas : null;
        }

        return $menu;
    }

    private function eCommerceBundleRoles(): array
    {
        return [
            'product_catogory' => 'ROLE_PRODUCT_CATEGORY_CREATE_EDIT',
            'product' => 'ROLE_PRODUCT_CREATE_EDIT',
        ];
    }
}