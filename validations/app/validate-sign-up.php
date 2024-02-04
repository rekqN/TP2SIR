<?php

function isSignUpValid($req)
{
    foreach ($req as $key => $value) {
        $req[$key] =  trim($req[$key]);
    }

    if (empty($req['firstName']) || strlen($req['firstName']) < 3 || strlen($req['firstName']) > 255) {
        $errors['firstName'] = '!! The First Name field cannot be empty and must be between 3 and 255 characters !!';
    }
    
    if (empty($req['lastName']) || strlen($req['lastName']) < 3 || strlen($req['lastName']) > 255) {
        $errors['lastName'] = '!! The Last Name field cannot be empty and must be between 3 and 255 characters !!';
    }

    if (!filter_var($req['emailAddress'], FILTER_VALIDATE_EMAIL)) {
        $errors['emailAddress'] = '!! The Email field must not be empty and must be correctly formated,such as the following example: spend@wise.com !!';
    }

    if (getEmailAddresses($req['emailAddress'])) {
        $errors['emailAddress'] = '!! This email is already being used !!';
        return ['invalid' => $errors];
    }

    if (empty($req['password']) || !preg_match('/^(?=.*\d)(?=.*[A-Z])(?=.*[a-z])(?=.*[^a-zA-Z\d])\S{8,}$/', $req['password'])) {
        $errors['password'] = '!! The password must be at least 8 characters long and contain at least one upper and lowercase letters, one number and one special character !!';
    }    

    if ($req['confirmPassword'] != $req['password']) {
        $errors['confirmPassword'] = '!! The Confirm Password field must not be empty and must be the same as the Password field !!';
    }

    if (isset($errors)) {
        return ['invalid' => $errors];
    }

    return $req;
}

?>