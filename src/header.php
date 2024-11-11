<?php
session_start();

if (isset($_SESSION['user_id']) == false) {
    header('Location: index.php');
}
?>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Epignosis</a>
        <div class="d-flex ms-auto">
            <form action="/actions/logout.php" method="post">
                <button type="submit" class="btn btn-outline-danger">Logout</button>
            </form>
        </div>
    </div>
</nav>