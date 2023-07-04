<?php

namespace Application\Query;

use Application\Data\ProductData;
use Application\Entities\Product;
use Application\Entities\User;
use Application\Interfaces\ProductRepository;
use Application\Interfaces\RatingRepository;
use Application\Interfaces\UserRepository;
use Application\UserData;

class ProductQuery {

    public function __construct(
        private  ProductRepository $productRepository,
        private RatingRepository $ratingRepository,
        private  UserRepository   $userRepository
    )
    {}

    public function searchProductByFilter(?string $filter) : array
    {
        $results = [];
        $products = null;
        if( trim($filter) === null || trim($filter) == ""){
            $products = $this->productRepository->getProducts();

        }  else{
            $products = $this->productRepository->getProductForFilter($filter);
        }


        foreach($products as $product) {


            $user = $this->userRepository->getUser($product->getUserId());

            $results[] = new ProductData(
                new Product(
                        $product->getId(),
                        $product->getName(),
                        $product->getPrice(),
                        $product->getProducer(),
                        $product->getUserId()
                    ),
                new User(
                    $product->getUserId(),
                    $user->getUserName()
                ),
                $this->ratingRepository->getAverageRatingForProduct($product->getId()),
                $this->ratingRepository->getTotalAmountOfRatingsForProduct($product->getId())
            );
        }

        return $results;
    }
    public function getProduct(int $id): ?ProductData {
        $product = $this->productRepository->getProduct($id);

        if($product === null) {
            return null;
        }


        $user = $this->userRepository->getUser($product->getUserId());

        return new ProductData(
            new Product(
                $product->getId(),
                $product->getName(),
                $product->getPrice(),
                $product->getProducer(),
                $product->getUserId()
            ),
            new User(
                $product->getUserId(),
                $user->getUserName()
            ),
            $this->ratingRepository->getAverageRatingForProduct($product->getId()),
            $this->ratingRepository->getTotalAmountOfRatingsForProduct($product->getId()),
            $this->ratingRepository->getRatingsFromProduct($product->getId())
        );
    }
}