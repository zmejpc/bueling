<?php

namespace BackendBundle\EventSubscriber;

use BackendBundle\Event\FilterEvent;
use BackendBundle\Event\AjaxCheckboxEvent;
use Ecommerce\Entity\Product;
use Ecommerce\Entity\ProductCategory;
use ExportBundle\Entity\GoogleMerchantCategory;
use ExportBundle\Entity\PromCategory;
use ExportBundle\Entity\ZakupkaCategory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class BackendEventSubscriber implements EventSubscriberInterface
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public static function getSubscribedEvents()
    {
        return [
            'dashboard.list.filter' => [
                ['onFilterEvent'],
            ],
            'dashboard.list.ajax.checkbox' => [
                ['onAjaxCheckboxEvent'],
            ],
        ];
    }

    public function onFilterEvent(FilterEvent $event)
    {
        $request = $event->getRequest()->request;

        if(!empty($request->all()['query']['filter'])) {
            if(array_key_exists('categories-translations-title', $request->all()['query']['filter'])) {

                $category = $this->em->getRepository(ProductCategory::class)
                    ->getProductCategoryBySlug($request->all()['query']['filter']['categories-translations-title']);

                if($category) {
                    $requestArray = $request->all();
                    unset($requestArray['query']['filter']['categories-translations-title']);

                    if(!$this->em->getRepository(Product::class)->countByCategoryId($category->getId())) {
                        
                        $children = $this->em->getRepository(ProductCategory::class)
                            ->getChildrenById((int)$category->getId());

                        if(!empty($children)) {
                            $requestArray['query']['filter']['categories-slug'] = array_column($children, 'slug');
                        }                    
                    } else {
                        $requestArray['query']['filter']['categories-slug'] = $category->getSlug();
                    }

                    $request->replace($requestArray);
                }
            }

            if(array_key_exists('translations-treeTitle', $request->all()['query']['filter'])) {

                $category = $this->em->getRepository(ProductCategory::class)
                    ->getProductCategoryBySlug($request->all()['query']['filter']['translations-treeTitle']);

                if($category) {
                    $requestArray = $request->all();
                    unset($requestArray['query']['filter']['translations-treeTitle']);
                    $requestArray['query']['filter']['id'] = [$category->getId()];
                    
                    $children = $this->em->getRepository(ProductCategory::class)
                            ->getChildrenById((int)$category->getId());

                    if(!empty($children))
                        $requestArray['query']['filter']['id'] = array_merge($requestArray['query']['filter']['id'], array_column($children, 'id'));

                    $request->replace($requestArray);
                }
            }
        }
    }

    public function onAjaxCheckboxEvent(AjaxCheckboxEvent $event)
    {
        $entity = $event->getEntity();

        if(get_class($entity) == GoogleMerchantCategory::class) {
            $unload = $event->getMethodArgument();
            $category = $entity->getCategory();

            $this->setGoogleMerchantCategoryUnload($category, $unload);
            $this->em->flush();
        }

        if(get_class($entity) == PromCategory::class) {
            $unload = $event->getMethodArgument();
            $category = $entity->getCategory();

            $this->setPromCategoryUnload($category, $unload);
            $this->em->flush();
        }

        if(get_class($entity) == ZakupkaCategory::class) {
            $unload = $event->getMethodArgument();
            $category = $entity->getCategory();

            $this->setZakupkaCategoryUnload($category, $unload);
            $this->em->flush();
        }
    }

    private function setGoogleMerchantCategoryUnload(ProductCategory $category, bool $unload)
    {
        $googleMerchantCategory = $this->em->getRepository(GoogleMerchantCategory::class)->findOneBy(['category' => $category]);
        $googleMerchantCategory->setUnload($unload);

        if($category->hasChildren()) {
            foreach($category->getChildren() as $child) {
                $this->setGoogleMerchantCategoryUnload($child, $unload);
            }
        }
    }

    private function setPromCategoryUnload(ProductCategory $category, bool $unload)
    {
        $promCategory = $this->em->getRepository(PromCategory::class)->findOneBy(['category' => $category]);
        $promCategory->setUnload($unload);

        if($category->hasChildren()) {
            foreach($category->getChildren() as $child) {
                $this->setPromCategoryUnload($child, $unload);
            }
        }
    }

    private function setZakupkaCategoryUnload(ProductCategory $category, bool $unload)
    {
        $zakupkaCategory = $this->em->getRepository(ZakupkaCategory::class)->findOneBy(['category' => $category]);
        $zakupkaCategory->setUnload($unload);

        if($category->hasChildren()) {
            foreach($category->getChildren() as $child) {
                $this->setZakupkaCategoryUnload($child, $unload);
            }
        }
    }
}