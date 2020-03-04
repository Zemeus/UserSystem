<?php
session_start();
include('includes/header.php');
$logged_in = false;
$name = '';
if(isset($_SESSION['user_id']) and isset($_SESSION['user_first_name'])) {
    $logged_in = true;
    $name = $_SESSION['user_first_name'];
}
?>




<!-- USER IS LOGGED IN -->
<?php if($logged_in): ?>
    <h1> Hello, <?php echo $name;?></h1>
    <button onclick = "destroy_session()">LOGOUT</button>
<?php else: ?>
    <a href="login.php">Login</a>
    <a href="registration.php">Register</a>
<?php endif ?>

<?php
    include_once('includes/footer.php');
?>