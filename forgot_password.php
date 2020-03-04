<?php
    session_start();
    include('includes/header.php');
    require('includes/db_connection.php');

if($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['user_recovery_password']) and isset($_SESSION['user_recovery_id'])) {
        /* ANSWER AUTHENTICATED AND RECEIVED NEW PASSWORD */
        set_new_password();
    }

    elseif (isset($_SESSION['user_recovery_id']) and isset($_POST['user_recovery_answer'])) {
        /* EMAIL AUTHENTICATED, RECEIVED ANSWER TO SECURITY QUESTION */
        authenticate_answer();
    }

    elseif (isset($_POST['user_recovery_email'])) {
        /* RECEIVED EMAIL FROM USER */
        authenticate_email();
    }

    /* RELEASE PDO CONNECTION */
    $connection = null;

} else {
    /* FIRST STEP, REQUEST USER EMAIL */
    show_form('email');
}

function show_form($type) {
     echo
        '<form id="recovery_form" action="' . $_SERVER['PHP_SELF'] . '" method="post">';

     if($type == 'email') {
     echo
            '<label for="user_recovery_email">Email:</label>' .
            '<input id="recovery_email_input" type="email" name="user_recovery_email" placeholder="johndoe@example.com" required>';
     }
     elseif ($type == 'question' and isset($_SESSION['user_question'])) {
     echo
            '<h5>' . $_SESSION['user_question'] .'</h5>' .
            '<label for="user_recovery_answer">Answer:</label>' .
            '<input id="recovery_answer_input" type="text" name="user_recovery_answer" placeholder="Answer." required>';
     }
     elseif ($type == 'password') {
     echo
            '<label for="user_recovery_password">New Password:</label>' .
            '<input id="password_input" type="password" placeholder="password" name="user_recovery_password" minlength="8" maxlength="50" required>';
     }

     echo
            '<input type="submit" value="Submit">' .
        '</form>';
}

function authenticate_email() {
    $email = filter_input(INPUT_POST, 'user_recovery_email', FILTER_VALIDATE_EMAIL);

    $connection = get_connection();

    $query = $connection->prepare('SELECT ID, question FROM hh_users WHERE email = :email');
    $query->bindParam(':email', $email);
    $query->execute();
    $query->setFetchMode(PDO::FETCH_ASSOC);
    $result = $query->fetch();

    if (!empty($result)) {
        /* EMAIL IS VALID */
        $_SESSION['user_recovery_id'] = $result['ID'];
        $_SESSION['user_question'] = $result['question'];
        show_form('question');
    } else {
        /* EMAIL NOT VALID */
        show_form('email');
        echo 'Email is not registered';
    }
}

function authenticate_answer() {
    $ID = $_SESSION['user_recovery_id'];
    $answer = strtoupper($_POST['user_recovery_answer']);
    $connection = get_connection();

    $query = $connection->prepare('SELECT answer FROM hh_users WHERE ID = :id');
    $query->bindParam(':id', $ID);
    $query->execute();
    $query->setFetchMode(PDO::FETCH_ASSOC);
    $result = $query->fetch();

    if(!empty($result) and password_verify($answer, $result['answer'])){
        /* ANSWER WAS AUTHENTICATED */
       show_form('password');

    } else {
        /* ANSWER INCORRECT */
        show_form('question');
        echo "The answer provided was incorrect. Please try again.";
    }
}

function set_new_password() {
    /* ALL VALIDATED */
    $password = password_hash($_POST['user_recovery_password'], PASSWORD_BCRYPT );
    $ID = $_SESSION['user_recovery_id'];


    $connection = get_connection();
    $query = $connection->prepare('UPDATE hh_users SET password = :password WHERE ID = :id');
    $query->bindParam(':password', $password);
    $query->bindParam(':id', $ID);

    if($query->execute()) {
        /* PASSWORD CHANGED SUCCESSFULLY, DESTROY SESSION AND REDIRECT */
        setcookie(session_name(), '', 100);
        session_unset();
        session_destroy();
        header('Location: http://localhost/homeschool_helper/reset_success.php');
    }
}

include('includes/footer.php');
?>
