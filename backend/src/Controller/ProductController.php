<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/products')]
class ProductController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private ProductRepository $productRepository;
    private SerializerInterface $serializer;
    private ValidatorInterface $validator;

    public function __construct(
        EntityManagerInterface $entityManager,
        ProductRepository $productRepository,
        SerializerInterface $serializer,
        ValidatorInterface $validator
    ) {
        $this->entityManager = $entityManager;
        $this->productRepository = $productRepository;
        $this->serializer = $serializer;
        $this->validator = $validator;
    }

    #[Route('', name: 'product_list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $products = $this->productRepository->findAll();
        
        return $this->json([
            'products' => $products,
        ], Response::HTTP_OK, [], ['groups' => 'product:read']);
    }

    #[Route('/search', name: 'product_search', methods: ['GET'])]
    public function search(Request $request): JsonResponse
    {
        $query = $request->query->get('q');
        
        if (!$query) {
            return $this->json([
                'error' => 'Search query parameter "q" is required',
            ], Response::HTTP_BAD_REQUEST);
        }
        
        $products = $this->productRepository->findByNameOrId($query);
        
        return $this->json([
            'products' => $products,
        ], Response::HTTP_OK, [], ['groups' => 'product:read']);
    }

    #[Route('', name: 'product_add', methods: ['POST'])]
    public function add(Request $request): JsonResponse
    {
        // Suppression de la vérification d'authentification
        // $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'You need to be authenticated as admin to add products');

        $data = json_decode($request->getContent(), true);

        if (!$data) {
            return $this->json([
                'error' => 'Invalid JSON data',
            ], Response::HTTP_BAD_REQUEST);
        }

        $product = new Product();
        $product->setName($data['name'] ?? '');
        $product->setCategory($data['category'] ?? '');
        $product->setPrice($data['price'] ?? 0);

        $errors = $this->validator->validate($product);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[$error->getPropertyPath()] = $error->getMessage();
            }

            return $this->json([
                'error' => 'Validation failed',
                'violations' => $errorMessages,
            ], Response::HTTP_BAD_REQUEST);
        }

        $this->entityManager->persist($product);
        $this->entityManager->flush();

        return $this->json([
            'message' => 'Product created successfully',
            'product' => $product,
        ], Response::HTTP_CREATED, [], ['groups' => 'product:read']);
    }

}