<?php

namespace UserBundle\Event;

use UserBundle\Entity\UserInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Design studio origami <https://origami.ua>
 */
final class FilterUserResponseEvent extends UserEvent
{
    /**
     * @var Response
     */
    private $response;

    /**
     * FilterUserResponseEvent constructor.
     *
     * @param UserInterface $user
     * @param Request       $request
     * @param Response      $response
     */
    public function __construct(UserInterface $user, Request $request, Response $response)
    {
        parent::__construct($user, $request);
        $this->response = $response;
    }

    /**
     * @return Response
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Sets a new response object.
     *
     * @param Response $response
     */
    public function setResponse(Response $response)
    {
        $this->response = $response;
    }
}
