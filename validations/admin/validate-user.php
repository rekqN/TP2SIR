<?php

function validatedUser($req)
{
    foreach ($req as $key => $value) {
        $req[$key] =  trim($req[$key]);
    }

    if (empty($req['firstName']) || strlen($req['firstName']) < 3 || strlen($req['firstName']) > 255) {
        $errors['firstName'] = '!! The Name field cannot be empty and must be between 3 and 255 characters !!';
    }

    if (empty($req['lastName']) || strlen($req['lastName']) < 3 || strlen($req['lastName']) > 255) {
        $errors['lastName'] = '!! The Last Name field cannot be empty and must be between 3 and 255 characters !!';
    }

    if (!filter_var($req['emailAddress'], FILTER_VALIDATE_EMAIL)) {
        $errors['emailAddress'] = 'The Email field cannot be empty and must have the email format, for example: spend@wise.com.';
    }

    if (getUserIDByEmailAddress($req['emailAddress'])) {
        $errors['emailAddress'] = 'Email already registered in our system.';
        return ['invalid' => $errors];
    }

    if (!empty($req['password']) && strlen($req['password']) < 6) {
        $errors['password'] = 'The Password field cannot be empty and must be at least 6 characters long.';
    }

    // if (!empty($req['confirm_password']) && ($req['confirm_password']) != $req['password']) {
    //     $errors['confirm_password'] = 'The Confirm Password field must not be empty and must be the same as the Password field.';
    // }

    $req['administrator'] = !empty($req['administrator']) == 'on' ? true : false;

    if (isset($errors)) {
        return ['invalid' => $errors];
    }
    return $req;
}
?>