<?php
// Đặt thời gian sống của cookie là 3 năm (94,608,000 giây)
session_set_cookie_params(94608000);
session_start();

// Create a cart array if needed
if (empty($_SESSION['cart12'])) { $_SESSION['cart12'] = array(); }

// Create a table of products
$products = array();
$products['MMS-1754'] = array('name' => 'Flute', 'cost' => '149.50');
$products['MMS-6289'] = array('name' => 'Trumpet', 'cost' => '199.50');
$products['MMS-3408'] = array('name' => 'Clarinet', 'cost' => '299.50');

// Include cart functions
require_once('cart.php');

// Get the action to perform
$action = filter_input(INPUT_POST, 'action');
if ($action === NULL) {
    $action = filter_input(INPUT_GET, 'action');
    if ($action === NULL) {
        $action = 'show_add_item';
    }
}

// Add or update cart as needed
switch($action) {
    case 'add':
        $product_key = filter_input(INPUT_POST, 'productkey');
        $item_qty = filter_input(INPUT_POST, 'itemqty');
        add_item($product_key, $item_qty);
        include('cart_view.php');
        break;
    case 'update':
        $new_qty_list = filter_input(INPUT_POST, 'newqty', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        foreach($new_qty_list as $key => $qty) {
            if ($_SESSION['cart12'][$key]['qty'] != $qty) {
                update_item($key, $qty);
            }
        }
        include('cart_view.php');
        break;
    case 'show_cart':
        include('cart_view.php');
        break;
    case 'show_add_item':
        include('add_item_view.php');
        break;
    case 'empty_cart':
        unset($_SESSION['cart12']);
        include('cart_view.php');
        break;
        case 'end_session':
        // 1. Xóa toàn bộ dữ liệu session (xóa giỏ hàng)
        session_unset();
        
        // 2. Hủy session trên server
        session_destroy();
        
        // 3. Xóa cookie session trên trình duyệt (đặt thời gian hết hạn về quá khứ)
        setcookie(session_name(), '', time() - 3600, '/');
        
        // 4. Chuyển hướng người dùng về trang chủ
        header("Location: index.php");
        exit();
        break;
}
?>