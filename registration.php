<?php
session_start();
include('includes/header.php');

if(isset($_SESSION['user_id'])) {
    header('Location: http://localhost/homeschool_helper/');
}
?>

    <form onsubmit="submit_registration(); return false;" method="post" name="registration_form">
        <input id="first_name_input" type="text" placeholder="First Name" name="user_first_name" required>
        <input id="last_name_input" type="text" placeholder="Last Name" name="user_last_name" required>
        <input id="email_input" type="email" placeholder="johndoe@example.com" name="user_email" required>
        <input id="password_input" type="password" placeholder="password" name="user_password" minlength="8" maxlength="50" required>
        <select id="question_input" name="user_question">
            <option>What is your mother's maiden name?</option>
            <option>What was your high-school mascot?</option>
            <option>Where did you go on your honeymoon?</option>
            <option>What was the model of your first car?</option>
            <option>What was the name of your childhood best-friend?</option>
            <option>What was the name of your first pet?</option>
        </select>
        <input id="answer_input" type="text" placeholder="Answer" name="user_answer" required>
        <input type="submit" name="submit" value="SUBMIT">
    </form>

    <div id="message"></div>

<?php include_once('includes/footer.php'); ?>