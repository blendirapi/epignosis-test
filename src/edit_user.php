<?php require 'header.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User | Epignosis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php 
        require 'classes/Users.php';

        if ($_SESSION['is_manager'] == false) {
            header('Location: dashboard.php');
        }
    ?>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header text-center">
                        <h3>Edit User</h3>
                    </div>
                    <?php
                        $db = new Users();
                        $user_data = $db->getUserData($_GET['id']);
                        $roles = $db->getUserRoles();
                    ?>
                    <div class="card-body">
                        <form action="/actions/edit.php" method="POST">
                            <input type="hidden" name="id"  value="<?php echo $user_data['id']; ?>">
                            <div class="mb-3">
                                <label for="role" class="form-label">Role</label>
                                <select class="form-control" id="role" name="role" required>
                                    <?php foreach ($roles as $role) : ?>
                                        <?php if ($user_data['role_id'] == $role['id']) : ?>
                                            <option value="<?php echo $role['id']; ?>" selected><?php echo $role['role_name']; ?></option>
                                        <?php else : ?>
                                            <option value="<?php echo $role['id']; ?>"><?php echo $role['role_name']; ?></option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" required value="<?php echo $user_data['username']; ?>">
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required value="<?php echo $user_data['email']; ?>">
                            </div>
                            <div class="mb-3">
                                <label for="employee_code" class="form-label">Employee Code</label>
                                <input type="text" class="form-control" id="employee_code" name="employee_code" maxlength="7" required value="<?php echo $user_data['employee_code']; ?>">
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">New Password</label>
                                <input type="text" class="form-control" id="password" name="password" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Edit User</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
