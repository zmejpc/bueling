<?php

namespace BackendBundle\EventListener;

use BackendBundle\Controller\Dashboard\DashboardController;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Security;

/**
 * @author Design studio origami <https://origami.ua>
 */
final class RefererListener
{
    /**
     * @var ControllerResolverInterface
     */
    private $resolver;

    /**
     * @var Security
     */
    protected $security;

    /**
     * RefererListener constructor.
     * @param ControllerResolverInterface $resolver
     * @param Security $security
     */
    public function __construct(ControllerResolverInterface $resolver, Security $security)
    {
        $this->resolver = $resolver;
        $this->security = $security;
    }

    /**
     * @param FilterControllerEvent $event
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        $controller = $event->getController();

        /*
         * $controller passed can be either a class or a Closure.
         * This is not usual in Symfony but it may happen.
         * If it is a class, it comes in array format
         */
        if (!is_array($controller)) {
            return;
        }

        if ($controller[0] instanceof DashboardController) {
            if ($this->security->isGranted('ROLE_MANUFACTURER')) {
                $request = $event->getRequest();
                $request->attributes->set('_controller', ManufacturerDashboardController::class . '::' . $controller[1]);
                $controller = $this->resolver->getController($request);
                $event->setController($controller);
            }
        }
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::CONTROLLER => 'onKernelController',
        );
    }
}
