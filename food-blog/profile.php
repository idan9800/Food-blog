<?php

require_once 'app/helpers.php';
session_start();


if (!user_auth()) {

    header('location: signin.php');
    exit;
}

$uid = $_SESSION['user_id'];
$link = mysqli_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PWD, MYSQL_DB);
$sql = "SELECT users.*,users_profile.profile_image FROM users
JOIN users_profile ON users.id = users_profile.user_id
WHERE users.id = $uid";
$result = mysqli_query($link, $sql);
$user =  mysqli_fetch_assoc($result);



$page_title = 'Profile Update Page';
$errors = [
    'name' => '',
    'email' => '',
    'password' => '',
    'submit' => '',
];


// if client click on button
if (isset($_POST['submit'])) {


    if (
        isset($_SESSION['csrf_token']) &&
        isset($_POST['csrf_token']) &&
        $_SESSION['csrf_token'] == $_POST['csrf_token']
    ) {

        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
        $name = mysqli_real_escape_string($link, $name);
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $email = mysqli_real_escape_string($link, $email);
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
        $password = mysqli_real_escape_string($link, $password);
        $form_valid = true;
        $profile_image = $user['profile_image'];
        define('MAX_FILE_SIZE', 1024 * 1024 * 5);



        if (!$name || mb_strlen($name) < 2 || mb_strlen($name) > 70) {
            $errors['name'] = '* Name is required for min 2 chars and max 70';
            $form_valid = false;
        }

        if (!$email) {
            $errors['email'] = '* A valid email is required';
            $form_valid = false;
        } elseif (email_exist($link, $email)) {
            $errors['email'] = '* Email is taken';
            $form_valid = false;
        }

        if (email_exist($link, $email) && $user['id'] == $_SESSION['user_id']) {
            $form_valid = true;
            $errors['email'] = '';
        }


        if (!$password || strlen($password) < 6 || strlen($password) > 20) {
            $errors['password'] = '* Passwrod is required for min 6 chars and max 70';
            $form_valid = false;
        }

        if (isset($_FILES['image']['error']) && $_FILES['image']['error'] == 0) {
            if (isset($_FILES['image']['error']) && $_FILES['image']['error'] <= MAX_FILE_SIZE) {
                if (isset($_FILES['image']['name'])) {

                    $allowed_ex = ['jpg', 'jpeg', 'png', 'gif', 'bmp'];
                    $details = pathinfo($_FILES['image']['name']);

                    if (in_array(strtolower($details['extension']), $allowed_ex)) {

                        if (isset($_FILES['image']['tmp_name']) && is_uploaded_file($_FILES['image']['tmp_name'])) {
                            $profile_image = date('Y.m.d.H.i.s') . '-' . $_FILES['image']['name'];
                            move_uploaded_file($_FILES['image']['tmp_name'], 'images/' . $profile_image);
                        }
                    }
                }
            }
        };

        if ($form_valid) {
            $password = password_hash($password, PASSWORD_BCRYPT);
            $sql = "UPDATE users
            SET name = '$name', password = '$password',email = '$email'
            WHERE users.id = $uid";
            $result = mysqli_query($link, $sql);

            if ($result && mysqli_affected_rows($link) > 0) {
                $sql = "UPDATE users_profile 
                SET profile_image = '$profile_image'
                WHERE users_profile.user_id = $uid";
                $result = mysqli_query($link, $sql);

                $_SESSION['user_ip'] = $_SERVER['REMOTE_ADDR'];
                $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
                $_SESSION['user_id'] = $uid;
                $_SESSION['user_name'] = $name;
                $_SESSION['user_image'] = $profile_image;
                header('location: blog.php');
                exit;
            }
        }
    }
    $token = csrf();
} else {
    $token = csrf();
}



?>

<?php include 'tpl/header.php'; ?>
<main class="min-height-900">
    <div class="container">
        <section id="header-content">
            <div class="row">
                <div class="col-12 mt-3">
                    <h1 class="display-4">Update Your Profile </h1>

                </div>
            </div>

        </section>
        <section id="signin-form-content">
            <div class="row">
                <div class="col-lg-6">
                    <form enctype="multipart/form-data" action="" method="POST" autocomplete="off"
                        novalidate="novalidate">
                        <input type="hidden" name="csrf_token" value="<?= $token ?>">
                        <div class="form-group">
                            <label for="name">* Name</label>
                            <input value="<?= $user['name']; ?>" type="text" name="name" id="name" class="form-control">
                            <span class="text-danger"><?= $errors['name']; ?></span>
                        </div>
                        <div class="form-group">
                            <label for="email">* Email</label>
                            <input value="<?= $user['email']; ?>" type="email" name="email" id="email"
                                class="form-control">
                            <span class="text-danger"><?= $errors['email']; ?></span>

                        </div>

                        <div class="form-group">
                            <label for="password">* Password</label>
                            <input type="password" name="password" id="password" class="form-control">
                            <span class="text-danger"><?= $errors['password']; ?></span>

                        </div>

                        <div class="form-group">
                            <label for="image">Profile Image:</label>
                        </div>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="inputGroupFileAddon01">Upload</span>
                            </div>
                            <div class="custom-file">
                                <input value="<?= $user['profile_image']; ?>" type="file" name="image"
                                    class="custom-file-input" id="inputGroupFile01"
                                    aria-describedby="inputGroupFileAddon01">
                                <label class="custom-file-label" for="inputGroupFile01">
                                    <?= $user['profile_image']; ?>
                                </label>
                            </div>
                        </div>
                        <input type="submit" value="Update Profile" name="submit" class="btn btn-primary">

                        <a class="btn btn-secondary float-right" href="blog.php">Back</a>

                    </form>
                </div>
            </div>
        </section>
    </div>
</main>
<?php include 'tpl/footer.php'; ?>