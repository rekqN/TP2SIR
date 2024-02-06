<?php

function isExpenseValid($req) {
    foreach ($req as $key => $value) {
        $req[$key] = trim($req[$key]);
    }

    $errors = [];

    if (!is_numeric($req['paidAmount']) || $req['paidAmount'] < 0) {
        $errors['paidAmount'] = '!! paidAmount field must be a NON-NEGATIVE value !!';
    }

    if (strlen($req['expenseNotes']) > 255) {
        $errors['expenseNotes'] = '!! Note field cannot exceed 255 characters !!';
    }

    if (!empty($errors)) {
        return ['invalid' => $errors];
    }

    return $req;
}
?>