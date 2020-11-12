<?php

namespace ComponentBundle\Controller\Frontend;

use StaticBundle\Entity\StaticContent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @author Design studio origami <https://origami.ua>
 */
abstract class DefaultController extends AbstractController
{
    public function getStaticContentAction(EntityManagerInterface $em, string $page)
    {
        $static = $em->getRepository(StaticContent::class)->getByPage($page);

        $result = [];
        foreach ($static as $item) {
            $linkName = $item->getLinkName();
            $translate = $item->translate();
            $result[$linkName]['description'] = $translate->getDescription();
            $result[$linkName]['short_description'] = $translate->getShortDescription();
            $result[$linkName]['poster'] = $item->getImg();
        }

        return new JsonResponse($result);
    }

//    public function initHeaderAction(Request $request)
//    {
//        return $this->render('default/_header.html.twig', [
//            'request' => $request
//        ]);
//    }
//
//    public function initFooterAction(Request $request)
//    {
//        return $this->render('default/_footer.html.twig', [
//            'request' => $request,
//        ]);
//    }
}