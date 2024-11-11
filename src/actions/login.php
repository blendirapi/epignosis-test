<?php
require '../classes/Users.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        die('Error: Missing required fields');
    }

    $users = new Users();

    // Login the user
    $is_logged_in = $users->loginUser($username, $password);

    if (isset($is_logged_in) && $is_logged_in == true) {
        header('Location: ../dashboard.php');
        exit;
    } else {
        header('Location: ../index.php?is_error=1');
    }
}
?>
