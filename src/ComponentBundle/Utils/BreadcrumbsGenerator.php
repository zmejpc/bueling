<?php

namespace ComponentBundle\Utils;

use SeoBundle\Utils\SeoManager;
use Symfony\Component\Routing\RouterInterface;

/**
 * @author Design studio origami <https://origami.ua>
 */
final class BreadcrumbsGenerator
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var SeoManager
     */
    private $seoManager;

    /**
     * BreadcrumbsGenerator constructor.
     * @param RouterInterface $router
     * @param SeoManager $seoManager
     */
    public function __construct(RouterInterface $router, SeoManager $seoManager)
    {
        $this->router = $router;
        $this->seoManager = $seoManager;
    }

    /**
     * Generates array for breadcrumbs menu with parameters url,title
     * All available routes are set in $this->menu parameter
     * $arr should be an array like ['category']=>['title'=>'Some Text','parameters'=>['par1'=>'asd']]
     *
     * @param array $arr
     * @return array|bool
     * @throws \Exception
     */
    public function generateBreadcrumbs(array $arr): array
    {
        $result = [];

        if (count($arr) < 1) {
            return $result;
        }

        foreach ($arr as $key => $value) {
            foreach ($value as $valueKey => $item) {
                if (!isset($item['parameters']) || !isset($item['title'])) {
                    throw new \Exception('Отсутствует маршрут для ключа ' . $valueKey . ' или не заданы параметры title,parameters ');
                }
                $route = $key;
                $temp = [];
                if (is_string($route)) {
                    $temp['url'] = $this->router->generate($route, $item['parameters']);
                } else {
                    $temp['url'] = 'javascript:void(0);';
                }
                $temp['title'] = $item['title'];
                $result[] = $temp;
            }
        }

        return $result;
    }

    /**
     * @return array
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getBreadcrumbForHomePage(): array
    {
        $breadcrumbsArr = [];
        $seoHomepage = $this->seoManager->getSeoForHomePage();
        $breadcrumbsArr['frontend_homepage'][] = [
            'parameters' => [],
            'title' => (!empty($seoHomepage) and !empty($seoHomepage->breadcrumb)) ? $seoHomepage->breadcrumb : ''
        ];

        return $breadcrumbsArr;
    }
}
