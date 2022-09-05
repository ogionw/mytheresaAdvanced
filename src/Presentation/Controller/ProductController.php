<?php

namespace App\Presentation\Controller;

use App\Application\Cqrs\CommandBusInterface;
use App\Application\Cqrs\QueryBusInterface;
use App\Infrastructure\Repository\ProductRepository;
use App\Presentation\Dto\ProductDto;
use App\Presentation\Exception\IncorrectContentTypeException;
use App\Presentation\Exception\MissingProductJsonException;
use App\Presentation\Message\AddProductCommand;
use App\Presentation\Message\GetProductsQuery;
use App\Presentation\Message\RemoveProductCommand;
use App\Presentation\Message\ReplaceProductsCommand;
use App\Presentation\Response\HttpResponseGeneratorFactory;
use Doctrine\Common\Collections\ArrayCollection;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class ProductController extends AbstractController
{
    private CommandBusInterface $commandBus;
    private QueryBusInterface $queryBus;
    private Serializer $serializer;
    private HttpResponseGeneratorFactory $respGenFactory;

    public function __construct(CommandBusInterface $commandBus, QueryBusInterface $queryBus, HttpResponseGeneratorFactory $factory)
    {
        $this->commandBus = $commandBus;
        $this->queryBus = $queryBus;
        $this->respGenFactory = $factory;
        $this->serializer = new Serializer([new ObjectNormalizer(), new ArrayDenormalizer()], [new JsonEncoder()]);
    }

    #[Route('/', name: 'index', methods: 'GET')]
    public function index(Request $request): JsonResponse
    {
        return $this->json(['message'=>'success']);
    }

    #[Route('/products', name: 'get-products', methods: 'GET')]
    public function getProducts(Request $request): JsonResponse
    {
        $generator = $this->respGenFactory->create('GET');
        try {
            $query = $this->serializer->deserialize(
                json_encode(['category'=>$request->get('category'), 'priceLessThan'=>$request->get('priceLessThan')]),
                GetProductsQuery::class, JsonEncoder::FORMAT
            );
            $response = $this->queryBus->handle($query);
            return $generator->generate($response);
        } catch (Exception $e) {
            return $generator->generate([], $e);
        }
    }

    #[Route('/products', name: 'add-product', methods: 'POST')]
    public function addProduct(Request $request): JsonResponse
    {
        $generator = $this->respGenFactory->create('POST');
        try {
            $command = $this->serializer->deserialize($request->getContent(), AddProductCommand::class, JsonEncoder::FORMAT);
            $this->commandBus->dispatch($command);
            return $generator->generate();
        } catch (Exception $e) {
            return $generator->generate([], $e);
        }
    }


    #[Route('/products', name: 'remove-product', methods: 'DELETE')]
    public function removeProduct(Request $request): JsonResponse
    {
        $generator = $this->respGenFactory->create('DELETE');
        try {
            $this->validateContentType($request->getContentType());
            $command = $this->serializer->deserialize($request->getContent(), RemoveProductCommand::class, JsonEncoder::FORMAT);
            $this->commandBus->dispatch($command);
            return $generator->generate();
        } catch (Exception $e) {
            return $generator->generate([], $e);
        }
    }

    #[Route('/products', name: 'replace-products', methods: 'PUT')]
    public function replaceProducts(Request $request): JsonResponse
    {
        $generator = $this->respGenFactory->create('PUT');
        try {
            $arr = $this->validateProductArr(json_decode($request->getContent(), true));
            $this->validateContentType($request->getContentType());
            $dtoArr = $this->serializer->deserialize(json_encode($arr), ProductDto::class.'[]', JsonEncoder::FORMAT);
            $this->commandBus->dispatch(new ReplaceProductsCommand(new ArrayCollection($dtoArr)));
            return $generator->generate();
        } catch (Exception $e) {
            return $generator->generate([], $e);
        }
    }

    #[Route('/test', name: 'test', methods: 'GET')]
    public function test(ProductRepository $productRepository): JsonResponse
    {
        $result = [];
        foreach ($productRepository->findAll() as $product) {
            $result[] = $product->toArray();
        }
        return $this->json($result);
    }

    /**
     * @throws IncorrectContentTypeException
     */
    private function validateContentType(string $contentType)
    {
        if($contentType !== 'json'){
            throw new IncorrectContentTypeException($contentType);
        }
    }

    /**
     * @throws MissingProductJsonException
     */
    private function validateProductArr(?array $arr): array
    {
        if(! $arr || !isset($arr['products']) || ! $arr['products']){
            throw new MissingProductJsonException();
        }
        return $arr['products'];
    }
}
