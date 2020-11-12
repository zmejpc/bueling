<?php

namespace BackendBundle\EventListener;

use UploadBundle\Services\FileHandler;

/**
 * @author Design studio origami <https://origami.ua>
 */
final class FileUpdateListener extends \UploadBundle\EventListener\FileUpdateListener
{
    public function __construct(FileHandler $fileHandler)
    {
        parent::__construct($fileHandler, new FileUpdateConfig());
    }
}
