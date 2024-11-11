<?php require 'header.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Epignosis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <?php require 'classes/Users.php'; ?>
    <?php require 'classes/Vacations.php'; ?>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <?php if ($_SESSION['is_manager'] == true) : ?>
                    <div class="card">
                        <div class="card-header text-center">
                            <h3>Users</h3>
                        </div>
                        <div class="card-body">
                            <div class="mb-3 text-start">
                                <button class="btn btn-primary" onclick="window.location.href='create_user.php'">
                                    Create User
                                </button>
                            </div>

                            <table class="table table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th scope="col">Name</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $db = new Users();
                                    $users = $db->getUsers();

                                    if (count($users) > 0) : ?>
                                        <?php foreach ($users as $user) : ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($user['username']); ?></td>
                                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                                <td>
                                                    <a href="/edit_user.php?id=<?php echo $user['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                                    <a href="/actions/delete_user.php?id=<?php echo $user['id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <tr><td colspan="3" class="text-center">No users found</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="card-header text-center">
                            <h3>Vacations</h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th scope="col">User</th>
                                        <th scope="col">Date Submited</th>
                                        <th scope="col">Dates Requested</th>
                                        <th scope="col">Total Days</th>
                                        <th scope="col">Reason</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $db = new Vacations();
                                    $vacations = $db->getVacationRequests();

                                    if (count($vacations) > 0) : ?>
                                        <?php foreach ($vacations as $vacation) : ?>
                                            <?php $total_days = $db->calculateTotalDays($vacation['date_from'], $vacation['date_to']) ?>
                                            
                                            <tr>
                                                <td><?php echo htmlspecialchars($vacation['username']); ?></td>
                                                <td><?php echo htmlspecialchars(date('Y-m-d', strtotime($vacation['created_at']))); ?></td>
                                                <td><?php echo htmlspecialchars($vacation['date_from']) . ' - ' . htmlspecialchars($vacation['date_to']); ?></td>
                                                <td><?php echo $total_days; ?></td>
                                                <td><?php echo htmlspecialchars($vacation['reason']); ?></td>
                                                <td><?php echo htmlspecialchars($vacation['approval_status_name']); ?></td>
                                                <td>
                                                    <?php if ($vacation['approval_status_name'] == 'Pending') : ?>
                                                        <a href="/actions/change_vacation_status.php?id=<?php echo $vacation['id']; ?>&status=approved" class="btn btn-success btn-sm">Approve</a>
                                                        <a href="/actions/change_vacation_status.php?id=<?php echo $vacation['id']; ?>&status=rejected" class="btn btn-danger btn-sm">Reject</a>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <tr><td colspan="7" class="text-center">No vacation requests found</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php else : ?>
                    <div class="card">
                        <div class="card-header text-center">
                            <h3>Vacations</h3>
                        </div>
                        <div class="card-body">
                            <div class="mb-3 text-start">
                                <button class="btn btn-primary" onclick="window.location.href='create_vacation.php'">
                                    Request Vacation
                                </button>
                            </div>

                            <table class="table table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th scope="col">User</th>
                                        <th scope="col">Date Submited</th>
                                        <th scope="col">Dates Requested</th>
                                        <th scope="col">Total Days</th>
                                        <th scope="col">Reason</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $db = new Vacations();
                                    $vacations = $db->getVacationRequests();

                                    if (count($vacations) > 0) : ?>
                                        <?php foreach ($vacations as $vacation) : ?>
                                            <?php $total_days = $db->calculateTotalDays($vacation['date_from'], $vacation['date_to']) ?>
                                            
                                            <tr>
                                                <td><?php echo htmlspecialchars($vacation['username']); ?></td>
                                                <td><?php echo htmlspecialchars(date('Y-m-d', strtotime($vacation['created_at']))); ?></td>
                                                <td><?php echo htmlspecialchars($vacation['date_from']) . ' - ' . htmlspecialchars($vacation['date_to']); ?></td>
                                                <td><?php echo $total_days; ?></td>
                                                <td><?php echo htmlspecialchars($vacation['reason']); ?></td>
                                                <td><?php echo htmlspecialchars($vacation['approval_status_name']); ?></td>
                                                <td>
                                                    <?php if ($vacation['approval_status_name'] == 'Pending' && $_SESSION['user_id'] == $vacation['user_id']) : ?>
                                                        <a href="/actions/delete_vacation.php?id=<?php echo $vacation['id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <tr><td colspan="7" class="text-center">No vacation requests found</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
