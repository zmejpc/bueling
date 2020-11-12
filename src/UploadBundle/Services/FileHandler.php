<?php

namespace UploadBundle\Services;

use Imagick;
use ImagickPixel;
use Symfony\Component\Form\Form;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @author Design studio origami <https://origami.ua>
 */
class FileHandler implements FileHandlerInterface
{
    /**
     * @var
     */
    protected $session;

    /**
     * @var mixed
     */
    protected $config;

    /**
     * @var
     */
    protected $sessionAttr;

    /**
     * @var mixed
     */
    protected $uploadTempDir;

    /**
     * @var mixed
     */
    protected $webDir;

    /**
     * @var bool|string
     */
    protected $rootDir;

    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var ContainerInterface
     */
    protected $container;

    protected $imageFormat = 'jpeg';

    /**
     * FileHandler constructor.
     * @param ContainerInterface $container
     * @param EntityManagerInterface $em
     */
    public function __construct(ContainerInterface $container, EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->container = $container;
        $this->rootDir = realpath($container->get('kernel')->getProjectDir());
        $this->webDir = $container->getParameter('upload.web_dir');
        $this->uploadTempDir = $container->getParameter('upload.temp_upload_dir');
        $this->config = $container->getParameter('upload.types');
    }

    /* check`s whether file is in a right format and exists */
    /**
     * @param string $file
     * @return bool
     */
    protected function fileValid(string $file): bool
    {
        $test = json_decode($file, true);
        if ((!$test) || (!isset($test['default_file']))) {
            return false;
        }
        $file = realpath($this->rootDir) . '/' . $this->webDir . $test['default_file'];

        return file_exists($file);
    }

    /* Get`s file path as parameter and return`s Imagick object */
    /**
     * @param string $path
     * @param bool $json_detect
     * @return Imagick
     * @throws \ImagickException
     */
    protected function prepareImgFile(string $path, bool $json_detect = false)
    {
        if ($json_detect) {
            $arrayPath = json_decode($path, true);
            $path = $arrayPath['default_file'];
        }

        $file = realpath($this->rootDir) . '/' . $this->webDir . $path;
        if (!$file) {
            throw new \Exception('No file exists for path = ' . $path);
        }

        $img = new Imagick($file);
        $img->setImageMatte(true);

        return $img;
    }

    /**
     * @param $dir
     */
    protected function checkDir(string $dir)
    {
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        @chmod($dir, 0777);
    }

    /**
     * @param $path
     * @param $action
     * @param $width
     * @param $height
     * @return Imagick
     * @throws \ErrorException
     * @throws \ImagickException
     */
    protected function performResize(string $path, ?string $action, ?int $width, ?int $height)
    {
        $img = new Imagick($path);

        if (!$img->getImageHeight()) {
            throw new \ErrorException('Error while file handling');
        }

        switch ($action) {
            case "exact_resize":
                $img->resizeImage($width, $height, Imagick::FILTER_LANCZOS, 1, true);
                break;
            case "landscape_resize":
                $img->resizeImage($width, null, Imagick::FILTER_LANCZOS, 1, true);
                break;
            case "portrait_resize":
                $img->resizeImage(null, $height, Imagick::FILTER_LANCZOS, 1, true);
                break;
            case "resize_and_crop":
                $img = $this->resizeAndCropImage($img, $width, $height);
                break;
            case "exact_crop":
                if ($img->getImageWidth() < $width || $img->getImageHeight() < $height) {
                    $arr = [];
                    $arr['th_height'] = $height;
                    $arr['th_width'] = $width;
                    $arr['im_width'] = $img->getImageWidth();
                    $arr['im_height'] = $img->getImageHeight();
                    $resize = $this->getCropResizeParameters($arr);
                    $img->resizeImage($resize['width'], $resize['height'], Imagick::FILTER_LANCZOS, 1);
                }
                $img->cropImage($width, $height, 0, 0);
                break;
            default:
                $img->resizeImage($width, $height, Imagick::FILTER_LANCZOS, 1);
                break;
        }

        $flattened = new IMagick();
        $flattened->newImage($img->getImageWidth(), $img->getImageHeight(), new ImagickPixel("white"));
        $flattened->setImageCompressionQuality(50);
        $flattened->compositeImage($img, imagick::COMPOSITE_OVER, 0, 0);
        $flattened->setImageFormat($this->imageFormat);
        // $flattened->trimImage(0);
        $img = $flattened;

        return $img;
    }

    public function resizeAndCropImage($img, $width, $height)
    {
        if($img->getImageWidth() < $width || $img->getImageHeight() < $height) {
            if ($img->getImageWidth() > $img->getImageHeight()) {
                $img->resizeImage(null, $height, Imagick::FILTER_LANCZOS, 1);
                $x = abs($img->getImageWidth() - $width) / 2;
                $y = 0;
            }
            else {
                $img->resizeImage($width, null, Imagick::FILTER_LANCZOS, 1);
                $x = 0;
                $y = abs($img->getImageHeight() - $height) / 2;
            }
        } else {
            $x = abs($img->getImageWidth() - $width) / 2;
            $y = abs($img->getImageHeight() - $height) / 2;
        }

        $img->cropImage($width, $height, $x, $y);
        return $img;
    }

    /**
     * @param string $filePath
     * @param bool $isFullPath
     */
    public function unlinkFile(string $filePath, bool $isFullPath = false)
    {
        if (!$isFullPath) {
            $fullPath = $this->rootDir . '/' . $this->webDir . $filePath;
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }
        } else {
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
    }

    private function helperForSaveFileOrImageFromForm($fieldDescription)
    {
        if ($fieldDescription instanceof Form) {
            foreach ($fieldDescription as $fieldName => $element) {
                self::helperManageEmbeddedImage($fieldDescription->getData(), $element, $fieldName, uniqid());
                self::helperForSaveFileOrImageFromForm($element);
            }
        } else {
            if ($fieldDescription->getConfig()->getType()->getBlockPrefix() === 'dashboard_collection') {
                foreach ($fieldDescription->all() as $item) {
                    foreach ($item->all() as $fieldName => $element) {
                        self::helperManageEmbeddedImage($item->getData(), $element, $fieldName, uniqid());
                        self::helperForSaveFileOrImageFromForm($element);
                    }
                }
            }
        }
    }

    /**
     * @param $entity
     * @param array $children
     * @throws \Exception
     */
    public function saveFileOrImageFromForm($entity, array $children)
    {
        foreach ($children as $fieldName => $fieldDescription) {
            $this->helperManageEmbeddedImage($entity, $fieldDescription, $fieldName, uniqid());
            self::helperForSaveFileOrImageFromForm($fieldDescription);
        }
    }

    /**
     * @param string $path
     * @param string $dir
     * @return array
     * @throws \Exception
     */
    private function saveFile(string $path, string $dir)
    {
        $fullDirPath = $this->rootDir . '/' . $this->webDir . $dir;
        $fullPath = $this->rootDir . '/' . $this->webDir . $path;
        $tempFullPath = $this->rootDir . '/' . $this->webDir . $path;
        $this->checkDir($fullDirPath);
        $result = [];
        $remoteFlag = false;
        if (
            filter_var($path, FILTER_VALIDATE_URL) and
            @get_headers($path)[0] == ('HTTP/1.1 200 OK' || 'HTTP/1.0 200 OK')
        ) {
            $remoteFlag = true;
        }

        if ($remoteFlag) {
            $fullPath = $path;
            $tempFullPath = $path;
        } elseif (!file_exists($fullPath)) {
            throw new \Exception('FileHandler::SaveImage - no file present');
        }
        $fileAttr = pathinfo($fullPath);
        if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $fullPath)) {
            $fullPath = urldecode($fullPath);
            $tempFullPath = urldecode($tempFullPath);
        }
        $fileName = uniqid();
        copy($fullPath, $fullDirPath . '/' . $fileAttr['basename']);
        rename(
            $fullDirPath . '/' . $fileAttr['basename'],
            $fullDirPath . '/' . $fileName . '.' . $fileAttr['extension']
        );
        $result['default_file'] = $dir . '/' . $fileName . '.' . $fileAttr['extension'];

        if (!$remoteFlag) {
            $this->unlinkFile($tempFullPath, true);
        }

        return $result;
    }

    /**
     * @param string $filePath
     * @param string $destinationDir
     * @param array $thumbs
     * @return array
     * @throws \ErrorException
     * @throws \ImagickException
     */
    private function saveImage(string $filePath, string $destinationDir, array $thumbs)
    {
        $fullDestinationDir = $this->rootDir . '/' . $this->webDir . $destinationDir;
        $this->checkDir($fullDestinationDir);
        $result = [];
        $fullPath = $this->rootDir . '/' . $this->webDir . $filePath;
        $tempFullPath = $this->rootDir . '/' . $this->webDir . $filePath;
        $remoteFlag = false;
        if (
            filter_var($filePath, FILTER_VALIDATE_URL) and
            @get_headers($filePath)[0] == ('HTTP/1.1 200 OK' || 'HTTP/1.0 200 OK')
        ) {
            $remoteFlag = true;
        }

        if (@get_headers($filePath)[0] == 'HTTP/1.1 301 Moved Permanently') {
            return null;
        }
        elseif (@get_headers($filePath)[0] == 'HTTP/1.1 404 Not Found') {
            return null;
        }
        elseif (@get_headers($filePath)[0] == ('HTTP/1.0 404 Not Found')) {
            return null;
        }

        if ($remoteFlag) {
            $fullPath = $filePath;
            $tempFullPath = $filePath;
        } elseif (!file_exists($fullPath)) {
            return null;
            throw new \Exception('FileHandler::SaveImage - no file present');
        }

        $fileAttr = pathinfo($fullPath);
        copy($fullPath, $fullDestinationDir . '/' . $fileAttr['basename']);
        $result['default_file'] = $destinationDir . '/' . $fileAttr['basename'];
        $fullPath = $this->rootDir . '/' . $this->webDir . $result['default_file'];

        foreach ($thumbs as $key => $thumb) {
            if (isset($thumb['action']) == true) {
                $img = $this->performResize($fullPath, $thumb['action'], $thumb['width'], $thumb['height']);
                $this->checkDir($fullDestinationDir);
                $name = uniqid();
                $path = $fullDestinationDir . '/' . $name . '.' . $this->imageFormat;
                $img->writeImage($path);
                $result[$key] = $destinationDir . '/' . $name . '.' . $this->imageFormat;

                if($this->imageFormat == 'png') {
                    $sourse = imagecreatefrompng($path);
                    imagealphablending($sourse, true);
                    imagesavealpha($sourse, true);
                } else {
                    $sourse = imagecreatefromjpeg($path);
                }

                imagepalettetotruecolor($sourse);

                $path = $fullDestinationDir . '/' . $name . '.webp';
                imagewebp($sourse, $path);
                $result['webp'][$key] = $destinationDir . '/' . $name . '.webp';
            }

//            if (isset($thumb['watermark']) == true) {
//                $watermark = new \Imagick(__DIR__ . '/../Resources/public/images/watermarks/' . $thumb['watermark']);
//                $watermark->setImageFormat('jpeg');
//                $watermark->setImageOpacity($thumb['opacity']);
//                $paddingX = $img->getImageWidth() - $watermark->getImageWidth() - $thumb['padding-x'];
//                $paddingY = $img->getImageHeight() - $watermark->getImageHeight() - $thumb['padding-y'];
//                $img->compositeImage($watermark, imagick::COMPOSITE_OVER, $paddingX, $paddingY);
//            }
        }

        if (!$remoteFlag) {
            $this->unlinkFile($tempFullPath, true);
        }

        return $result;
    }

    /**
     * @param $entity
     * @param $fieldDescription
     * @param string $fieldName
     * @param string $folder
     * @throws \Exception
     */
    private function helperManageEmbeddedImage($entity, $fieldDescription, string $fieldName, string $folder)
    {
        $blockPrefix = $fieldDescription->getConfig()->getType()->getBlockPrefix();

        if ($blockPrefix === 'upload' or $blockPrefix === 'upload_file') {
            $getter = 'get' . ucfirst($fieldName);
            $filePath = $entity->$getter();
            $data = json_decode($filePath, true);
            if (!is_array($data)) {
                $data = json_decode($data, true);
                $subDir = $folder;
                $this->handleFile($entity, $data, $fieldName, $subDir);
            }
        }
    }

    /**
     * @param $entity
     * @param $data
     * @param string $fieldName
     * @param string $subDir
     * @throws \Exception
     */
    private function handleFile($entity, $data, string $fieldName, string $subDir)
    {
        if ((isset($data["file_type"])) && (isset($data["path"]))) {
            $setter = 'set' . ucfirst($fieldName);
            $resultRes = $this->handleFileAndSave($data, $subDir);
            $entity->$setter(json_encode($resultRes));
            $this->em->persist($entity);
        }
    }

    /**
     *
     */
    public function clearTempDirForLastDay()
    {
        $date = new \DateTime();
        $date = $date->modify('-1 day');
        $monthDir = $date->format('m-Y');
        $dayDir = $date->format('d');

        $dir = $this->rootDir . '/' . $this->webDir . '/' . $this->uploadTempDir . '/' . $monthDir . '/' . $dayDir;

        if (!is_dir($dir)) {
            return;
        }

        $it = new \RecursiveDirectoryIterator($dir);
        $files = new \RecursiveIteratorIterator($it, \RecursiveIteratorIterator::CHILD_FIRST);

        foreach ($files as $file) {
            if ($file->getFilename() === '.' || $file->getFilename() === '..') {
                continue;
            }
            if ($file->isDir()) {
                rmdir($file->getRealPath());
            } else {
                unlink($file->getRealPath());
            }
        }

        rmdir($dir);
    }

    /**
     * @param array $data
     * @param string $subDir
     * @return array
     * @throws \Exception
     */
    public function handleFileAndSave(array $data, string $subDir)
    {
        $filePath = $data['path'];
        $fieldType = $data['file_type'];
        $uploadDir = $this->config[$fieldType]['upload_dir'];
        $dir = $uploadDir . '/' . date("Y") . '/' . date("m") . '/' . date("d") . '/' . $subDir;

        if ($this->config[$fieldType]['type'] == 'file' || $this->config[$fieldType]['type'] == 'video') {
            return $result = $this->saveFile($filePath, $dir);
        } elseif ($this->config[$fieldType]['type'] == 'image') {
            return $result = $this->saveImage($filePath, $dir, $this->config[$fieldType]['thumbnails']);
        }

        throw new \Exception('Unrecognized file type!');
    }

    /**
     * @param LifecycleEventArgs $args
     * @param $elements
     */
    public function preRemove(LifecycleEventArgs $args, $elements)
    {
        $entity = $args->getEntity();

        $shortClass = explode("\\", get_class($entity));
        $shortClass = end($shortClass);

        if (isset($elements[$shortClass])) {
            $this->handlePreRemoveFiles($elements[$shortClass], $entity);
        }

        return;
    }

    /**
     * @param array $arr
     * @param $entity
     */
    private function handlePreRemoveFiles(array $arr, $entity)
    {
        foreach ($arr as $field) {
            $getter = 'get' . ucfirst($field);
            $data = @json_decode($entity->$getter(), true);
            $dir = null;

            if (is_array($data)) {
                foreach ($data as $item) {
                    $file = $this->rootDir . '/' . $this->webDir . $item;
                    if (!is_dir($file) && file_exists($file)) {
                        @unlink($file);
                        $dir = dirname($file);
                    }
                }
            }

            if (!is_null($dir) and is_dir($dir)) {
                rmdir($dir);
            }
        }
    }

    /**
     * @param LifecycleEventArgs $args
     * @param $elements
     */
    public function preUpdate(LifecycleEventArgs $args, $elements)
    {
        $entity = $args->getEntity();
        $shortClass = explode("\\", get_class($entity));
        $shortClass = end($shortClass);

        if (isset($elements[$shortClass])) {
            $this->handlePreUpdateFiles($elements[$shortClass], $args);
        }

        return;
    }

    /**
     * @param $arr
     * @param $args
     */
    private function handlePreUpdateFiles($arr, $args)
    {
        if (count($arr) < 1) {
            return;
        }

        foreach ($arr as $field) {
            if ($args->hasChangedField($field)) {
                $old = $args->getOldValue($field);
                if (!is_null($old)) {
                    $this->removePreUpdateFiles($old);
                }
            }
        }
    }

    /**
     * @param $str
     */
    private function removePreUpdateFiles(string $str)
    {
        $arr = json_decode($str, true);
        $dir = null;
        if (($arr) && isset($arr["default_file"]) && !empty($arr["default_file"])) {
            foreach ($arr as $value) {
                if(is_array($value)) {
                    foreach($value as $valueItem) {
                        $file = $this->rootDir . '/' . $this->webDir . $valueItem;
                        if (!is_dir($file) && file_exists($file)) {
                            $dir = dirname($file);
                            @unlink($file);
                        }
                    }
                } else {
                    $file = $this->rootDir . '/' . $this->webDir . $value;
                    if (!is_dir($file) && file_exists($file)) {
                        $dir = dirname($file);
                        @unlink($file);
                    }
                }
            }

            if (!is_null($dir) and is_dir($dir)) {
                rmdir($dir);
            }
        }
    }
}