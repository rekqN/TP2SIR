<?php

function validatedUpdate($req)
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

    if (!empty($req['dateOfBirth'])) {
        $currentDate = new DateTime();
        $dateOfBirth = DateTime::createFromFormat('Y-m-d', $req['dateOfBirth']);

        if ($dateOfBirth > $currentDate) {
            $errors['dateOfBirth'] = '!! Birthdate cannot be a future date !!';
        }
    }

    $req['isAdmin'] = !empty($req['isAdmin']) ? 1 : 0;

    if (isset($errors)) {
        return ['invalid' => $errors];
    }
    return $req;
}
?>