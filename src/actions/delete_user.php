<?php
require '../classes/Users.php';

if (isset($_SESSION['is_manager']) && $_SESSION['is_manager'] == false) {
    header('Location: dashboard.php');
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $users = new Users();

    // Delete the user by ID
    $is_deleted = $users->deleteUser($id);

    if (isset($is_deleted) && $is_deleted == true) {
        header('Location: ../dashboard.php');
        exit;
    } else {
        echo "Failed to delete user.";
    }
} else {
    echo "No user ID";
}
?>
