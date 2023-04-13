<?php
session_start();

// Include the database connection and Post class
include_once('../config/database.php');
include_once('../classes/Post.php');
include('../includes/header.php');
include('../includes/navbar.php');



    // Create a new Post object and set its properties
    $id = $_GET['id'];
    if (isset($id)) {
    $postobj1 = new Post($pdo);

        if ($postobj1->readPost($id)) {

            $post = $postobj1->readPost($id);
            $title =$post['title'];
        }
    }

    // Check if the form has been submitted
    if (isset($_POST['edit'])) {
    $postobj2 = new Post($pdo);
        // Attempt to create the post
        if ($postobj2->updatePost($_POST['title'], $_POST['content'], $id)) {
            // Redirect to the homepage or display a success message
            header('Location: /Blogs/index.php');
            exit;
        } else {
            // Display an error message
            echo 'An error occurred while editing the post.';
        }
    }


    if ($_SESSION['user_id'] != $post['user_id']) {
        header('Location: /Blogs/index.php');
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
                    <h2 class="fw-bold mb-5">Edit Post</h2>
                    <form method="post">
                        <!-- Email input -->
                        <div class="form-outline mb-4">
                            <label class="form-label" for="form3Example3">Title</label>
                            <input <?= "value= '$title' " ?> name="title" type="text" id="form3Example3"
                                class="form-control" />
                        </div>

                        <!-- Password input -->
                        <div class="form-outline mb-4">
                            <label class="form-label" for="form3Example4">Content</label>
                            <textarea name="content" type="password" id="form3Example4" class="form-control"
                                required><?= $post['content'] ?></textarea>
                        </div>



                        <!-- Submit button -->
                        <button name="edit" type="submit" class="btn btn-primary btn-block mb-4">
                            Edit Post
                        </button>


                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- HTML form for adding a new post -->

<?php
include('../includes/footer.php')
?>