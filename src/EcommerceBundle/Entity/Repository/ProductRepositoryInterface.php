<?php

namespace Ecommerce\Entity\Repository;

use Ecommerce\Entity\ProductCollection;

/**
 * @author Design studio origami <https://origami.ua>
 */
interface ProductRepositoryInterface
{
    public function findById(int $id);

    public function getProducts();
}
