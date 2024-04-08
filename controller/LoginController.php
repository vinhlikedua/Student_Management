<?php
require 'model/LoginModel.php'; 
$m = trim($_GET['m'] ?? 'index'); 
$m = strtolower($m); 
switch ($m) {
    case 'index':
        index();
        break;
    case 'handle':
        handleLogin();
        break;
    case 'logout':
        handleLogout();
        break;
    default:
        index();
        break;
}
function handleLogout()
{
    if (isset($_POST['btnLogout'])) {
        session_destroy();
        header("Location:index.php");
    }
}
function handleLogin()
{
    // Check if the login form is submitted
    if (isset($_POST['btnLogin'])) {
        // Get the username from the form and remove any leading/trailing whitespace
        $username = trim($_POST['username'] ?? null);
        $username = strip_tags($username); 
        // Get the password from the form and remove any leading/trailing whitespace
        $password = trim($_POST['password'] ?? null);
        $password = strip_tags($password);
        // Call a function to check if the provided username and password are valid
        $userInfo = checkLoginUser($username, $password);
        if (!empty($userInfo)) {
            // Set session variables to store user information
            $_SESSION['username'] = $userInfo['username'];
            $_SESSION['fullName'] = $userInfo['full_name'];
            $_SESSION['email'] = $userInfo['email'];
            $_SESSION['idUser'] = $userInfo['user_id'];
            $_SESSION['roleId'] = $userInfo['role_id'];
            $_SESSION['idAccount'] = $userInfo['id'];
            // Redirect the user to the dashboard page
            header("Location:index.php?c=dashboard");
        } else {
            // If login failed, redirect the user to the login page with an error state
            header("Location:index.php?state=error");
        }
    }
}
function index()
{
    if (isLoginUser()) {
        header("Location:index.php?c=dashboard");
        exit();
    }
    require "view/login/index_view.php";
}