<?php

namespace App\Tests;

use App\Basket;
use App\Strategy\BogoOffer;
use App\Strategy\StandardDelivery;
use PHPUnit\Framework\TestCase;

class BasketTest extends TestCase
{
    private \PDO $pdo;
    private Basket $basket;

    protected function setUp(): void
    {
        // Setup an in-memory SQLite database for testing
        $this->pdo = new \PDO('sqlite::memory:');
        $this->setupDatabase();

        // Create instances of the strategies
        $offerStrategy = new BogoOffer();
        $deliveryStrategy = new StandardDelivery([
            ['threshold' => 50, 'charge' => 4.95],
            ['threshold' => 90, 'charge' => 2.95],
            ['threshold' => PHP_INT_MAX, 'charge' => 0]
        ]);

        // Initialize the Basket with strategies
        $this->basket = new Basket($this->pdo, $offerStrategy, $deliveryStrategy);
    }

    private function setupDatabase(): void
    {
        // Create products table and insert test data
        $this->pdo->exec("
            CREATE TABLE products (
                code TEXT PRIMARY KEY,
                name TEXT NOT NULL,
                price REAL NOT NULL
            );
        ");

        $this->pdo->exec("
            INSERT INTO products (code, name, price) VALUES
            ('R01', 'Red Widget', 32.95),
            ('G01', 'Green Widget', 24.95),
            ('B01', 'Blue Widget', 7.95);
        ");
    }

    public function testBasketTotalWithoutOffers(): void
    {
        $this->basket->add('B01');
        $this->basket->add('G01');
        $this->assertEquals(37.85, $this->basket->total());
    }

    public function testBasketTotalWithBOGO(): void
    {
        $this->basket->add('R01');
        $this->basket->add('R01');
        $this->assertEquals(54.38, $this->basket->total());
    }

    public function testBasketTotalWithoutOffersAgain(): void
    {
        $this->basket->add('R01');
        $this->basket->add('G01');
        $this->assertEquals(60.85, $this->basket->total());
    }

    public function testBasketTotalWithMixedProducts(): void
    {
        $this->basket->add('B01');
        $this->basket->add('B01');
        $this->basket->add('R01');
        $this->basket->add('R01');
        $this->basket->add('R01');
        $this->assertEquals(98.28, $this->basket->total());
    }

    public function testBasketTotalWithNoItems(): void
    {
        $this->assertEquals(0.00, $this->basket->total());
    }
}
