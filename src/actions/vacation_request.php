<?php
require '../classes/Vacations.php';

if (isset($_SESSION['user_id']) == false) {
    header('Location: index.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date_from = $_POST['date_from'];
    $date_to = $_POST['date_to'];
    $reason = $_POST['reason'];

    if (empty($date_from) || empty($date_to) || empty($reason)) {
        die('Error: Missing required fields');
    }

    $vacations = new Vacations();

    $is_inserted = $vacations->requestVacation($date_from, $date_to, $reason);

    if ($is_inserted == true) {
        header('Location: ../dashboard.php');
        exit;
    } else {
        echo "Failed to insert vacation.";
    }
}
?>
