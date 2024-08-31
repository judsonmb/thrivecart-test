<?php

namespace App\Strategy;

/**
 * Strategy for Buy-One-Get-One (BOGO) offer.
 * 
 * Applies the BOGO offer to the price of products.
 */
class BogoOffer implements OfferStrategy
{
    /**
     * Applies the Buy-One-Get-One (BOGO) offer to a product.
     * 
     * @param float $price The price of the product.
     * @param int $quantity The quantity of the product.
     * @return float The total price after applying the BOGO offer.
     */
    public function apply(float $price, int $quantity): float
    {
        $fullPriceItems = ceil($quantity / 2);
        $halfPriceItems = floor($quantity / 2);
        return ($fullPriceItems * $price) + ($halfPriceItems * ($price / 2));
    }
}

