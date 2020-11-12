<?php

namespace BackendBundle\Controller\Dashboard;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use ContactBundle\Entity\Contact;
use ContactBundle\Entity\Callback;
use BackendBundle\Event\AjaxCheckboxEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * @author Design studio origami <https://origami.ua>
 */
final class DashboardController extends \DashboardBundle\Controller\DashboardController
{
	protected $em = null;

    protected $eventDispatcher;

    public function __construct(EntityManagerInterface $em, EventDispatcherInterface $eventDispatcher)
    {
        $this->em = $em;
        $this->eventDispatcher = $eventDispatcher;
    }

	public function indexAction(): Response
    {
        return $this->render('@Backend/dashboard/homepage/index.html.twig', [
            'countNewContactsRequests' => $this->em->getRepository(Contact::class)->countNewContactRequests(),
            'templateNumber' => 1
        ]);
    }

    public function ajaxCheckboxAction(Request $request)
    {
        $entity = $this->em->getRepository($request->request->get('entity'))->find((int)$request->request->get('id'));
        
        if($entity) {
            $method = "set" . ucfirst($request->request->get('field'));
            $method_argument = $request->request->get('checked') == 'true';
            $entity->$method($method_argument);

            $this->em->persist($entity);
            $this->em->flush();

            $event = new AjaxCheckboxEvent();
            $event->setEntity($entity);
            $event->setMethod($method);
            $event->setMethodArgument($method_argument);
            
            $this->eventDispatcher->dispatch('dashboard.list.ajax.checkbox', $event);
        }

        exit();
    }

    public function ajaxSortPositionAction(Request $request)
    {
        $entity = $this->em->getRepository($request->request->get('entity'))->find((int)$request->request->get('id'));
        
        if($entity) {
            // $entityOnPosition = $this->em->getRepository($request->request->get('entity'))->findOneBy(['position' => (int)$request->request->get('position')]);

            // if($entityOnPosition) {
            //     $entityOnPosition->setPosition($entity->getPosition());

            //     $this->em->persist($entityOnPosition);
            //     $this->em->flush();
            // }

            $entity->setPosition((int)$request->request->get('position'));

            $this->em->persist($entity);
            $this->em->flush();
        }

        exit();
    }
}