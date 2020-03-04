<?php
    require_once('../includes/db_connection.php');

    /* RESPONSE MESSAGE */
    $message = [
            'success'         => false,
            'message'         => ''
        ];

    /* POST REQUEST RECEIVED */
    if($_SERVER['REQUEST_METHOD'] === "POST") {

        if( form_input_exists() ) {

            /* FILTER INPUT */
            $first_name = filter_input(INPUT_POST, 'user_first_name', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
            $last_name = filter_input(INPUT_POST, 'user_last_name', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
            $password = password_hash($_POST['user_password'], PASSWORD_BCRYPT );
            $email = filter_input(INPUT_POST, 'user_email', FILTER_VALIDATE_EMAIL);
            $question = filter_input(INPUT_POST, 'user_question', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
            $answer = password_hash(strtoupper($_POST['user_answer']), PASSWORD_BCRYPT );

            /* ALL VALIDATED, CHECK IF ALREADY REGISTERED */
            $connection = get_connection();
            $query = $connection->prepare("SELECT * FROM hh_users WHERE email = :email");
            $query->bindParam(':email', $email);
            $query->execute();
            $query->setFetchMode(PDO::FETCH_ASSOC);
            $result = $query->fetchAll();

            /* IF NOT ALREADY REGISTERED, EXECUTE REGISTRATION */
            if(empty($result)) {
                $query = $connection->prepare("INSERT INTO hh_users (first_name, last_name, password, email, question, answer) 
                                                         VALUES (:first_name, :last_name, :password, :email, :question, :answer)");
                $query->bindParam(":first_name", $first_name);
                $query->bindParam(":last_name", $last_name);
                $query->bindParam(":password", $password);
                $query->bindParam(":email", $email);
                $query->bindParam(":question", $question);
                $query->bindParam(":answer", $answer);

                if($query->execute()) {
                    /* SUCCESSFUL REGISTRATION */
                    $message = [
                        'success'     => true,
                        'message'     => 'Registration successful.'
                    ];
                } else {
                    /* DB ERROR */
                    $message = [
                        'success'     => false,
                        'message'     => 'There was an error submitting your registration, please try again.'
                    ];
                }

            } else {
                /* ALREADY REGISTERED */
                $message = [
                        'success'     => false,
                        'message'     => 'This email has already been registered. Please try to login.'
                    ];
            }

            /* RELEASE PDO CONNECTION */
            $connection =  null;

        } else {
            if(isset($_POST['user_password']) and strlen($_POST['user_password']) < 8) {
                /* PASSWORD TOO SHORT */
                $message = [
                    'success'         => false,
                    'message'         => 'Password must be at least 8 characters long.'
                ];
            } else {
                /* MISSING PARAMETER IN POST REQUEST */
                $message = [
                    'success'         => false,
                    'message'         => 'All fields must be filled out to complete registration.'
                ];
            }
        }

    }

    echo json_encode($message);

    function form_input_exists() {
        /* IF ALL INPUTS EXIST & PASSWORD >= 8 CHARS */
        return (filter_has_var(INPUT_POST, 'user_first_name')
            and filter_has_var(INPUT_POST, 'user_last_name')
            and filter_has_var(INPUT_POST, 'user_password')
            and filter_has_var(INPUT_POST, 'user_email')
            and strlen($_POST['user_password']) >= 8);
    }
