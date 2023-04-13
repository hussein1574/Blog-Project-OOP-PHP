<!-- Navigation-->
<nav class="navbar navbar-expand-lg navbar-light" id="mainNav">
    <div class="container px-4 px-lg-5">
        <a class="navbar-brand" href="/Blogs/index.php">Clean Blog</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive"
            aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            Menu
            <i class="fas fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ms-auto py-4 py-lg-0">
                <li class="nav-item"><a class="nav-link px-lg-3 py-3 py-lg-4" href="/Blogs/index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link px-lg-3 py-3 py-lg-4" href="">About</a></li>
                <?php
                    if (!isset($_SESSION['user_id'])) {
                    ?>
                <li class="nav-item"><a class="nav-link px-lg-3 py-3 py-lg-4" href="/Blogs/pages/login.php">LOGIN</a>
                </li>
                <?php } else { ?>
                <li class="nav-item"><a class="nav-link px-lg-3 py-3 py-lg-4" href="/Blogs/pages/my_blogs.php">My
                        Blogs</a></li>
                <li class="nav-item"><a class="nav-link px-lg-3 py-3 py-lg-4" href="/Blogs/pages/add_post.php">Add
                        Post</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link px-lg-3 py-3 py-lg-4 dropdown-toggle" href="#" id="navbarDropdownMenuLink"
                        role="button" data-bs-toggle="dropdown" aria-expanded="false"><?= $_SESSION['username'] ?></a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                        <li><a class="dropdown-item" href="/Blogs/pages/profile.php">Profile</a></li>
                        <li><a class="dropdown-item" href="/Blogs/pages/logout.php">Logout</a></li>
                    </ul>
                </li>
                <?php } ?>

            </ul>
        </div>
    </div>
</nav>