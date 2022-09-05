<?php

namespace App\Presentation\Controller;

use App\Application\Cqrs\CommandBusInterface;
use App\Application\Cqrs\QueryBusInterface;
use App\Infrastructure\Repository\DiscountRepository;
use App\Presentation\Dto\DiscountDto;
use App\Presentation\Message\AddDiscountCommand;
use App\Presentation\Message\GetDiscountsQuery;
use App\Presentation\Message\RemoveDiscountCommand;
use App\Presentation\Message\ReplaceDiscountsCommand;
use App\Presentation\Response\HttpResponseGeneratorFactory;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class DiscountController extends AbstractController
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

    #[Route('/discounts', name: 'get-discounts', methods: 'GET')]
    public function getDiscounts(Request $request): JsonResponse
    {
        $query = $this->serializer->deserialize(
            json_encode(['category'=>$request->get('category'), 'priceLessThan'=>$request->get('priceLessThan')]),
            GetDiscountsQuery::class, JsonEncoder::FORMAT
        );
        $response = $this->queryBus->handle($query);
        return $this->json($response);
    }

    #[Route('/discounts', name: 'add-discount', methods: 'POST')]
    public function addDiscount(Request $request): JsonResponse
    {
        $command = $this->serializer->deserialize($request->getContent(), AddDiscountCommand::class, JsonEncoder::FORMAT);
        $this->commandBus->dispatch($command);
        return $this->json(['message'=>'not implemented']);
    }


    #[Route('/discounts', name: 'remove-discount', methods: 'DELETE')]
    public function removeDiscount(Request $request): JsonResponse
    {
        $command = $this->serializer->deserialize($request->getContent(), RemoveDiscountCommand::class, JsonEncoder::FORMAT);
        $this->commandBus->dispatch($command);
        return $this->json(['message'=>'not implemented']);
    }

    #[Route('/discounts', name: 'replace-discounts', methods: 'PUT')]
    public function replaceDiscounts(Request $request): JsonResponse
    {
        $dtoArr = $this->serializer->deserialize(
            json_encode(json_decode($request->getContent(), true)['discounts']),
            DiscountDto::class.'[]', JsonEncoder::FORMAT
        );
        $this->commandBus->dispatch(new ReplaceDiscountsCommand(new ArrayCollection($dtoArr)));
        return $this->json(['message'=>'not implemented']);
    }
}
