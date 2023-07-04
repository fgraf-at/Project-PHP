<?php

namespace Presentation\Controllers;

use Presentation\MVC\ActionResult;

class ProductsController extends \Presentation\MVC\Controller
{

    public function __construct(
        private \Application\SignedInUserQuery $signedInUserQuery,
        private \Application\Query\ProductQuery $productQuery,
        private \Application\Commands\ProductCommand $productCommand
    ) {
    }
    public function GET_Start(): \Presentation\MVC\ActionResult
    {
        $errors = [];
        $product = null;
        $idParam = "";
        if(!$this->tryGetParam("id", $idParam)) {
            $errors[] = "Product not found";
        } else {
            $id = intval($idParam);
            if(intval($idParam) != 0) {
                $product = $this->productQuery->getProduct($id);
            }
            if($product === null) {
                $errors[] = "Product could not be found!";
            }
        }

        if(sizeof($errors) > 0) {
            $errors[] = "Product not found.";
            return $this->view(
                "productEdit",
                [
                    "user" => $this->signedInUserQuery->execute(),
                    "product" => $product,
                    "errors" => $errors
                ]);
        } else {
            return $this->view("productEdit",
                [
                    "user" => $this->signedInUserQuery->execute(),
                    "product" => $product
                ]);
        }
    }
    public function GET_Search(): \Presentation\MVC\ActionResult
    {
        $filter         = $this->tryGetParam('filter', $filter) ? trim($filter) : null;
        $products       = $this->productQuery->searchProductByFilter($filter);
        $user           = $this->signedInUserQuery->execute();

        return $this->view("product",
            [
                "filter"    => $filter,
                "products"  => $products,
                "user"      => $user
            ]
        );
    }

    public function GET_Detail(): \Presentation\MVC\ActionResult
    {
        $errors = [];
        $product = null;
        $idParam = "";
        if(!$this->tryGetParam("id", $id)) {
            return "Product could not be found!";
        } else  if(sizeof($errors) <= 0){
            return $this->view("productInfo",
                [
                    "user" => $this->signedInUserQuery->execute(),
                    "product" => $this->productQuery->getProduct(intval($id))
                ]);
        }
    }



    public function GET_Edit(): \Presentation\MVC\ActionResult {

        // check for valid id

        $errors = [];
        $product = null;
        if(!$this->tryGetParam("id", $idParam)) {
            $errors[] = "Product not found.";
            return $this->view(
                "productEdit",
                [
                    "user" => $this->signedInUserQuery->execute(),
                    "errors" => $errors
                ]);
        } else {
            $id = intval($idParam);
            if(intval($idParam) != 0) {
                $product = $this->productQuery->getProduct($id);
            }
            if($product === null) {
                $errors[] = "Product not found.";
                return $this->view(
                    "productEdit",
                    [
                        "user" => $this->signedInUserQuery->execute(),
                        "errors" => $errors
                    ]);
            }

        }
    return $this->view("productEdit",
        [
            "user" => $this->signedInUserQuery->execute(),
            "product" => $product
        ]);
    }

    public function Post_Edit(): ActionResult {

        $productId = $this->getParam("id");
        $name = $this->getParam("productName");
        $producer = $this->getParam("producer");

        $result = $this->productCommand->edit($productId, $producer, $name);

        if($result != 0) {
            return $this->view(
                "productEdit", [
                "user" => $this->signedInUserQuery->execute(),
                "product" => $this->productQuery->execute($productId),
                "errors" => ["An error occured"]
            ]);
        }
        return $this->redirect("ProductsController", "Search");
    }



    public function GET_Create(): \Presentation\MVC\ViewResult {
        return $this->view("productAdd", [
            "user" => $this->signedInUserQuery->execute()
        ]);
    }

    public function Post_Create(): \Presentation\MVC\ActionResult {

        $name = $this->getParam("name");
        $manufacturer = $this->getParam("manufacturer");
        $price = $this->getParam("price");
        $res = $this->productCommand->addProduct($manufacturer, $name, $price);
        if($res != null) {
            return $this->redirect("ProductsController", "Search");

        }
        return $this->view(
            "productAdd",[
            "user" => $this->signedInUserQuery->execute(),
            "errors" => [$res]
        ]);
    }

}
