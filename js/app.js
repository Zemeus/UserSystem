function ajax_request(METHOD, URL, QUERY_STRING, CALLBACK) {
    const ajax = new XMLHttpRequest();

    ajax.onreadystatechange = function() {
        if(this.status === 200 && this.readyState === 4) {
            /* SUCCESSFUL RESPONSE */
            CALLBACK(JSON.parse(this.responseText));
        }
    };

    ajax.open(METHOD, URL, true);
    ajax.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    ajax.send(QUERY_STRING);
}

function submit_registration() {
    const SUBMIT_URL = "http://localhost/homeschool_helper/backend/register.php";
    const first_name = document.querySelector('#first_name_input');
    const last_name = document.querySelector('#last_name_input');
    const email = document.querySelector('#email_input');
    const password = document.querySelector('#password_input');
    const question = document.querySelector('#question_input');
    const answer = document.querySelector('#answer_input');
    const query = "user_first_name=" + first_name.value
                    + "&user_last_name=" + last_name.value
                    + "&user_email=" + email.value
                    + "&user_password=" + password.value
                    + "&user_question=" + question.value
                    + "&user_answer=" + answer.value;

    ajax_request('POST', SUBMIT_URL, query, registered);

}

function submit_login() {
    const SUBMIT_URL = "http://localhost/homeschool_helper/backend/authenticate.php";
    const email_input = document.querySelector('#email_input');
    const password_input = document.querySelector('#password_input');
    const email = email_input.value;
    const password = password_input.value;

    const query = 'user_email=' +  email + '&user_password=' + password;

    ajax_request('POST', SUBMIT_URL, query, authenticated);
}

function destroy_session() {
    const SUBMIT_URL = "http://localhost/homeschool_helper/backend/logout.php";
    ajax_request('POST', SUBMIT_URL, '');
    location.replace('http://localhost/homeschool_helper/');
}

function authenticated(response) {
    const message = document.querySelector('#message');
    if(response.success) {
        location.replace('http://localhost/homeschool_helper/');
    } else {
        message.innerHTML = response.message;
    }
}

function registered(response) {
    const message = document.querySelector('#message');

    if(response.success) {
        location.replace('http://localhost/homeschool_helper/registration_success.php');
    } else {
        message.innerHTML = "An internal error has occurred.";
    }
}