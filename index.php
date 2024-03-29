<?php

// This file is your starting point (= since it's the index)
// It will contain most of the logic, to prevent making a messy mix in the html

// This line makes PHP behave in a more strict way
declare(strict_types=1);

// We are going to use session variables so we need to enable sessions
session_start();

// Use this function when you need to need an overview of these variables
function whatIsHappening() {
    echo '<h2>$_GET</h2>';
    var_dump($_GET);
    echo '<h2>$_POST</h2>';
    var_dump($_POST);
    echo '<h2>$_COOKIE</h2>';
    var_dump($_COOKIE);
    echo '<h2>$_SESSION</h2>';
    var_dump($_SESSION);
}

// TODO: provide some products (you may overwrite the example)
$products = [
    ['name' => 'Cloud-in-a-Jar Kit', 'price' => 24.99],
    ['name' => 'Portable Fog Machine', 'price' => 14.99],
    ['name' => 'Hailstone Stress Balls', 'price' => 1.99],
    ['name' => 'Storm Chaser Binoculars', 'price' => 4.99],
    ['name' => 'Rainbow Forecast Umbrellas', 'price' => 249.99]
];

$weatherProducts = [
    ['name' => 'Sunny Weather', 'price' => 249.99],
    ['name' => 'Rainy Weather', 'price' => 4.99],
    ['name' => 'Cloudy Weather', 'price' => 9.99],
    ['name' => 'Stormy Weather', 'price' => 1.99]
];

$orders = isset($_COOKIE["orders"]) ? json_decode($_COOKIE["orders"], true) : [];
var_dump($orders);

$topProduct = getTopProduct();
$totalValue = isset($_COOKIE["totalvalue"]) ? $_COOKIE["totalvalue"] : 0;
$totalNumberOfProducts = isset($_COOKIE["totalnumberofproducts"]) ? $_COOKIE["totalnumberofproducts"] : 0;
function validate()
{
    $invalidFields = [];
    // TODO: This function will send a list of invalid fields back
    if (empty($_POST["zipcode"])) $invalidFields[] = "Zipcode can't be empty.";
    else if (!is_numeric($_POST["zipcode"])) $invalidFields[] = "Zipcode has to be a number.";
    if (empty($_POST["email"])) $invalidFields[] = "E-mail can't be empty.";
    else if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) $invalidFields[] = "Please fill in a valid email.";
    if (empty($_POST["city"])) $invalidFields[] = "City can't be empty.";
    if (empty($_POST["street"])) $invalidFields[] = "Street can't be empty.";
    if (empty($_POST["streetnumber"])) $invalidFields[] = "Street number can't be empty.";
    if (!isset($_POST["products"])) $invalidFields[] = "Please select a product!";

    return $invalidFields;
}

function handleForm()
{
    global $topProduct;
    
    $invalidFields = validate();
    if (!empty($invalidFields)) {
        foreach ($invalidFields as $error) {
            echo "<div class='alert alert-danger' role='alert'>" . $error . "</div>";
        }
    } else {
        displayConfirmation();
        updateSession();
        $_POST = [];
        $topProduct = getTopProduct();
    }
}

function getTopProduct()
{
    global $orders;

    if (!empty($orders)) {
        usort($orders, function ($a, $b) {
            return $b['amount'] <=> $a['amount'];
        });
    
        return $orders[0]["product"];
    }
}

function updateSession()
{
    $_SESSION["email"] = $_POST["email"];
    $_SESSION["city"] = $_POST["city"];
    $_SESSION["zipcode"] = $_POST["zipcode"];
    $_SESSION["street"] = $_POST["street"];
    $_SESSION["streetnumber"] = $_POST["streetnumber"];
}

function updateOrders(array $product, string $amount)
{
    global $orders;

    $containsProduct = false;
    foreach ($orders as &$order) {
        if ($order["product"]["name"] == $product["name"]) {
            $containsProduct = true;
            $order["amount"] += intval($amount);
        }
    }

    if (!$containsProduct) $orders[] = ["product" => $product, "amount" => $amount];
}

function displayConfirmation()
{
    global $products, $weatherProducts, $totalValue, $totalNumberOfProducts, $orders;

    $confirmationMessage = "Thank you for ordering:";

    foreach ($_POST["products"] as $index => $product) {
        if ($_GET["weather"] == 0) {
            $confirmationMessage .= " " . $products[$index]["name"] . " x " . $_POST["amounts"][$index];
            $totalValue += $products[$index]["price"] * $_POST["amounts"][$index];
            updateOrders($products[$index], $_POST["amounts"][$index]);
        }
        else if ($_GET["weather"] == 1) {
            $confirmationMessage .= " " . $weatherProducts[$index]["name"] . " x " . $_POST["amounts"][$index];
            $totalValue += $weatherProducts[$index]["price"] * $_POST["amounts"][$index];
            updateOrders($weatherProducts[$index], $_POST["amounts"][$index]);
        }
        $totalNumberOfProducts += $_POST["amounts"][$index];
    }

    $confirmationMessage .= ". You order will be delivered shortly to " . $_POST["street"] . " " . $_POST["streetnumber"] . ", " . $_POST["city"] . " " . $_POST["zipcode"] . " !<br>";
    
    if (isset($_POST["express"])) {
        $totalValue += 5;
        $confirmationMessage .= "Delivery expected within 45 minutes!";
    } else {
        $confirmationMessage .= "Expected delivery time is 2 hours.";
    }

    setcookie("totalvalue", strval($totalValue), time() + (86400 * 30));
    setcookie("totalnumberofproducts", strval($totalNumberOfProducts), time() + (86400 * 30));
    setcookie("orders", json_encode($orders), time() + (86400 * 30));
    
    echo "<div class='alert alert-success' role='alert'>" . $confirmationMessage . "</div>";
}

// TODO: replace this if by an actual check for the form to be submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    whatIsHappening();
    handleForm();
}

require 'form-view.php';