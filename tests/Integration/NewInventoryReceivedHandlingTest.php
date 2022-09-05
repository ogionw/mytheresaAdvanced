<?php

namespace App\Tests\Integration;

use App\Application\Cqrs\EventBusInterface;
use App\Domain\Event\NewInventoryReceivedEvent;
use App\Infrastructure\DataFixtures\ProductFixtures;
use App\Infrastructure\Entity\Product;
use App\Presentation\Dto\ProductDto;
use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManager;
use Exception;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class NewInventoryReceivedHandlingTest extends KernelTestCase
{
    protected EntityManager $entityManager;
    protected ?EventBusInterface $eventBus;

    /**
     * @throws Exception
     */
    public function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        $this->eventBus = $container->get(EventBusInterface::class);
        $this->entityManager = self::$kernel->getContainer()->get('doctrine')->getManager();
        parent::setUp();
    }

    public function testMinimalSelect(): void
    {
        $this->setFixtures(new ProductFixtures());
        $this->eventBus->dispatch(new NewInventoryReceivedEvent($this->products()));
        $products = $this->entityManager->getRepository(Product::class)->findAll();
        foreach ($products as $product){
            if(in_array($product->getSku(), ['000001','000002','000003'])){
                $this->assertSame(30, $product->getPrice()->getDiscount()->getValue());
            } else if(in_array($product->getSku(), ['000004','000005'])){
                $this->assertNull($product->getPrice()->getDiscount());
            }
        }
    }

    private function setFixtures(ORMFixtureInterface $fixture)
    {
        (new ORMExecutor($this->entityManager, new ORMPurger($this->entityManager)))->execute([$fixture]);
    }

    private function products()
    {
        $products = new ArrayCollection();
        $products->add(new ProductDto("000001", "BV Lean leather ankle boots", "boots", 89000));
        $products->add(new ProductDto("000002", "BV Lean leather ankle boots", "boots", 99000));
        $products->add(new ProductDto("000003", "Ashlington leather ankle bootss", "boots", 71000));
        $products->add(new ProductDto("000004", "Naima embellished suede sandals", "sandals", 79500));
        $products->add(new ProductDto("000005", "Nathane leather sneakers", "sneakers", 59000));
        return $products;
    }
}
