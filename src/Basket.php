<?php

namespace App;

use App\Strategy\OfferStrategy;
use App\Strategy\DeliveryStrategy;

/**
 * Class representing a shopping basket.
 * 
 * The shopping basket manages added products and calculates the total based on offer and delivery strategies.
 */
class Basket
{
    private \PDO $pdo;
    
    /**
     * Array holding product details from the catalogue.
     * 
     * @var array<string, array{code: string, name: string, price: float}> Associative array where the key is the product code and the value is an array of product details.
     * 
     * @phpstan-var array<string, array{code: string, name: string, price: float}> An associative array of product details. It can be empty.
     */
    private array $catalogue = [];
    
    /**
     * Strategy for applying offers.
     * 
     * @var OfferStrategy
     */
    private OfferStrategy $offerStrategy;
    
    /**
     * Strategy for calculating delivery charges.
     * 
     * @var DeliveryStrategy
     */
    private DeliveryStrategy $deliveryStrategy;
    
    /**
     * Array holding added product codes.
     * 
     * @var array<string> List of product codes.
     */
    private array $items = [];

    /**
     * Constructor for the Basket class.
     * 
     * @param \PDO $pdo Instance of PDO for database access.
     * @param OfferStrategy $offerStrategy Strategy for applying offers.
     * @param DeliveryStrategy $deliveryStrategy Strategy for calculating delivery charges.
     */
    public function __construct(\PDO $pdo, OfferStrategy $offerStrategy, DeliveryStrategy $deliveryStrategy)
    {
        $this->pdo = $pdo;
        $this->offerStrategy = $offerStrategy;
        $this->deliveryStrategy = $deliveryStrategy;
        $this->loadCatalogue();
    }

    /**
     * Loads the product catalogue from the database.
     * 
     * Fills the product catalogue with data from the database.
     */
    private function loadCatalogue(): void
    {
        $stmt = $this->pdo->query("SELECT * FROM products");
        
        if ($stmt instanceof \PDOStatement) {
            $products = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($products as $product) {
                $this->catalogue[$product['code']] = [
                    'code' => (string) $product['code'],
                    'name' => (string) $product['name'],
                    'price' => (float) $product['price'],
                ];
            }
        }
    }

    /**
     * Adds a product to the basket.
     * 
     * @param string $code Product code to add.
     */
    public function add(string $code): void
    {
        if (isset($this->catalogue[$code])) {
            $this->items[] = $code;
        }
    }

    /**
     * Calculates the total price of the basket, including applicable delivery charges.
     * 
     * @return float The total price of the basket.
     */
    public function total(): float
    {
        $subtotal = 0.0;
        $quantities = array_count_values($this->items);

        foreach ($quantities as $code => $quantity) {
            if (isset($this->catalogue[$code])) {
                $price = $this->catalogue[$code]['price']; // Ensure price is a float
                
                if ($code === 'R01') {
                    $subtotal += $this->offerStrategy->apply($price, $quantity);
                } else {
                    $subtotal += $price * $quantity;
                }
            }
        }

        $deliveryCharge = ($subtotal > 0) ? $this->deliveryStrategy->calculate($subtotal) : 0.0;

        return round($subtotal + $deliveryCharge, 2, PHP_ROUND_HALF_UP);
    }
}
