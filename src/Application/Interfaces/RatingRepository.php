<?php

namespace Application\Interfaces;

interface RatingRepository {

    public function getAverageRatingForProduct(int $productId): float;
    public function getTotalAmountOfRatingsForProduct(int $id): int;
    public function addRatingToDatabase( string $productId,
                                         int $idUser,
                                         int $rating,
                                         string $comment
    );
    public function removeRatingFromDatabase(int $id);
}