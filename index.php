<!DOCTYPE html>
<html lang="en">

<?php
    include("header.php");
    $i="mon_texte";
    for($i = 0; $i < 5; $i++){
        echo("<p> $i </p>");
    }
?>

<div class="container">
    <h2>Multiplication Calculator</h2>
    <input type="number" id="input1" placeholder="Enter first number">
    <input type="number" id="input2" placeholder="Enter second number">
    <button onclick="multiply()">Calculate</button>
    <p id="result"></p>

    <script src="script.js"></script>
</div>
