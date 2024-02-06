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

    if (!empty($req['emailAddress'])) {
        $user = user();
        $existingUser = getEmailAddresses($req['emailAddress']);

        if ($existingUser && $existingUser['userID'] != $user['userID']) {
            $errors['emailAddress'] = '!! Email already in use !!';
        }
    } else {
        $errors['emailAddress'] = '!! The Email field cannot be empty !!';
    }

    if (!filter_var($req['emailAddress'], FILTER_VALIDATE_EMAIL)) {
        $errors['emailAddress'] = '!! The Email field must have a valid emailAddress format, for example: spend@wise.com. !!';
    }

    $currentDate = new DateTime();
    $birthdate = DateTime::createFromFormat('Y-m-d', $req['dateOfBirth']);

    if ($birthdate > $currentDate) {
        $errors['birthdate'] = '!! Birth date cannot be a future date !!';
    }

    $req['isAdmin'] = !empty($req['isAdmin']) == 'on' ? true : false;

    if (isset($errors)) {
        return ['invalid' => $errors];
    }
    return $req;
}
?>