<?php
session_start();

// Include the database connection and Post class
include_once('../config/database.php');
include_once('../classes/Post.php');
include('../includes/header.php');
include('../includes/navbar.php');

?>

<header class="masthead" style="background-image: url('assets/img/home-bg.jpg')">
    <div class="container position-relative px-4 px-lg-5">
        <div class="row gx-4 gx-lg-5 justify-content-center">
            <div class="col-md-10 col-lg-8 col-xl-7">
                <div class="site-heading">
                    <h1>My Profile</h1>
                    <h2>Username: <?php echo $_SESSION['username']; ?></h2>
                    <h2>Email: <?php echo $_SESSION['email']; ?></h2>
                </div>
            </div>
        </div>
    </div>
</header>
<div class="container px-4 px-lg-5">
    <div class="row gx-4 gx-lg-5 justify-content-center">
        <a href="change_username.php" class="btn btn-primary">Change Username</a>
        <a href="change_email.php" class="btn btn-primary">Change Email</a>
        <a href="change_password.php" class="btn btn-primary">Change Password</a>
    </div>
</div>


<?php
include('../includes/footer.php');
?>