<?php

namespace FrontendBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

final class LocaleListener implements EventSubscriberInterface
{
    private $defaultLocale = 'uk';

    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    private $router;
    private $requestStack;
    private $em;

    public function __construct(RouterInterface $router, RequestStack $requestStack, EntityManagerInterface $em)
    {
        $this->router = $router;
        $this->requestStack = $requestStack;
        $this->em = $em;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        $session = $request->getSession();

        if ($session->get('_locale')) {
            $request->setLocale($session->get('_locale'));
        }

        try {
            $routeParams = $this->router->match($request->getPathInfo());
            $routeName = $routeParams['_route'];
        } catch (\Exception $exception) {
            return;
        }

        if (
            empty($routeParams) || 
            $routeName[0] == '_' || 
            $this->requestStack->getCurrentRequest()->isXmlHttpRequest() ||
            $this->requestStack->getCurrentRequest()->getMethod() == 'POST'
        ) {
            return;
        }

        unset($routeParams['_route']);
        $routeData = ['name' => $routeName, 'params' => $routeParams];

        if ($routeName != 'change_locale') {
            if (empty($routeParams['slug']) or !empty($routeParams['slug']) and $routeParams['slug'] != '_fragment') {
                $session->set('last_route', $routeData);
            }
        }

        if (!empty($session->get('_locale'))) {
            $locale = $request->attributes->get('_locale');
        }

        if(empty($locale)) {
            $locale = $this->defaultLocale;
        }

        $session->set('_locale', $locale);
        $request->setLocale($locale);
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => [['onKernelRequest', 15]],
        ];
    }
}
