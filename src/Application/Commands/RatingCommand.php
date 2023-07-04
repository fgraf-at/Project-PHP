<?php
namespace Application\Commands;
class RatingCommand{
    public function __construct(
        private \Application\Services\AuthenticationService $authenticationService,
        private \Application\Interfaces\RatingRepository $ratingRepository
    ) { }
    public function addRatingToDatabase(int $id, int $rating, string $comment) : ?string{
        $userId = $this->authenticationService->getUserId();
        $errors = 0;

        $comment = trim($comment);
        if($userId === null) {
            $errors |= "User not registered";
        }

        if(!$errors) {
            if( $this->ratingRepository->addRatingToDatabase(
                 $id,
                $userId,
                $rating,
                $comment
                )  === null){
                $errors = "Rating could not be created";
            }
        }

        return $errors;
    }

    public function removeRating(int $id){
        return $this->ratingRepository->removeRatingFromDatabase($id);
    }
}

