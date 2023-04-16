<?php
session_start();

// Include the database connection and Post class
include_once('../config/database.php');
include_once('../classes/Post.php');
include('../includes/header.php');
include('../includes/navbar.php');

$post = new Post($pdo);
$pageNumber = 1;
$remainingPages = false;
$error = false;
if(isset($_GET['page'])){
    $pageNumber = $_GET['page'];
}
if(!is_numeric($pageNumber) || $pageNumber < 1)
{
    header('Location: /blogs/pages/page_not_found.php');
}
$posts = $post->readAllMyPosts($_SESSION['user_id'],$pageNumber);

if(!isset($posts['count']))
{
    $error = true;
}
else 
{
    if(count($posts) == 1 && $pageNumber > 1)
    {
        header('Location: /blogs/pages/page_not_found.php');
    }
    if($posts['count'] > (count($posts) - 1) + ($pageNumber - 1) * 5)
    {
        $remainingPages = true;
    }
}



?>
<header class="masthead" style="background-image: url('assets/img/home-bg.jpg')">
    <div class="container position-relative px-4 px-lg-5">
        <div class="row gx-4 gx-lg-5 justify-content-center">
            <div class="col-md-10 col-lg-8 col-xl-7">
                <div class="site-heading">
                    <h1>My Blogs</h1>
                </div>
            </div>
        </div>
    </div>
</header>
<div class="container px-4 px-lg-5">
    <div class="row gx-4 gx-lg-5 justify-content-center">
        <div class="col-md-10 col-lg-8 col-xl-7">
            <?php if($error === true) {
                $_SESSION['error'] = $posts;
                header('Location: /blogs/pages/page_not_found.php');
            } ?>
            <!-- Post preview-->
            <?php foreach ($posts as $post) { ?>
            <?php if (!isset($post['id'])) 
                    continue;
                 ?>
            <div class="post-preview">
                <a href=<?= "post.php?id=". $post['id'] ?>>
                    <h2 class="post-title"><?php echo $post['title'] ?></h2>
                    <h3 class="post-subtitle"><?php echo substr($post['content'], 0, 10); ?></h3>
                </a>
                <p class="post-meta">
                    Posted by
                    <a href="#!"><?php echo $post['username']; ?></a>
                    <?php echo $post['created_at']; ?>
                </p>
            </div>


            <?php } ?>
            <!-- Divider-->
            <hr class="my-4" />
            <!-- Pager-->
            <div class="d-flex justify-content-end mb-4">
                <?php if($pageNumber > 1) : ?>
                <a class="btn btn-primary text-uppercase" style=" margin-right:320px"
                    href="?page=<?= $pageNumber - 1 ?>">←
                    Newer
                    Posts
                </a>
                <?php endif ?>
                <?php if($remainingPages === true) : ?>
                <a class="btn btn-primary text-uppercase" href="?page=<?= $pageNumber + 1 ?>">Older
                    Posts
                    →</a>
                <?php endif ?>
            </div>
        </div>
    </div>
</div>

<?php
include('../includes/footer.php');
?>