<?php
require '../classes/Users.php';

if (isset($_SESSION['is_manager']) && $_SESSION['is_manager'] == false) {
    header('Location: dashboard.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $role = $_POST['role'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $employee_code = $_POST['employee_code'];
    $password = $_POST['password'];

    if (empty($id) || empty($role) || empty($username) || empty($email) || empty($employee_code) || empty($password)) {
        die('Error: Missing required fields');
    }

    $users = new Users();

    // Edit the user's data
    $is_updated = $users->editUserData($id, $username, $email, $employee_code, $password, $role);

    if ($is_updated == true) {
        header('Location: ../dashboard.php');
        exit;
    } else {
        echo "Failed to edit user.";
    }
}
?>
