<?php

namespace BackendBundle\EventListener;

use UploadBundle\Services\FileHandler;

/**
 * @author Design studio origami <https://origami.ua>
 */
final class FileDeleteListener extends \UploadBundle\EventListener\FileDeleteListener
{
    public function __construct(FileHandler $fileHandler)
    {
        parent::__construct($fileHandler, new FileUpdateConfig());
    }
}
