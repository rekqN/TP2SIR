<?php
require_once __DIR__ . '/../../repositories/userRepository.php';
require_once __DIR__ . '/../../validations/user/updateUser.php';
@require_once __DIR__ . '/../../validations/session.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = validatedUser($_POST);
    
    if (isset($data['invalid'])) {
        $_SESSION['errors'] = $data['invalid'];
        
        header('Location: ../../pages/secure/profilePage.php');
        exit();
    }

    $user = [
        'userID' => $_SESSION['userID'], 
        'firstName' => $data['firstName'],
        'lastName' => $data['lastName'],
        'emailAddress' => $data['emailAddress'],
        'dateOfBirth' => $data['dateOfBirth'],
        'updatedAt' => date('Y-m-d H:i:s'),
    ];

    $updateSuccess = fullUserUpdate($user);

    if ($updateSuccess) {
        $_SESSION['success'] = '!! Updated profile SUCCESSFULLY !!';
        
        header('Location: ../../pages/secure/profilePage.php');
        exit();
    } else {
        $_SESSION['errors'] = ['update' => '!! FAILED to update profile !!'];
        
        header('Location: ../../pages/secure/profilePage.php');
        exit();
    }
} else {
    echo 'Invalid request.';
}
?>