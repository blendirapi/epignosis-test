<?php
require '../classes/Users.php';

if (isset($_SESSION['is_manager']) && $_SESSION['is_manager'] == false) {
    header('Location: index.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $role = $_POST['role'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $employee_code = $_POST['employee_code'];
    $password = $_POST['password'];

    if (empty($role) || empty($username) || empty($email) || empty($employee_code) || empty($password)) {
        die('Error: Missing required fields');
    }

    if (strlen($employee_code) > 7) {
        $employee_code = substr($employee_code, 0, 7);
    }

    $users = new Users();

    // Register the user
    $is_inserted = $users->registerUser($username, $email, $employee_code, $password, $role);

    if ($is_inserted == true) {
        header('Location: ../dashboard.php');
        exit;
    } else {
        echo "Failed to insert user.";
    }
}
?>
