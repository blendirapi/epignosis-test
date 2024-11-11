<?php
require '../classes/Vacations.php';

if (isset($_SESSION['user_id']) == false) {
    header('Location: index.php');
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $vacations = new Vacations();

    // Delete the vacation by its ID
    $is_deleted = $vacations->deleteVacation($id);

    if (isset($is_deleted) && $is_deleted == true) {    
        header('Location: ../dashboard.php');
        exit;
    } else {
        echo "Failed to delete vacation.";
    }
} else {
    echo "No vacation ID";
}
?>
