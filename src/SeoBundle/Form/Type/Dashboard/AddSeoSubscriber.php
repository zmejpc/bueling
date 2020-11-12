<?php

namespace SeoBundle\Form\Type\Dashboard;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @author Design studio origami <https://origami.ua>
 */
class AddSeoSubscriber implements EventSubscriberInterface
{
    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            FormEvents::PRE_SUBMIT => 'preSubmit'
        ];
    }

    /**
     * @param FormEvent $event
     */
    public function preSubmit(FormEvent $event)
    {
        $data = $event->getData();
        if(!empty($data['translations']['translations'])){
            foreach ($data['translations']['translations'] as $locale => $item) {
                $title = $item['title'];
                if (empty($data['seo']['seo']['translations']['translations'][$locale]['metaTitle'])) {
                    $data['seo']['seo']['translations'][$locale]['metaTitle'] = $title;
                }
                if (empty($data['seo']['seo']['translations'][$locale]['h1'])) {
                    $data['seo']['seo']['translations'][$locale]['h1'] = $title;
                }
                if (empty($data['seo']['seo']['translations'][$locale]['breadcrumb'])) {
                    $data['seo']['seo']['translations'][$locale]['breadcrumb'] = $title;
                }
            }
        }else{
            foreach ($data['translations'] as $locale => $item) {
                $title = $item['title'];
                if (empty($data['seo']['translations'][$locale]['metaTitle'])) {
                    $data['seo']['translations'][$locale]['metaTitle'] = $title;
                }
                if (empty($data['seo']['translations'][$locale]['h1'])) {
                    $data['seo']['translations'][$locale]['h1'] = $title;
                }
                if (empty($data['seo']['translations'][$locale]['breadcrumb'])) {
                    $data['seo']['translations'][$locale]['breadcrumb'] = $title;
                }
            }
        }
        $event->setData($data);
    }
}