<?php
require_once('../../private/initialize.php');


$session->logout();
session_destroy();
redirect_to(url_for('/admin/login.php'));
