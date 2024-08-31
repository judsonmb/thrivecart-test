<?php

namespace App\Strategy;

/**
 * Interface for delivery charge strategies.
 * 
 * Defines a method for calculating delivery charges based on subtotal.
 */
interface DeliveryStrategy
{
    /**
     * Calculates the delivery charge based on the given subtotal.
     * 
     * @param float $subtotal The subtotal of the basket.
     * @return float The delivery charge.
     */
    public function calculate(float $subtotal): float;
}
