<?php

namespace ComponentBundle\Storage;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * @author Design studio origami <https://origami.ua>
 */
final class SessionStorage implements StorageInterface
{
    /** @var SessionInterface */
    private $session;

    /**
     * SessionStorage constructor.
     * @param SessionInterface $session
     */
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function has(string $name): bool
    {
        return $this->session->has($name);
    }

    /**
     * @param string $name
     * @param null $default
     * @return mixed
     */
    public function get(string $name, $default = null)
    {
        return $this->session->get($name, $default);
    }

    /**
     * @param string $name
     * @param $value
     */
    public function set(string $name, $value): void
    {
        $this->session->set($name, $value);
    }

    /**
     * @param string $name
     */
    public function remove(string $name): void
    {
        $this->session->remove($name);
    }

    /**
     * @return array
     */
    public function all(): array
    {
        return $this->session->all();
    }
}
