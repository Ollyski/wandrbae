<?php
require_once('../../private/initialize.php');
include_header();

$user_session->logout();
session_destroy();
redirect_to(url_for('/admin/login.php'));
