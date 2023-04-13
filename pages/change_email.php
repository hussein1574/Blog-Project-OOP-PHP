<?php
session_start();
include('../includes/header.php');
include('../includes/navbar.php');

// Include the User class and database configuration
require_once('../classes/User.php');
require_once('../config/database.php');


$oldEmail = $_SESSION['email'];

if ($_POST && isset($_POST['change-email'])) {

    // Create a new instance of the User class
    $user = new User($pdo);

    $email = trim($_POST['email']);

    // Call the register() method to add the user to the database
    $resuilt = $user->changeEmail($_SESSION['user_id'],$email);

    if ($resuilt === true) {
        // If the registration was successful, redirect to the login page
        header('Location: profile.php');
        exit;
    } else {
        // If there were errors, display them to the user
        $errors = $resuilt;
    }
}






?>
<section class="text-center">
    <!-- Background image -->
    <div class="p-5 bg-image" style="
        background-image: url('https://mdbootstrap.com/img/new/textures/full/171.jpg');
        height: 300px;
        "></div>
    <!-- Background image -->

    <div class="card mx-4 mx-md-5 shadow-5-strong" style="
        margin-top: -100px;
        background: hsla(0, 0%, 100%, 0.8);
        backdrop-filter: blur(30px);
        ">
        <div class="card-body py-5 px-md-5">

            <div class="row d-flex justify-content-center">
                <div class="col-lg-8">
                    <h2 class="fw-bold mb-5">Sign up now</h2>
                    <?php if (isset($errors)) : ?>
                    <div class=" alert alert-danger">
                        <ul>
                            <?php foreach ($errors as $error) : ?>
                            <li><?= $error ?></li>
                            <?php endforeach ?>
                        </ul>
                    </div>
                    <?php endif ?>
                    <form method="post">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" <?= "value= '$oldEmail'" ?> name="email" id="email" class="form-control"
                                required>
                        </div>
                        <button type="submit" name="change-email" class="btn btn-primary mt-2">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
include('../includes/footer.php')
?>