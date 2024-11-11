<?php
require '../classes/Vacations.php';

if (isset($_SESSION['is_manager']) && $_SESSION['is_manager'] == false) {
    header('Location: index.php');
}

$id = $_GET['id'];
$status = $_GET['status'];

$vacations = new Vacations();

if (empty($id) || empty($status)) {
    die('Error: Missing required fields');
}

// Change the vacation status
if ($status == 'approved') {
    $is_changed = $vacations->approveVacation($id);
} else if ($status == 'rejected') {
    $is_changed = $vacations->rejectVacation($id);
} else {
    echo 'Status is not valid';
}

if (isset($is_changed) && $is_changed == true) {
    header('Location: ../dashboard.php');
    exit;
} else {
    echo "Failed to change vacation status";
}

?>
