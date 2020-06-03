<?php
require_once './app/helpers.php';
session_start();

if (!user_auth()) {

    header('location: signin.php');
    exit;
}

$error = [
    'title' => '',
    'article' => '',
];

$page_title = 'Add Post Form';

if (isset($_POST['submit'])) {
    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
    $title = trim($title);
    $article = filter_input(INPUT_POST, 'article', FILTER_SANITIZE_STRING);
    $article = trim($article);
    $form_valid = true;

    if (!$title || mb_strlen($title) < 2) {
        $error['title'] = '* Title is required for min 2 chars';
        $form_valid = false;
    }

    if (!$article || mb_strlen($article) < 2) {
        $error['article'] = '* Article is required for min 2 chars';
        $form_valid = false;
    }

    if ($form_valid) {
        $uid = $_SESSION['user_id'];
        $link = mysqli_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PWD, MYSQL_DB);

        $title = mysqli_real_escape_string($link, $title);

        $article = mysqli_real_escape_string($link, $article);


        $sql = "INSERT INTO posts VALUES(null,$uid,'$title','$article',NOW())";
        $result = mysqli_query($link, $sql);

        if ($result && mysqli_affected_rows($link) > 0) {
            header("location:blog.php");
            exit;
        }
    }
}

?>

<?php include 'tpl/header.php'; ?>
<main class="min-height-900">
    <div class="container">
        <section id="header-content">
            <div class="row">
                <div class="col-12 mt-3">
                    <h1 class="display-4">Add Post Form</h1>
                    <p>here you can post your digg</p>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <form action="" method="POST" autocomplete="off" novalidate='novalidate'>
                        <div class="form-group">
                            <label for="title">*Title:</label>
                            <input value='<?= old('title'); ?>' class="form-control" type="text" name="title"
                                id="title">
                            <span class="text-danger"><?= $error['title']; ?></span>
                        </div>
                        <div class="form-group">
                            <label for="article">*article:</label>
                            <textarea class='form-control' name="article" id="article" cols="30"
                                rows="10"><?= old('article'); ?></textarea>
                            <span class="text-danger"><?= $error['article']; ?></span>

                        </div>
                        <input class="btn btn-primary" type="submit" value="Save Post" name="submit">
                        <a class="btn btn-secondary" href="blog.php"> Cancel</a>
                    </form>
                </div>
            </div>
        </section>
        <section id="main-content"></section>
    </div>
</main>
<?php include 'tpl/footer.php'; ?>