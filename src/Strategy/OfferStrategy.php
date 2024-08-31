<?php

namespace App\Strategy;

/**
 * Interface for offer strategies.
 * 
 * Defines a method for applying offers to products.
 */
interface OfferStrategy
{
    /**
     * Applies an offer to the given price and quantity.
     * 
     * @param float $price The price of the product.
     * @param int $quantity The quantity of the product.
     * @return float The total price after applying the offer.
     */
    public function apply(float $price, int $quantity): float;
}