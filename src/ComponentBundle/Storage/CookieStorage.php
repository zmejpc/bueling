<?php

namespace ComponentBundle\Storage;

use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class CookieStorage
 * @package ComponentBundle\Storage
 */
final class CookieStorage implements StorageInterface, EventSubscriberInterface
{
    /** @var ParameterBag */
    private $requestCookies;

    /** @var ParameterBag */
    private $responseCookies;

    /**
     * CookieStorage constructor.
     */
    public function __construct()
    {
        $this->requestCookies = new ParameterBag();
        $this->responseCookies = new ParameterBag();
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => [['onKernelRequest', 1024]],
            KernelEvents::RESPONSE => [['onKernelResponse', -1024]],
        ];
    }

    /**
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event): void
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        $this->requestCookies = new ParameterBag($event->getRequest()->cookies->all());
        $this->responseCookies = new ParameterBag();
    }

    /**
     * @param FilterResponseEvent $event
     */
    public function onKernelResponse(FilterResponseEvent $event): void
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        $response = $event->getResponse();
        foreach ($this->responseCookies as $name => $value) {
            $response->headers->setCookie(new Cookie($name, $value));
        }

        $this->requestCookies = new ParameterBag();
        $this->responseCookies = new ParameterBag();
    }

    /**
     * @param string $name
     * @return bool
     */
    public function has(string $name): bool
    {
        return !in_array($this->get($name), ['', null], true);
    }

    /**
     * @param string $name
     * @param null $default
     * @return mixed
     */
    public function get(string $name, $default = null)
    {
        return $this->responseCookies->get($name, $this->requestCookies->get($name, $default));
    }

    /**
     * @param string $name
     * @param $value
     */
    public function set(string $name, $value): void
    {
        $this->responseCookies->set($name, $value);
    }

    /**
     * @param string $name
     */
    public function remove(string $name): void
    {
        $this->set($name, null);
    }

    /**
     * @return array
     */
    public function all(): array
    {
        return array_merge($this->responseCookies->all(), $this->requestCookies->all());
    }
}
