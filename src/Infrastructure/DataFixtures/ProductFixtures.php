<?php

namespace App\Infrastructure\DataFixtures;

use App\Infrastructure\Entity\Discount;
use App\Infrastructure\Entity\Price;
use App\Infrastructure\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $discount1 = (new Discount())->setCategory('boots')->setValue(30);
        $manager->persist($discount1);
        $discount2 = (new Discount())->setSku('000003')->setValue(15);
        $manager->persist($discount2);

        $product = (new Product())
            ->setSku('000001')
            ->setName('BV Lean leather ankle boots')
            ->setCategory('boots')
            ->setPrice((new Price())->setOriginal(89000));
        $manager->persist($product);
        $product = (new Product())
            ->setSku('000002')
            ->setName('BV Lean leather ankle boots')
            ->setCategory('boots')
            ->setPrice((new Price())->setOriginal(99000));
        $manager->persist($product);
        $product = (new Product())
            ->setSku('000003')
            ->setName('Ashlington leather ankle boots')
            ->setCategory('boots')
            ->setPrice((new Price())->setOriginal(71000));
        $manager->persist($product);
        $product = (new Product())
            ->setSku('000004')
            ->setName('Naima embellished suede sandals')
            ->setCategory('sandals')
            ->setPrice((new Price())->setOriginal(79500));
        $manager->persist($product);
        $product = (new Product())
            ->setSku('000005')
            ->setName('Nathane leather sneakers')
            ->setCategory('sneakers')
            ->setPrice((new Price())->setOriginal(59000));
        $manager->persist($product);

        $manager->flush();
    }
}
