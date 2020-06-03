<?php



require_once 'app/helpers.php';
session_start();

if (isset($_SESSION['user_id'])) {

  header('location: ./');
  exit;
}

$page_title = 'Sign in Page';
$error = '';

// if client click on button
if (isset($_POST['submit'])) {

  if (
    isset($_SESSION['csrf_token']) &&
    isset($_POST['csrf_token']) &&
    $_SESSION['csrf_token'] == $_POST['csrf_token']
  ) {
    // collect data from form to simple variables
    $email = !empty($_POST['email']) ? trim($_POST['email']) : '';
    $password = !empty($_POST['password']) ? trim($_POST['password']) : '';

    // validation
    if (!$email) {

      $error = '* A valid email is required';
    } elseif (!$password) {
      $error = '* Password is required';
    } else {

      $link = mysqli_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PWD, MYSQL_DB);

      $email = mysqli_real_escape_string($link, $email);
      $password = mysqli_real_escape_string($link, $password);

      $sql = "SELECT u.*,up.profile_image FROM users u  
      JOIN users_profile up ON u.id = up.user_id 
       WHERE email = '$email' LIMIT 1";
      $result = mysqli_query($link, $sql);

      if ($result && mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);



        if (password_verify($password, $user['password'])) {
          $_SESSION['user_ip'] = $_SERVER['REMOTE_ADDR'];
          $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
          $_SESSION['user_id'] = $user['id'];
          $_SESSION['user_name'] = $user['name'];
          $_SESSION['user_image'] = $user['profile_image'];

          header('location: blog.php');
          exit;
        } else {
          $error = '* Wrong email/password combination';
        }
      } else {

        $error = '* Wrong email/password combination';
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
                    <h1 class="display-4">Sign in Page</h1>
                    <p>
                        Here you can signin with your account,
                        <a href="signup.php">Open new account</a>
                    </p>
                </div>
            </div>

        </section>
        <section id="signin-form-content">
            <div class="row">
                <div class="col-lg-6">
                    <form action="" method="POST" autocomplete="off" novalidate="novalidate">
                        <input type="hidden" name="csrf_token" value="<?= $token ?>">
                        <div class="form-group">
                            <label for="email">* Email</label>
                            <input value="<?= old('email'); ?>" type="email" name="email" id="email"
                                class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="password">* Password</label>
                            <input type="password" name="password" id="password" class="form-control">
                        </div>
                        <input type="submit" value="Signin" name="submit" class="btn btn-primary">
                        <span class="text-danger"><?= $error; ?></span>
                    </form>
                </div>
            </div>
        </section>
    </div>
</main>
<?php include 'tpl/footer.php'; ?>