<?php

namespace UploadBundle\EventListener;

use UploadBundle\Services\FileHandler;
use Doctrine\ORM\Event\LifecycleEventArgs;

/**
 * @author Design studio origami <https://origami.ua>
 */
class FileUpdateListener
{
    /**
     * @var
     */
    private $elements;
    /**
     * @var FileHandler
     */
    private $fileHandler;

    /**
     * FileUpdateListener constructor.
     * @param FileHandler $fileHandler
     * @param $config
     */
    public function __construct(FileHandler $fileHandler, $config)
    {
        $this->fileHandler = $fileHandler;
        $this->elements = $config->elements;
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function preUpdate(LifecycleEventArgs $args)
    {
        $this->fileHandler->preUpdate($args, $this->elements);
    }
}