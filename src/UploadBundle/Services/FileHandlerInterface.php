<?php

namespace UploadBundle\Services;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @author Design studio origami <https://origami.ua>
 */
interface FileHandlerInterface
{
    /**
     * FileHandler constructor.
     * @param ContainerInterface $container
     * @param EntityManagerInterface $em
     */
    public function __construct(ContainerInterface $container, EntityManagerInterface $em);

    /**
     * @param string $filePath
     * @param bool $isFullPath
     */
    public function unlinkFile(string $filePath, bool $isFullPath = false);

    /**
     * @param $entity
     * @param array $children
     * @throws \Exception
     */
    public function saveFileOrImageFromForm($entity, array $children);

    public function clearTempDirForLastDay();

    /**
     * @param array $data
     * @param string $subDir
     * @return array
     * @throws \Exception
     */
    public function handleFileAndSave(array $data, string $subDir);

    /**
     * @param LifecycleEventArgs $args
     * @param $elements
     */
    public function preRemove(LifecycleEventArgs $args, $elements);

    /**
     * @param LifecycleEventArgs $args
     * @param $elements
     */
    public function preUpdate(LifecycleEventArgs $args, $elements);
}