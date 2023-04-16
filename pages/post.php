<?php
include_once '../config/database.php';
include_once '../Classes/post.php';
include_once '../Classes/comment.php';
session_start();
include('../includes/header.php');
include('../includes/navbar.php');

$postobj = new POST($pdo);
$commentobj = new Comment($pdo);
$postId = $_GET['id'];

if(isset($_POST['submit_comment']))
{
    echo $_SESSION['user_id'];
    $result = $commentobj->createComment($_POST['content'], $postId, $_SESSION['user_id']);
    if($result === true)
        header('Location: /blogs/pages/post.php?id='.$postId);
    else
        header('Location: /blogs/pages/page_not_found.php');
}


if (isset($_GET['id'])) {
    if ($postobj->readPost($postId)) {
        $post = $postobj->readPost($postId);
    }else{
        header('Location: /blogs/pages/page_not_found.php');
    }
    if ($commentobj->readAllComments($postId)) {
        $comments = $commentobj->readAllComments($postId);
    }
}

if (isset($_POST['delete_post'])) {
    if ($postobj->deletePost($postId)) {
        header('Location: /blogs/index.php');
    }
}

?>


<!-- Page Header-->
<header class="masthead" style="background-image: url('../assets/img/post-bg.jpg')">
    <div class="container position-relative px-4 px-lg-5">
        <?php if (isset($_SESSION['user_id'])) {?>
        <?php if($_SESSION['user_id']==$post['user_id']) {?>
        <div class="row">
            <div class="col">
                <a href=<?= "edit_post.php?id=" . $postId ?> class=" btn" type="submit"><i
                        class="fas fa-edit text-white fs-3"></i></a>
            </div>
            <div class="col">
                <form method="post">
                    <button class="btn" type="submit" name="delete_post"><i
                            class="fas fa-trash text-white fs-3"></i></button>
                </form>
            </div>
        </div>
        <?php }?>
        <?php }?>

        <div class="row gx-4 gx-lg-5 justify-content-center">
            <div class="post-heading">
                <h2>
                    <?php
                    if (isset($post['title'])) :
                        echo $post['title'];
                    endif
                    ?>
                </h2>

                <span class="meta">

                    Posted by
                    <a href="#!">
                        <?php
                        if (isset($post['username'])) :
                            echo $post['username'];
                        endif
                        ?>
                    </a>
                    <?php
                    if (isset($post['created_at'])) :
                        echo $post['created_at'];
                    endif
                    ?>
                </span>
            </div>
        </div>
    </div>
    </div>
</header>
<!-- Post Content-->
<article class="mb-4">
    <div class="container px-4 px-lg-5">
        <div class="row gx-4 gx-lg-5 justify-content-center">
            <div class="col-md-10 col-lg-8 col-xl-7">
                <p>
                    <?php
                    if ($post) :
                        echo $post['content'];
                    endif
                    ?>
                </p>
            </div>
        </div>
    </div>
</article>
<!-- Comments Section-->
<section class="comments-section">
    <div class="container px-4 px-lg-5">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-8 col-xl-7">
                <h3>Comments</h3>
                <ul class="comments-list">
                    <?php if (!empty($comments)) : ?>
                    <?php
                    foreach ($comments as $comment) :
                    ?>
                    <li>
                        <div class="comment-meta">
                            <span class="comment-author">
                                <?php echo $comment['username']; ?>
                            </span>
                            <span class="comment-date">
                                <?php echo $comment['created_at']; ?>
                            </span>
                        </div>
                        <div class="comment-content">
                            <?php echo "&ensp;" . $comment['content']; ?>
                        </div>
                    </li>
                    <?php endforeach; ?>
                    <?php else : ?>
                    <p>No comments yet</p>
                    <?php endif; ?>
                </ul>

                <?php if (isset($_SESSION['user_id'])) : ?>
                <form method="post">
                    <div class="form-group">
                        <label for="content">Leave a comment:</label>
                        <textarea class="form-control" id="content" name="content" rows="3"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary" name="submit_comment">Submit</button>
                </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php
include('../includes/footer.php');
?>