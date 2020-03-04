<?php
    session_start();
    include('includes/header.php');
    if(isset($_SESSION['user_id'])) {
        header('Location: http://localhost/homeschool_helper/');
    }
?>

<form onsubmit="submit_login(); return false;">
    <input id="email_input" type="email" name="user_email" placeholder="johndoe@example.com" required>
    <input id="password_input" type="password" name="user_password" placeholder="password" required>
    <input type="submit" value="LOGIN">
</form>
<br>
<a href="http://localhost/homeschool_helper/forgot_password.php">Forgot Password</a>
<div id="message"></div>
<?php
    include('includes/footer.php');
?>