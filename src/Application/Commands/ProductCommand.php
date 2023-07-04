<?php

namespace Application\Commands;

class ProductCommand
{
    const Error_NotAuthenticated = 0x01; // 1
    const Error_InvalidName = 0x02; // 2
    const Error_InvalidProducer = 0x04; // 4

    public function __construct(
        private \Application\Services\AuthenticationService $authenticationService,
        private \Application\Interfaces\ProductRepository $productRepository
    ) {

    }

    public function edit(int $id, string $producer, string $name): ?string
    {

        $name = trim($name);
        $producer = trim($producer);
        $error = null;


        $userId = $this->authenticationService->getUserId();

        if(strlen($name) == 0) {
            $error |= "Invalid name";
        }

        if(strlen($producer) == 0) {
            $error |= "The given Producer is invalid";
        }

        if($userId === null  || $this->productRepository->canEdit($id, $userId) == 0) {
            $error |= "not Authenticated";
        }


        if(!$error) {
            $this->productRepository->edit($id, $producer, $name);
        }

        return $error;
    }
    public function addProduct( string $name, string $manufacturer, int $price) : ?string{

        $name = trim($name);
        $producer = trim($manufacturer);

        $error = null;
        $userId = $this->authenticationService->getUserId();
        if(!$error && $userId !=  null) {
            $productId = $this->productRepository->addProductToDatabase($manufacturer, $userId, $name, $price);
            if($productId === null) {
                $error = "Product could not be created";
            }
        }
        else if($userId === null) {
            $error = "user not registered";
        }
        else{
            if(strlen($name) == 0) {
                $error = "invalid user";
            }
            $error = "Unknown error occured";
        }

        return $error;
    }
}