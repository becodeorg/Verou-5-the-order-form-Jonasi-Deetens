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
    // TODO: This function will send a list of invalid fields back
    return [];
}

function handleForm()
{
    // TODO: form related tasks (step 1)
    // Validation (step 2)
    $invalidFields = validate();
    if (!empty($invalidFields)) {
        // TODO: handle errors
    } else {
        displayConfirmation();
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

    echo $confirmationMessage;
}

// TODO: replace this if by an actual check for the form to be submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    whatIsHappening();
    handleForm();
}

require 'form-view.php';