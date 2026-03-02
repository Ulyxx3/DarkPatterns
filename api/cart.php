<?php
session_start();
require_once __DIR__ . '/../includes/products.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$action = $_POST['action'] ?? $_GET['action'] ?? '';
if (!isset($_SESSION['cart']))
    $_SESSION['cart'] = [];

switch ($action) {
    case 'add':
        $id = (int) ($_POST['id'] ?? 0);
        $product = getProduct($id);
        if ($product) {
            if (isset($_SESSION['cart'][$id])) {
                $_SESSION['cart'][$id]['qty']++;
            } else {
                $_SESSION['cart'][$id] = [
                    'id' => $id,
                    'name' => $product['name'],
                    'price' => $product['price'],
                    'qty' => 1,
                    'image' => $product['image'],
                    'subscription' => false,
                    'subscription_price' => $product['subscription_price'],
                ];
            }
            echo json_encode(['success' => true, 'count' => array_sum(array_column($_SESSION['cart'], 'qty'))]);
        } else {
            echo json_encode(['success' => false]);
        }
        break;

    case 'remove':
        $id = (int) ($_POST['id'] ?? 0);
        unset($_SESSION['cart'][$id]);
        echo json_encode(['success' => true, 'count' => array_sum(array_column($_SESSION['cart'], 'qty'))]);
        break;

    case 'count':
        echo json_encode(['count' => array_sum(array_column($_SESSION['cart'], 'qty'))]);
        break;

    case 'get':
        echo json_encode(['cart' => $_SESSION['cart']]);
        break;

    case 'fake_viewers':
        // Fake live viewer data for FOMO
        echo json_encode([
            'viewers' => rand(31, 89),
            'recent_orders' => rand(5, 23),
            'stock_left' => 2, // always 2
        ]);
        break;

    default:
        echo json_encode(['error' => 'Unknown action']);
}
?>