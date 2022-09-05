<?php

namespace App\Tests\Integration;

use App\Application\Cqrs\EventBusInterface;
use App\Domain\Event\NewInventoryReceivedEvent;
use App\Domain\Model\Discounter;
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

class DiscounterTest extends KernelTestCase
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
        /** @var Discounter $sut */
        $this->sut = static::getContainer()->get(Discounter::class);
        parent::setUp();
    }

    public function testApplyDiscount(): void
    {
        $this->setFixtures(new ProductFixtures());
        $product = $this->entityManager->getRepository(Product::class)->findOneBy(['sku'=>'000002']);
        $this->assertNull($product->getPrice()->getDiscount());
        $this->sut->applyDiscount($product);
        $this->assertSame(30, $product->getPrice()->getDiscount()->getValue());
    }

    private function setFixtures(ORMFixtureInterface $fixture)
    {
        (new ORMExecutor($this->entityManager, new ORMPurger($this->entityManager)))->execute([$fixture]);
    }
}
