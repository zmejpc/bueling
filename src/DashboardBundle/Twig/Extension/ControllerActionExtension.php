<?php

namespace DashboardBundle\Twig\Extension;

use Twig\Environment;
use Twig\TwigFunction;
use Twig\Extension\AbstractExtension;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @author Design studio origami <https://origami.ua>
 */
class ControllerActionExtension extends AbstractExtension
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var \Twig_Environment
     */
    protected $environment;

    /**
     * ControllerActionExtension constructor.
     * @param RequestStack $requestStack
     * @param Environment $environment
     */
    public function __construct(RequestStack $requestStack, Environment $environment)
    {
        $this->environment = $environment;
        $this->request = $requestStack->getCurrentRequest();
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('getControllerName', [$this, 'getControllerName']),
            new TwigFunction('getActionName', [$this, 'getActionName'])
        ];
    }

    /**
     * Get current controller name
     */
    public function getControllerName()
    {
        if (null !== $this->request) {
            $pattern = "#Controller\\\([a-zA-Z]*)Controller#";
            $matches = [];
            preg_match($pattern, $this->request->get('_controller'), $matches);

            return strtolower($matches[1]);
        }
    }

    /**
     * Get current action name
     */
    public function getActionName()
    {
        if (null !== $this->request) {
            $pattern = "#Controller\\\([a-zA-Z]*)Controller#";
            $matches = [];
            preg_match($pattern, $this->request->get('_controller'), $matches);

            return strtolower($matches[1]);
        }

        return null;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'controller_twig_extension';
    }
}
