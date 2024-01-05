<?php // This file is mostly containing things for your view / html ?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" type="text/css"
          rel="stylesheet"/>
    <title>Wacky Weather Gear Boutique</title>
</head>
<body>
<div class="container">
    <h1>Place your order</h1>
    <nav>
        <ul class="nav">
            <li class="nav-item">
                <a class="nav-link active" href="?weather=0">Order gear</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="?weather=1">Order weather</a>
            </li>
        </ul>
    </nav>
    <form method="POST">
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="email">E-mail:</label>
                <input type="email" id="email" name="email" value= "<?= isset($_POST["email"]) ? $_POST["email"] : "" ?>" class="form-control"/>
            </div>
            <div></div>
        </div>

        <fieldset>
            <legend>Address</legend>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="street">Street:</label>
                    <input type="text" name="street" value= "<?= isset($_POST["street"]) ? $_POST["street"] : (isset($_SESSION["street"]) ? $_SESSION["street"] : "") ?>" id="street" class="form-control">
                </div>
                <div class="form-group col-md-6">
                    <label for="streetnumber">Street number:</label>
                    <input type="text" id="streetnumber" name="streetnumber" value= "<?= isset($_POST["streetnumber"]) ? $_POST["streetnumber"] : (isset($_SESSION["streetnumber"]) ? $_SESSION["streetnumber"] : "") ?>" class="form-control">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="city">City:</label>
                    <input type="text" id="city" name="city" value= "<?= isset($_POST["city"]) ? $_POST["city"] : (isset($_SESSION["city"]) ? $_SESSION["city"] : "") ?>" class="form-control">
                </div>
                <div class="form-group col-md-6">
                    <label for="zipcode">Zipcode</label>
                    <input type="text" id="zipcode" name="zipcode" value= "<?= isset($_POST["zipcode"]) ? $_POST["zipcode"] : (isset($_SESSION["zipcode"]) ? $_SESSION["zipcode"] : "") ?>" class="form-control">
                </div>
            </div>
        </fieldset>

        <fieldset>
            <legend>Products</legend>
            <?php if ($_GET["weather"] == 0) : ?>
                <?php foreach ($products as $i => $product): ?>
                    <label>
                        <?php // <?= is equal to <?php echo ?>
                        <input type="checkbox" value="1" name="products[<?= $i ?>]" <?= (isset($_POST["products"]) && array_key_exists($i, $_POST["products"])) ? "checked" : "" ?>/> <?= $product['name'] ?> -
                        &euro; <?= number_format($product['price'], 2) ?></label>
                        <input type="number" value="<?= isset($_POST["amounts"]) ? $_POST["amounts"][$i] : "1" ?>" name="amounts[]"/></label><br />
                <?php endforeach; ?>
            <?php elseif ($_GET["weather"] == 1) : ?>
                <?php foreach ($weatherProducts as $i => $product): ?>
                    <label>
                        <input type="checkbox" value="1" name="products[<?= $i ?>]" <?= (isset($_POST["products"]) && array_key_exists($i, $_POST["products"])) ? "checked" : "" ?>/> <?= $product['name'] ?> -
                        &euro; <?= number_format($product['price'], 2) ?></label>
                        <input type="number" value="<?= isset($_POST["amounts"]) ? $_POST["amounts"][$i] : "1" ?>" name="amounts[]"/></label><br />
                <?php endforeach; ?>
            <?php endif; ?>
        </fieldset><br>
        <input type="checkbox" value="1" name="express"/> Express delivery for &euro; 5.</label><br>
        <button type="submit" class="btn btn-primary">Order!</button>
    </form>

    <footer>You already ordered <strong>&euro; <?php echo $totalValue ?></strong> in gear and weather.</footer>
</div>

<style>
    footer {
        text-align: center;
    }
</style>
</body>
</html>