<?php
function postValue($field)
{
    if (isset($_POST[$field]) && is_string($_POST[$field])) {
        return trim($_POST[$field]);
    }

    return "";
}

function escapeHtml($value)
{
    return htmlspecialchars($value, ENT_QUOTES, "UTF-8");
}

$firstName = postValue("firstname");
$lastName = postValue("lastname");
$age = postValue("age");
$date = postValue("bookday");
$partySize = postValue("partysize");

$speciesNames = [
    "M" => "Human",
    "D" => "Dwarf",
    "E" => "Elf",
    "H" => "Hobbit"
];

$menuNames = [
    "none" => "none",
    "lembas" => "lembas",
    "mushrooms" => "mushrooms",
    "ent" => "ent draft",
    "cram" => "cram"
];

$species = $speciesNames[postValue("species")] ?? "";
$menu = $menuNames[postValue("food")] ?? "";

$bookingOptions = [
    "accom" => "Accommodation",
    "4day" => "4 Day Tour",
    "10day" => "10 Day Tour"
];

$selectedBookings = [];
foreach ($bookingOptions as $field => $label) {
    if (isset($_POST[$field])) {
        $selectedBookings[] = $label;
    }
}

$errors = [];

if ($firstName === "" || $lastName === "") {
    $errors[] = "Enter your first and last names.";
}

if (!ctype_digit($age) || (int) $age < 1) {
    $errors[] = "Enter a positive whole number for your age.";
}

if ($date === "") {
    $errors[] = "Enter a booking date.";
}

if (!ctype_digit($partySize) || (int) $partySize < 1) {
    $errors[] = "Enter at least one traveller.";
}

if ($species === "" || $menu === "") {
    $errors[] = "Select a species and a menu preference.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Booking Confirmation</title>
</head>
<body>
    <h1>Rohirrim Tour Booking Confirmation</h1>

    <?php if ($_SERVER["REQUEST_METHOD"] !== "POST"): ?>
        <p>Please submit the booking form first.</p>
        <p><a href="register.html">Back to the booking form</a></p>
    <?php elseif ($errors): ?>
        <p>Please correct the following details:</p>
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= escapeHtml($error) ?></li>
            <?php endforeach; ?>
        </ul>
        <p><a href="register.html">Back to the booking form</a></p>
    <?php else: ?>
        <p>
            Welcome <?= escapeHtml("$firstName $lastName") ?>!<br>
            <?php if ($selectedBookings): ?>
                You are now booked for <?= escapeHtml(implode(" and ", $selectedBookings)) ?>.<br>
            <?php else: ?>
                Booking: None selected<br>
            <?php endif; ?>
            Species: <?= escapeHtml($species) ?><br>
            Age: <?= escapeHtml($age) ?><br>
            Meal Preference: <?= escapeHtml($menu) ?><br>
            Number of travellers: <?= escapeHtml($partySize) ?>
        </p>
    <?php endif; ?>
</body>
</html>