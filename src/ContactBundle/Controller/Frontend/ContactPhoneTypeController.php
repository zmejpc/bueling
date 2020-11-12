<?php

namespace ContactBundle\Controller\Frontend;

use Doctrine\ORM\EntityManagerInterface;
use ContactBundle\Entity\ContactPhoneType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @author Ihor Drevetskyi <ihor.drevetskyi@gmail.com>
 */
class ContactPhoneTypeController extends AbstractController
{
    public function getContactPhoneTypeAction(EntityManagerInterface $em)
    {
        $contactPhoneType = $em->getRepository(ContactPhoneType::class)->getContactPhoneType();
        $result = [];
        foreach ($contactPhoneType as $item) {
            $result[$item->getId()]['title'] = $item->translate()->getTitle();
            if ($item->hasContactPhones()) {
                foreach ($item->getContactPhones() as $phone) {
                    $result[$item->getId()]['phones'][] = $phone->getPhone();
                }
            }
        }

        return new JsonResponse($result);
    }
}
