<?php

namespace ComponentBundle\Storage;

/**
 * @author Design studio origami <https://origami.ua>
 */
interface StorageInterface
{
    /**
     * @param string $name
     * @return bool
     */
    public function has(string $name): bool;

    /**
     * @param string $name
     * @param null $default
     * @return mixed
     */
    public function get(string $name, $default = null);

    /**
     * @param string $name
     * @param $value
     */
    public function set(string $name, $value): void;

    /**
     * @param string $name
     */
    public function remove(string $name): void;

    /**
     * @return array
     */
    public function all(): array;
}
