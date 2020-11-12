<?php

namespace UserBundle\EventListener;

use UploadBundle\Services\FileHandler;

/**
 * @author Design studio origami <https://origami.ua>
 */
final class FileDeleteListener extends \UploadBundle\EventListener\FileDeleteListener
{
    /**
     * FileDeleteListener constructor.
     * @param FileHandler $fileHandler
     */
    public function __construct(FileHandler $fileHandler)
    {
        parent::__construct($fileHandler, new FileUpdateConfig());
    }
}
