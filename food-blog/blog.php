<?php
require_once 'app/helpers.php';
session_start();


$page_title = 'Blog Page';

$link = mysqli_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PWD, MYSQL_DB);

mysqli_query($link, "SET NAMES utf8");

$sql = "SELECT u.name,up.profile_image,p.*,DATE_FORMAT(p.date, '%d/%m/%Y %H:%i:%s') pdate FROM posts p
JOIN users u ON u.id = p.user_id 
JOIN users_profile up ON u.id = up.user_id  
ORDER BY p.date DESC";

$result = mysqli_query($link, $sql);

?>

<?php include 'tpl/header.php'; ?>
<main class="min-height-900">
    <div class="container">
        <section id="header-content">
            <div class="row">
                <div class="col-12 mt-3">
                    <h1 class="display-4">Blog</h1>
                    <p>The blog page.</p>
                    <p>
                        <?php if (user_auth()) : ?>
                        <a class="btn btn-primary" href="add_post.php">
                            <i class="fas fa-plus-circle"></i>
                            Add Post
                        </a>
                        <?php else : ?>
                        <a href="signup.php">Create your account and start</a>
                        <?php endif; ?>
                    </p>
                </div>
            </div>
        </section>

        <?php if ($result && mysqli_num_rows($result) > 0) : ?>
        <section id="main-content">
            <div class="post">
                <?php while ($post = mysqli_fetch_assoc($result)) : ?>
                <div class="col-12 mb-3">
                    <div class="card">
                        <div class="card-header">
                            <img class="rounded-circle mr-2" width="40" height="40"
                                src="images/<?= $post['profile_image'] ?>" alt="">
                            <span><?= $post['name']; ?></span>
                            <span class="float-right"><?= $post['pdate']; ?></span>
                        </div>
                        <div class="card-body">
                            <h3 class="card-title"><?= $post['title']; ?></h3>
                            <p class="card-text">
                                <?= str_replace("\n", '<br>', $post['article']); ?>
                            </p>
                            <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $post['user_id']) : ?>
                            <div class="float-right mr-5">
                                <div class="dropdown">
                                    <a class="dropdown-toggle-no-arrow dropdown-toggle text-decoration-none" href="#"
                                        id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false">
                                        <i class="fas fa-ellipsis-h"></i>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                        <a class="dropdown-item" href="edit_post.php?pid=<?= $post['id'] ?>"><i
                                                class="fas fa-edit"></i>
                                            Edit</a>
                                        <a class="delete-post-btn dropdown-item"
                                            href="delete_post.php?pid=<?= $post['id'] ?>"><i class="fas fa-eraser"></i>
                                            Delete</a>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </section>
        <?php endif; ?>
    </div>
</main>
<?php include 'tpl/footer.php'; ?>