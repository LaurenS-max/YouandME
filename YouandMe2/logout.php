<?php
// strart the session   
 session_start();

 // clear all session  variables
    session_unset();

// destroy the session
    session_destroy(); 
// redirect to the login page
    header("Location: login.html");
    exit();      
?>