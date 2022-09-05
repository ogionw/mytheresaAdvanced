<?php

namespace App\Tests\Unit;

use App\Domain\Exception\InvalidPriceException;
use App\Infrastructure\Entity\Product;
use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{
    public function testCreateSuccessful(): void
    {
        $product = Product::create('000001', 'BV Lean leather ankle boots', 'boots', 89000);
        $this->assertSame('000001', $product->getSku());
    }

    public function testCreateFailure(): void
    {
        $this->expectException(InvalidPriceException::class);
        $product = Product::create('000001', 'BV Lean leather ankle boots', 'boots', -1);
    }
}
