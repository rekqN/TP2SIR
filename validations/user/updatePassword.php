<?php
require_once __DIR__ . '/../../repositories/userRepository.php';

function updatePasswordValidation($userID, $currentPassword, $newPassword, $confirmPassword)
{
    $hashedUserPassword = getPasswordHash($userID);

    if ($hashedUserPassword === false) {
        return '!! User not found !!';
    }

    if (!password_verify($currentPassword, $hashedUserPassword)) {
        return '!! Current password is incorrect !!';
    }

    if ($currentPassword === $newPassword) {
        return '!! New password must be different from the current password !!';
    }

    if ($newPassword !== $confirmPassword) {
        return '!! New password and confirm password do not match !!';
    }

    if (!preg_match('/^(?=.*\d)(?=.*[A-Z])(?=.*[a-z])(?=.*[^a-zA-Z\d])\S{8,}$/', $newPassword)) {
        return '!! The password must be at least 8 characters long and contain at least one upper and lowercase letters, one number and one special character !!';
    }

    return true;
}
?>