<?php

if(session_destroy()) // Destroying All Sessions
{
header("Location: " .W_ROOT . "index.php"); // Redirecting To Home Page
}
