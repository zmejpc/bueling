<?php

namespace UploadBundle\EventListener;

use UploadBundle\Services\FileHandler;
use Doctrine\ORM\Event\LifecycleEventArgs;

/**
 * @author Design studio origami <https://origami.ua>
 */
class FileDeleteListener
{
    /**
     * @var FileHandler
     */
    private $fileHandler;

    /**
     * @var
     */
    private $elements;

    /**
     * FileDeleteListener constructor.
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
    public function preRemove(LifecycleEventArgs $args)
    {
        $this->fileHandler->preRemove($args, $this->elements);
    }
}