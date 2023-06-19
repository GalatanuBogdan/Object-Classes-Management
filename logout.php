<?php

include 'utils.php';
if(isUserLoggedIn())
{
    session_destroy();
    $logoutMessage = 'logout succeed.';
    header("Location: index.php?logoutMessage=$logoutMessage", true, 303);
}