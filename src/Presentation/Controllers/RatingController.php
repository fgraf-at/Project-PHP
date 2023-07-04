<?php
namespace Presentation\Controllers;

class RatingController extends  \Presentation\MVC\Controller {

    public function __construct(
    private \Application\Commands\RatingCommand $ratingCommand,
    private \Application\Query\ProductQuery $productQuery,
    private \Application\SignedInUserQuery $signedInUserQuery,
    ) {
    }

    public function POST_Create(): \Presentation\MVC\ActionResult {
    $rating = $this->getParam("rt");
    $comment = $this->getParam("ct");
    $id = $this->getParam("id");

    if($this->ratingCommand->addRatingToDatabase($id, $rating, $comment) != 0) {
        return $this->view(
                "productInfo", [
                "user" => $this->signedInUserQuery->execute(),
                "product" => $this->productQuery->getProduct($id),
                "errors" => ["An error occured"]
            ]);
    }
    return $this->view(
        "productInfo", [
            "user" => $this->signedInUserQuery->execute(),
            "product" => $this->productQuery->getProduct($id),
        ]);
    }

    public function POST_Remove(): \Presentation\MVC\ActionResult
    {
        $rid = $this->getParam("rid");
        $id = $this->getParam("pid");
        $result = $this->ratingCommand->removeRating($rid);
        if($result != null) {
            $errors = [];
            return $this->view(
                "productInfo", [
                "user" => $this->signedInUserQuery->execute(),
                "product" => $this->productQuery->getProduct($id),
                "errors" => ["User is not signed in"]
            ]);
        }
        return $this->redirect("ProductsController","Search");
    }

    public function POST_Edit(): \Presentation\MVC\ActionResult {


    }


}