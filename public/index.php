<?php
require '../vendor/autoload.php';

use App\Database;
use App\Basket;
use App\Strategy\BogoOffer;
use App\Strategy\StandardDelivery;

$dsn = 'mysql:host=db;dbname=mydatabase;charset=utf8';
$username = 'user';
$password = 'userpassword';
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

$db = new Database($dsn, $username, $password);
$pdo = $db->getPdo();

// Configura as estratégias
$offerStrategy = new BogoOffer();
$deliveryStrategy = new StandardDelivery([
    ['threshold' => 50, 'charge' => 4.95],
    ['threshold' => 90, 'charge' => 2.95],
    ['threshold' => PHP_INT_MAX, 'charge' => 0]
]);

$basket = new Basket($pdo, $offerStrategy, $deliveryStrategy);

$total = 0;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST as $code => $quantity) {
        if (is_numeric($quantity) && $quantity > 0) {
            for ($i = 0; $i < $quantity; $i++) {
                $basket->add($code);
            }
        }
    }
    $total = $basket->total();
}

$products = $pdo->query("SELECT * FROM products")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Basket</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <header>
        <h1>Shopping Basket</h1>
    </header>
    <main>
        <div class="container">
            <form id="basket-form" method="post">
                <div class="product-list">
                    <?php foreach ($products as $product): ?>
                        <div class="product">
                            <div class="product-info">
                                <h2><?php echo htmlspecialchars($product['name']); ?></h2>
                                <p>Price: $<?php echo number_format($product['price'], 2); ?></p>
                                <label for="quantity_<?php echo htmlspecialchars($product['code']); ?>">Quantity:</label>
                                <input type="number" id="quantity_<?php echo htmlspecialchars($product['code']); ?>" name="<?php echo htmlspecialchars($product['code']); ?>" min="0" value="0">
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <button type="submit">Calculate Total</button>
            </form>
            <?php if ($total > 0): ?>
                <div class="total">
                    <h2>Total: $<?php echo number_format($total, 2); ?></h2>
                </div>
            <?php endif; ?>
        </div>
    </main>
    <footer>
        <p>&copy; 2024 Your Company</p>
    </footer>
    <script src="js/script.js"></script>
</body>
</html>
