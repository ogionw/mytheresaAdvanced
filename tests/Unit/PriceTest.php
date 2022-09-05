<?php

namespace App\Tests\Unit;

use App\Domain\Exception\InvalidPriceException;
use App\Infrastructure\Entity\Discount;
use App\Infrastructure\Entity\Price;
use App\Infrastructure\Entity\Product;
use PHPUnit\Framework\TestCase;

class PriceTest extends TestCase
{
    public function testFinalSameAsOriginalOnCreate(): void
    {
        $price = (new Price())->setOriginal(89000);
        $this->assertSame(89000, $price->getFinal());
    }

    public function testCurrencyEur(): void
    {
        $price = (new Price())->setOriginal(89000);
        $this->assertSame('EUR', $price->getCurrency());
    }

    public function testApplyDefault(): void
    {
        $price = (new Price())->setOriginal(89000);
        $discount = (new Discount())->setCategory('boots')->setValue(30);
        $price->apply($discount);
        $this->assertSame(62300, $price->getFinal());
    }

    public function testApplyZeroDiscount(): void
    {
        $price = (new Price())->setOriginal(89000);
        $discount = (new Discount())->setCategory('boots')->setValue(0);
        $price->apply($discount);
        $this->assertSame(89000, $price->getFinal());
    }

    public function testApplyDecimalDiscount(): void
    {
        $price = (new Price())->setOriginal(89000);
        $discount = (new Discount())->setCategory('boots')->setValue(33.33);
        $price->apply($discount);
        $this->assertSame(59630, $price->getFinal());
    }

    public function testApplyNegative(): void
    {
        $price = (new Price())->setOriginal(89000);
        $discount = (new Discount())->setCategory('boots')->setValue(-1);
        $price->apply($discount);
        $this->assertSame(89890, $price->getFinal());
    }

    public function testApplyMoreThanHundredPercent(): void
    {
        $price = (new Price())->setOriginal(89000);
        $discount = (new Discount())->setCategory('boots')->setValue(120);
        $price->apply($discount);
        $this->assertSame(-17800, $price->getFinal());
    }
}
