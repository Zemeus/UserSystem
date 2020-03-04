<?php
session_start();
include('../includes/db_connection.php');

$message = [
    'success'           => false,
    'message'           => ''
];

if($_SERVER['REQUEST_METHOD'] == 'POST') {

    if(filter_has_var(INPUT_POST, 'user_email')
        and filter_has_var(INPUT_POST, 'user_password')) {

        $email = filter_input(INPUT_POST, 'user_email', FILTER_VALIDATE_EMAIL);
        $password = $_POST['user_password'];

        $connection = get_connection();

        $query = $connection->prepare("SELECT id, first_name, password FROM hh_users WHERE email = :email");
        $query->bindParam(':email', $email);
        $query->execute();
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $result = $query->fetchAll();

        if(empty($result)) {
            /* NOT REGISTERED */
                $message = [
                    'success'           => false,
                    'message'           => "Email is not registered"
                ];
        }
        else if(password_verify($password, $result[0]['password'])) {
            /* USER AUTHENTICATED */
            $_SESSION['user_id'] = $result[0]['id'];
            $_SESSION['user_first_name'] = $result[0]['first_name'];
            $message = [
                'success'           => true,
                'message'           => "User is logged in."
            ];
        } else {
            /* WRONG PASSWORD */
            $message = [
                'success'           => false,
                'message'           => "Password is incorrect."
            ];
        }

    }
}

echo json_encode($message);