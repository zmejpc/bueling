<?php

namespace UploadBundle\Controller;

use UploadBundle\Services\FileHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @author Design studio origami <https://origami.ua>
 */
final class DefaultController extends AbstractController
{
    /**
     * @var null
     */
    private $uploadFile = null;

    /**
     * @param $settings
     * @return bool|File|Image|null
     * @throws \Exception
     */
    private function getFileValidator($settings)
    {
        $file = (is_array($this->uploadFile)) ? array_values($this->uploadFile)[0] : $this->uploadFile;

        $fileConstraint = null;

        $formats = explode(",", $settings['format']);
        $match = false;

        foreach ($formats as $format) {
            if (str_replace(' ', '', strtolower($format)) == strtolower($file->getClientOriginalExtension()))
                $match = true;
        }

        $imageParameters = getimagesize($file);

        if (isset($imageParameters['mime']) == 'image/jpeg' && !empty($imageParameters['channels']) && $imageParameters['channels'] == 4) {
            return false;
        }

        if (!$match) {
            return false;
        }

        if ($settings['type'] == 'file' || $settings['type'] == 'video') {
            $fileConstraint = new File();
            $fileConstraint->mimeTypes = $settings['mime_type'];
        } elseif ($settings['type'] == 'image') {
            $fileConstraint = new Image();
        }

        if (is_null($fileConstraint)) {
            throw new \Exception('Not found file type in configuration!');
        }

        return $fileConstraint;
    }

    /**
     * @param Request $request
     * @param ValidatorInterface $validatorInterface
     * @param KernelInterface $kernel
     * @param string $fileSetting
     * @param string $field
     * @param SessionInterface $session
     * @param string $secureToken
     * @return JsonResponse
     * @throws \Exception
     */
    public function uploadAction(
        Request $request, ValidatorInterface $validatorInterface, KernelInterface $kernel,
        string $fileSetting, string $field, SessionInterface $session, string $secureToken
    )
    {
        $this->uploadFile = $request->files->get('file');

        if (is_null($this->uploadFile)) {
            throw new \InvalidArgumentException('File not found!');
        }

        $filesConfig = $this->getParameter('upload.types');

        if (empty($filesConfig[$fileSetting])) {
            throw new \InvalidArgumentException('fileSetting wrong!');
        }

        $data = ['success' => false];

        if ($this->uploadFile->isValid() && $secureToken == $session->get('secure_token')) {
            $fileSettings = $filesConfig[$fileSetting];
            $siteWebDir = $this->getParameter('upload.web_dir');
            $tempUploadDir = $this->getParameter('upload.temp_upload_dir');
            $validator = $this->getFileValidator($fileSettings);

            if (!$validator) {
                $data = [
                    'success' => false,
                    'error' => 'Method getFileValidator wrong!'
                ];

                return new JsonResponse($data);
            }

            $errorList = $validatorInterface->validate($this->uploadFile, $validator);

            if (count($errorList) == 0) {
                $uploadDir = $kernel->getProjectDir() . '/' . $siteWebDir . '/' . $tempUploadDir . '/' .
                    date("Y") . '/' . date("m") . '/' . date("d");

                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $uniq = uniqid();
                $fileName = $uniq . '.' . strtolower($this->uploadFile->getClientOriginalExtension());

                $this->uploadFile->move($uploadDir, $fileName);
                $arr = [];
                $arr['file_type'] = $fileSetting;
                $arr['field'] = $field;
                $arr['path'] = '/' . $tempUploadDir . '/' .
                    date("Y") . '/' . date("m") . '/' . date("d") . '/' . $fileName;

                $data = [
                    'success' => true,
                    'uploaded' => true,
                    'fileName' => $fileName,
                    'file' => json_encode($arr),
                    'path' => $arr['path']
                ];
            } else {
                $data = [
                    'success' => false,
                    'error' => $errorList[0]->getMessage()
                ];
            }
        }

        return new JsonResponse($data);
    }

    /**
     * @param Request $request
     * @param FileHandler $fileHandler
     * @return JsonResponse
     */
    public function removeAction(Request $request, FileHandler $fileHandler)
    {
        $path = $request->get('path');
        $isNew = $request->get('isNew');

        if ($isNew == true) {
            $fileHandler->unlinkFile($path);
        }

        return new JsonResponse(['status' => true]);
    }
}