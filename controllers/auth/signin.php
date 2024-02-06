<?php
session_start();
require_once __DIR__ . '/../../repositories/userRepository.php';
require_once __DIR__ . '/../../validations/app/validate-login-password.php';

if (isset($_POST['user'])) {
    if ($_POST['user'] == 'login') {
        login($_POST);
    }

    if ($_POST['user'] == 'logout') {
        logout();
    }

    if ($_POST['user'] == 'delete') {
        softDelete();
    }
}

function login($req)
{
    $data = isLoginValid($req);
    $valid = checkErrors($data, $req);

    if ($valid) {
        $data = isPasswordValid($data);
    }

    $user = checkErrors($data, $req);

    if ($user) {
        doLogin($data);
    }
}

function checkErrors($data, $req)
{
    if (isset($data['invalid'])) {
        $_SESSION['errors'] = $data['invalid'];
        $params = '?' . http_build_query($req);
        header('location: /projeto_sir/pages/public/signin.php' . $params);
        return false;
    }

    unset($_SESSION['errors']);
    return true;
}

function doLogin($data)
{
    $_SESSION['userID'] = $data['userID'];
    $_SESSION['firstName'] = $data['firstName'];

    setcookie("userID", $data['userID'], time() + (60 * 60 * 24 * 30), "/");
    setcookie("firstName", $data['firstName'], time() + (60 * 60 * 24 * 30), "/");

    $home_url = 'http://' . $_SERVER['HTTP_HOST'] . '/projeto_sir/pages/secure/userDashboard.php';
    header('Location: ' . $home_url);
}

function logout()
{
    if (isset($_SESSION['userID'])) {

        $_SESSION = array();

        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600);
        }
        session_destroy();
    }

    setcookie('userID', '', time() - 3600, "/");
    setcookie('firstName', '', time() - 3600, "/");

    $home_url = 'http://' . $_SERVER['HTTP_HOST'] . '/projeto_sir/landingPage';
    header('Location: ' . $home_url);
}

function softDelete()
{
    if (!isset($_SESSION['userID'])) {
        echo '!! User ID NOT SET in the session !!';
        exit();
    }

    $user = [
        'userID' => $_SESSION['userID'],
    ];

    $deleteSuccess = deleteUser($user['userID']);

    if ($deleteSuccess) {
        session_unset();
        session_destroy();

        setcookie(session_name(), '', time() - 3600);
        setcookie('userID', '', time() - 3600, "/");
        setcookie('firstName', '', time() - 3600, "/");

        $home_url = 'http://' . $_SERVER['HTTP_HOST'] . '/projeto_sir/landingPage';
        header('Location: ' . $home_url);
        exit();
    } else {
        echo '!! ERROR deleting account !!';
    }
}
