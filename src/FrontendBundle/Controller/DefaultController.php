<?php

namespace FrontendBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use ContactBundle\Entity\ContactPhone;
use StaticBundle\Entity\StaticContent;
use Ecommerce\Entity\ProductCategory;
use Ecommerce\Entity\ActivityArea;

/**
 * @author Design studio origami <https://origami.ua>
 */
final class DefaultController extends \ComponentBundle\Controller\Frontend\DefaultController
{
    public const FAIL_MESSAGE_TITLE = 'Упс... Что-то пошло не так';
    public const FAIL_MESSAGE_BODY = 'При отправке сообщения произошла ошибка. Попробуйте ещё раз.';

    public function initHeaderAction(EntityManagerInterface $em, Request $request, bool $is_transparent = false)
    {
        $categories = $em->getRepository(ProductCategory::class)->getForFrontendMenu();
        $areas = $em->getRepository(ActivityArea::class)->getForFrontendMenu();

        $contactPhones = $em->getRepository(ContactPhone::class)->getForFrontendAction(1);

        return $this->render('default/_header.html.twig', [
            'is_transparent' => $is_transparent,
            'contactPhones' => $contactPhones,
            'categories' => $categories,
            'areas' => $areas,
        ]);
    }

    public function initFooterAction(EntityManagerInterface $em)
    {

        $contactPhones = $em->getRepository(ContactPhone::class)->getForFrontendAction(1);
        $static = $em->getRepository(StaticContent::class)->getByPage('contact');

        $staticResult = [];
        foreach ($static as $item) {
            $staticResult[$item->getLinkName()]['text'] = $item->translate()->getDescription();
        }

        return $this->render('default/_footer.html.twig', [
            'contactPhones' => $contactPhones,
            'static' => $staticResult,
        ]);
    }

    public function ajaxDialogAction(bool $status, array $message, $data = [])
    {
        if (!$status && !$message)
            $message = [
                'title' => self::FAIL_MESSAGE_TITLE,
                'body' => self::FAIL_MESSAGE_BODY,
            ];

        $response = [
            'status' => $status,
            'dialog' => $this->renderView('default/dialog.html.twig', $message)
        ];

        foreach ($data as $key => $item)
            $response[$key] = $item;

        return new JsonResponse($response);
    }
}