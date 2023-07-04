<?php

namespace Application\Data;

use Application\Entities\Product;
use Application\Entities\User;

class ProductData
{
    public function __construct(
        private Product $product,
        private User $user,
        private int $averageRating,
        private int $totalAmountOfRatings,
        private ?array $ratings = null
    ) {
    }


    /**
     * @return Product
     */
    public function getProduct(): Product
    {
        return $this->product;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }
    /**
     * @return int
     */
    public function getAverageRating(): int
    {
        return $this->averageRating;
    }

    /**
     * @return int
     */
    public function getTotalAmountOfRatings(): int
    {
        return $this->totalAmountOfRatings;
    }

    /**
     * @return array|null
     */
    public function getRatings(): ?array
    {
        return $this->ratings;
    }
}
