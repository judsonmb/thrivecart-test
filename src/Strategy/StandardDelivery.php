<?php

namespace App\Strategy;

/**
 * Strategy for standard delivery charges.
 * 
 * Calculates delivery charges based on predefined thresholds and charges.
 */
class StandardDelivery implements DeliveryStrategy
{
    /** 
     * @var array<int, array{threshold: float, charge: float}> 
     */
    private array $rules;

    /**
     * Constructor for the StandardDelivery class.
     * 
     * @param array<int, array{threshold: float, charge: float}> $rules Delivery charge rules based on subtotal thresholds.
     */
    public function __construct(array $rules)
    {
        $this->rules = $rules;
    }

    /**
     * Calculates the delivery charge based on the given subtotal.
     * 
     * @param float $subtotal The subtotal of the basket.
     * @return float The delivery charge.
     */
    public function calculate(float $subtotal): float
    {
        foreach ($this->rules as $rule) {
            if ($subtotal < $rule['threshold']) {
                return $rule['charge'];
            }
        }

        return 0.0;
    }
}
