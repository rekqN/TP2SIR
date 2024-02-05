<?php
session_start();

require_once __DIR__ . '/../../repositories/userRepository.php';
require_once __DIR__ . '/../../validations/app/validate-sign-up.php';

if (isset($_POST['user'])) {
    if ($_POST['user'] == 'signUp') {
        signUp($_POST);
    }
}

function signUp($req)
{
    $data = isSignUpValid($req);

    if (isset($data['invalid'])) {
        $_SESSION['errors'] = $data['invalid'];
        $params = '?' . http_build_query($req);
        header('location: /projeto_sir/pages/public/signup.php' . $params);
    } else {
        $user = registerUser($data);

        if ($user) {
            if (!$user['deletedAt']) {
                $_SESSION['userID'] = $user['userID'];
                $_SESSION['firstName'] = $user['firstName'];

                setcookie("userID", $data['userID'], time() + (60 * 60 * 24 * 30), "/");
                setcookie("firstName", $data['firstName'], time() + (60 * 60 * 24 * 30), "/");
                header('location: /projeto_sir/pages/secure/userDashboard.php');
            }
        }
    }
}
