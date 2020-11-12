<?php

namespace ComponentBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

class IdToEntityTransformer implements DataTransformerInterface
{
	private $repository;

    public function __construct($repository) {
        $this->repository = $repository;
    }

    public function transform($entity): ?int
    {
        return is_object($entity) ? $entity->getId() : null;
    }

    public function reverseTransform($id)
    {
        if ('' === $id || null === $id) {
            return '';
        }

        return $this->repository->find($id);
    }
}