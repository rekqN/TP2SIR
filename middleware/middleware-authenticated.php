<?php

require_once __DIR__ . '/../validations/session.php';

if (!isAuthenticated()) {
    $home_url = 'http://' . $_SERVER['HTTP_HOST'] . '/index.html/';
    header('Location: ' . $home_url);
}
