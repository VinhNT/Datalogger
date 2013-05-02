<?php

require_once 'classes/Membership.php';
require_once 'classes/conf.php';
$membership = new Membership();
$membership->confirm_Member(); // redirect to login page in not login yet


// if logged in user, redirect to SHOW page
header('location: demo/'.LOGIN_REDIRECT);