<?php
require_once 'app/helpers.php';
session_start();
$page_title = 'Home Page';


?>

<?php include 'tpl/header.php'; ?>
<main class="min-height-900">
    <div class="container">
        <section id="header-content">
            <div class="row">
                <div class="col-12 text-center mt-3">
                    <h1 class="display-4">Welcome to Food Blog!</h1>
                    <p>Here you can share recipes and tips</p>
                    <p class="mt-4">
                        <a href="signup.php" class="btn btn-outline-warning btn-lg">Start Now!</a>
                    </p>
                </div>
            </div>
        </section>
        <section id="main-content">
            <div class="row">
                <div class="col-4">
                    <div class="card text-left">
                        <img class="card-img-top " src="images/pancakes-2291908_960_720.jpg" alt="">
                        <div class="card-body">
                            <h5 class="card-title">you can see all types of recipes</h5>
                        </div>
                    </div>
                </div>
                <div class="col-4"></div>
                <div class="col-4">
                    <div class="card text-left">
                        <img class="card-img-top" src="images/pizza-1442946_640.jpg" alt="">
                        <div class="card-body">
                            <h5 class="card-title">you can share other people tips</h5>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</main>
<?php include 'tpl/footer.php'; ?>