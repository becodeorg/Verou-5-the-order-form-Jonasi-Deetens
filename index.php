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
    ['name' => 'Cloud-in-a-Jar Kit', 'price' => 25],
    ['name' => 'Portable Fog Machine', 'price' => 15],
    ['name' => 'Hailstone Stress Balls', 'price' => 2],
    ['name' => 'Storm Chaser Binoculars', 'price' => 5],
    ['name' => 'Rainbow Forecast Umbrellas', 'price' => 250]
];

$totalValue = 0;

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
    // TODO: form related tasks (step 1)
    // Validation (step 2)
    $invalidFields = validate();
    if (!empty($invalidFields)) {
        foreach ($invalidFields as $error) {
            echo "<div class='alert alert-danger' role='alert'>" . $error . "</div>";
        }
    } else {
        displayConfirmation();
        $_POST = [];
    }
}

function displayConfirmation()
{
    global $products;

    $confirmationMessage = "Thank you for ordering:";

    foreach ($_POST["products"] as $index => $product) {
        $confirmationMessage .= " " . $products[$index]["name"];
    }

    $confirmationMessage .= ". You order will be delivered shortly to " . $_POST["street"] . " " . $_POST["streetnumber"] . ", " . $_POST["city"] . " " . $_POST["zipcode"] . " !";

    echo "<div class='alert alert-success' role='alert'>" . $confirmationMessage . "</div>";
}

// TODO: replace this if by an actual check for the form to be submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    whatIsHappening();
    handleForm();
}

require 'form-view.php';