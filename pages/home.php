<?php
include_once 'config/database.php';
include_once('classes/Post.php');

session_start();
include('includes/header.php');
include('includes/navbar.php');

$post = new Post($pdo);
$pageNumber = 1;
$remainingPages = false;
$search = "";
$error = false;
if(isset($_GET['page'])){
    $pageNumber = $_GET['page'];
}
if(isset($_GET['search'])){
    $search = $_GET['search'];
    $posts = $post->SearchForPosts($search, $pageNumber);  
}
else
    $posts = $post->readAllPosts($pageNumber);

if(!isset($posts['count']))
{
    $error = true;
}
else 
{
    if($posts['count'] > (count($posts) - 1) + ($pageNumber - 1) * 5)
    {
        $remainingPages = true;
    }
}

?>


<body>
    <header class="masthead" style="background-image: url('assets/img/home-bg.jpg')">
        <div class="container position-relative px-4 px-lg-5">
            <div class="row gx-4 gx-lg-5 justify-content-center">
                <div class="col-md-10 col-lg-8 col-xl-7">
                    <div class="site-heading">
                        <h1>Clean Blog</h1>
                        <span class="subheading">A Blog Website by Hussein Medhat</span>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container px-4 px-lg-5">
        <div class="row gx-4 gx-lg-5 justify-content-center">
            <div class="col-md-10 col-lg-8 col-xl-7">
                <?php if($error === true) : ?>
                <div class="alert alert-danger" role="alert">
                    <?php foreach($posts as $post) {echo $post; }  ?>
                </div>
                <?php endif ?>
                <!-- Search form-->
                <form class="mb-4" method="GET">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search for posts..." name="search">
                        <button class="btn btn-primary" type="submit">Search</button>
                    </div>
                </form>
                <!-- Post preview-->
                <?php foreach ($posts as $post) { ?>
                <?php if (!isset($post['id'])) 
                    continue;
                 ?>
                <div class="post-preview">
                    <a href=<?= "pages/post.php?id=". $post['id'] ?>>
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
                <?php if($search === "") : ?>
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
                <?php else : ?>
                <div class="d-flex justify-content-end mb-4">
                    <?php if($pageNumber > 1) : ?>
                    <a class="btn btn-primary text-uppercase" style=" margin-right:320px"
                        href="?page=<?= $pageNumber - 1 ?>&search=<?= $search ?>">←
                        Newer
                        Posts
                    </a>
                    <?php endif ?>
                    <?php if($remainingPages === true) : ?>
                    <a class="btn btn-primary text-uppercase"
                        href="?page=<?= $pageNumber + 1 ?>&search=<?= $search ?>">Older
                        Posts
                        →</a>
                    <?php endif ?>
                </div>
                <?php endif ?>


            </div>
        </div>
    </div>



    <?php
include('includes/footer.php')
?>