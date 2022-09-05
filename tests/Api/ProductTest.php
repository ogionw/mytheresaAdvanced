<?php

namespace App\Tests\Api;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Infrastructure\DataFixtures\ProductFixtures;
use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class ProductTest extends ApiTestCase
{
    public function setUp(): void
    {
        self::bootKernel();
        $this->entityManager = self::$kernel->getContainer()->get('doctrine')->getManager();
        parent::setUp();
    }

    public function testStatus(): void
    {
        static::createClient()->request('GET', '/');
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);
        $this->assertJsonContains(['message' => 'success']);
    }

    public function testHappyPath(): void
    {
        $this->setFixtures(new ProductFixtures());
        $response = $this->getResponse('PUT', $this->products());
        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(['message' => 'success']);
        $this->assertResponseStatusCodeSame(200);
    }

    public function testBadJson(): void
    {
        $this->setFixtures(new ProductFixtures());
        $response = static::createClient()->request('PUT', '/products', [
            'body' => 'hello',
            'headers' => ['content-type' => ['application/json']],
        ]);
        $this->assertJsonContains(['exception' => 'Failed to find products in json']);
        $this->assertResponseStatusCodeSame(400);
    }

    public function testIncorrectContentType(): void
    {
        $this->setFixtures(new ProductFixtures());
        $response = static::createClient()->request('PUT', '/products', [
            'json' => $this->products(),
            'headers' => ['content-type' => ['application/xml']],
        ]);
        $this->assertJsonContains(['exception' => 'Incorrect content type: "xml"']);
        $this->assertResponseStatusCodeSame(400);
    }

    public function testNotIntegerPrice(): void
    {
        $this->setFixtures(new ProductFixtures());
        $products = $this->products();
        $products['products'][1]['price'] = 'hello';
        $response = $this->getResponse('PUT', $products);
        $this->assertJsonContains(['exception' => 'Not integer price: "hello"']);
        $this->assertResponseStatusCodeSame(400);
    }

    public function testInvalidPrice(): void
    {
        $this->setFixtures(new ProductFixtures());
        $products = $this->products();
        $products['products'][1]['price'] = 0;
        $response = $this->getResponse('PUT', $products);
        $this->assertJsonContains(['exception' => 'Invalid price: 0 for product with sku: 000002']);
        $this->assertResponseStatusCodeSame(400);
    }

    public function testDuplicateProduct(): void
    {
        $this->setFixtures(new ProductFixtures());
        $products = $this->products();
        $products['products'][1]['sku'] = '000001';
        $response = $this->getResponse('PUT', $products);
        $this->assertJsonContains(['exception' => 'Duplicate sku: 000001']);
        $this->assertResponseStatusCodeSame(400);
    }

    public function testAdded(): void
    {
        $this->setFixtures(new ProductFixtures());
        $single = ["products"=>[
            [
                "sku"=>"000001",
                "name"=>"BV Lean leather ankle boots",
                "category"=>"boots",
                "price"=>89000
            ]
        ]];
        $this->getResponse('PUT', $single);
        $response = $this->getResponse('GET', []);
        $added = json_decode($response->getContent(),true);
        $added[0]['price'] = $added[0]['price']['original'];
        $this->assertEqualsCanonicalizing($single, ['products'=>$added]);
    }

    public function testCategoryFilter(): void
    {
        $this->setFixtures(new ProductFixtures());
        $response = $this->getResponse('GET', ['category'=>'boots']);
        $arr = json_decode($response->getContent(), true);
        $expectedSkus = ['000001','000002','000003'];
        $unexpectedSkus = ['000004','000005'];
        foreach ($arr as $product){
            $this->assertContains($product['sku'], $expectedSkus);
            $this->assertNotContains($product['sku'], $unexpectedSkus);
            if (($key = array_search($product['sku'], $expectedSkus)) !== false) {
                unset($expectedSkus[$key]);
            }
        }
    }

    public function testPriceFilter(): void
    {
        $this->setFixtures(new ProductFixtures());
        $response = $this->getResponse('GET', ['priceLessThan'=>'75000']);
        $arr = json_decode($response->getContent(), true);
        $expectedSkus = ['000005','000003'];
        $unexpectedSkus = ['000001','000004','000002'];
        foreach ($arr as $product){
            $this->assertContains($product['sku'], $expectedSkus);
            $this->assertNotContains($product['sku'], $unexpectedSkus);
            if (($key = array_search($product['sku'], $expectedSkus)) !== false) {
                unset($expectedSkus[$key]);
            }
        }
    }

    public function testBothFilters(): void
    {
        $this->setFixtures(new ProductFixtures());
        $response = $this->getResponse('GET', ['category'=>'boots', 'priceLessThan'=>'75000']);
        $arr = json_decode($response->getContent(), true);
        $this->assertEquals(array_values($arr)[0]['sku'], '000003');
        $this->assertCount(1, $arr);
    }

    public function testReturnsNoMoreThanFive(): void
    {
        $this->setFixtures(new ProductFixtures());
        $products = $this->products();
        $products['products'][] = [
            "sku"=>"000006",
            "name"=>"Fool Plate Boots",
            "category"=>"boots",
            "price"=>99000
        ];
        $this->getResponse('PUT', $products);
        $response = $this->getResponse('GET', []);
        $arr = json_decode($response->getContent(), true);
        $this->assertCount(5, $arr);
    }

    public function testCurrency(): void
    {
        $this->setFixtures(new ProductFixtures());
        $response = $this->getResponse('GET', []);
        $arr = json_decode($response->getContent(), true);
        foreach ($arr as $product){
            $this->assertEquals('EUR', $product['price']['currency']);
        }
    }

    public function testDiscountsApplied():void
    {
        $this->getResponse('PUT', $this->products());
        $response = $this->getResponse('GET', []);
        $arr = json_decode($response->getContent(), true);
        $this->assertEquals('30%', $arr[0]['price']['discount\_percentage'] );
        $this->assertEquals('30%', $arr[1]['price']['discount\_percentage'] );
    }

    public function testBiggerDiscountApplied():void
    {
        $this->getResponse('PUT', $this->products());
        $response = $this->getResponse('GET', []);
        $arr = json_decode($response->getContent(), true);
        $this->assertEquals('30%', $arr[2]['price']['discount\_percentage'] );
    }

    public function testDiscountsNotAppliedForSandalsAndSneakers():void
    {
        $this->getResponse('PUT', $this->products());
        $response = $this->getResponse('GET', []);
        $arr = json_decode($response->getContent(), true);
        $this->assertNull($arr[3]['price']['discount\_percentage'] );
        $this->assertEquals($arr[3]['price']['original'], $arr[3]['price']['final']);
        $this->assertNull($arr[4]['price']['discount\_percentage'] );
        $this->assertEquals($arr[4]['price']['original'], $arr[4]['price']['final']);
    }

    public function testSkuDiscountApplied():void
    {
        $products = [];
        foreach($this->products()['products'] as $i=>$product){
            $product['category'] = 'greaves';
            $products['products'][$i] = $product;
        }
        $this->getResponse('PUT', $products);
        $response = $this->getResponse('GET', []);
        $arr = json_decode($response->getContent(), true);
        $this->assertEquals('000003', $arr[2]['sku']);
        $this->assertEquals('15%', $arr[2]['price']['discount\_percentage'] );
        $this->assertEquals('60350', $arr[2]['price']['final'] );
    }

    /**
     * @throws TransportExceptionInterface
     */
    private function getResponse(string $method, array $json): ResponseInterface|Response
    {
        $url = '/products';
        if($method === 'GET' && count($json)){
            $chunks = [];
            foreach ($json as $key=>$val){
                $chunks[] = $key.'='.$val;
            }
            $url .= '?'.join('&',$chunks);
        }
        return static::createClient()->request($method, $url, [
            'json' => $json,
            'headers' => ['content-type' => ['application/json']],
        ]);
    }

    private function setFixtures(ORMFixtureInterface $fixture)
    {
        (new ORMExecutor($this->entityManager, new ORMPurger($this->entityManager)))->execute([$fixture]);
    }

    private function products(){
        return [
            "products"=>[
              ["sku"=>"000001", "name"=>"BV Lean leather ankle boots", "category"=>"boots", "price"=>89000],
              ["sku"=>"000002","name"=>"BV Lean leather ankle boots", "category"=>"boots", "price"=>99000],
              ["sku"=>"000003", "name"=>"Ashlington leather ankle boots", "category"=>"boots", "price"=>71000],
              ["sku"=>"000004", "name"=>"Naima embellished suede sandals", "category"=>"sandals", "price"=>79500],
              ["sku"=>"000005", "name"=>"Nathane leather sneakers", "category"=>"sneakers", "price"=>59000]
            ]
        ];
    }
}
