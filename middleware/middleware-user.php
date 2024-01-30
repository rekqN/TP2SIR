<?php

session_start();

if (!isset($_SESSION['userID'])) {
  if (isset($_COOKIE['userID']) && isset($_COOKIE['firstName'])) {
    $_SESSION['userID'] = $_COOKIE['userID'];
    $_SESSION['firstName'] = $_COOKIE['firstName'];
  } else {
    $home_url = 'http://' . $_SERVER['HTTP_HOST'] . '/index.html/';
    header('Location: ' . $home_url);
  }
}