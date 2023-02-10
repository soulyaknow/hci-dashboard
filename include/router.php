<?php
session_start();
require_once('backend.php');
if (isset($_POST['choice'])) {
    switch ($_POST['choice']) {
        case 'login':
            $backend = new backend();
            echo $backend->doLogin(
                $_POST['user_email'],
                $_POST['user_password']
            );
            break;
            
        case 'register':
            $backend = new backend();
            echo $backend->doRegister(
                $_POST['username'],
                $_POST['email'],
                $_POST['password'],
                $_POST['cpassword'],
            );
            break;
                
        case 'logout':
            session_unset();
            session_destroy();
            echo "200";
            break;
            
        default:
            # code...
            break;
    }
}
?>