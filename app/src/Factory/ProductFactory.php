<?php 
declare(strict_types=1);

namespace App\Factory;

use App\Entity\Product;
use App\Entity\Category;

class ProductFactory
{
    public function createProduct(string $name, int $index, ?Category $category = null): Product
    {
        $product = new Product();
        $product->setName($name);
        $product->setProductIndex($index);
        $product->setCategory($category);

        return $product;
    }
}
