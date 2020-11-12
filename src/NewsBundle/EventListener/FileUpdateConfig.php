<?php

namespace NewsBundle\EventListener;

/**
 * @author Design studio origami <https://origami.ua>
 */
final class FileUpdateConfig
{
    public $elements = [
        'News' => ['poster'],
        'NewsAuthor' => ['poster'],
        'NewsElement' => ['img'],
        'NewsGalleryImage' => ['img'],
    ];
}
