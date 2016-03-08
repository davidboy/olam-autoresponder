<?php
# ------------------------------------------------
# License and copyright:
# See license.txt for license information.
# ------------------------------------------------

require_once('common.php');

if (userIsLoggedIn()) {
    redirectTo('/admin.php?action=list');
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // GET action: display the login form

    require('templates/open.page.php');
    include('templates/login.admin.php');
    require('templates/close.page.php');

} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // POST action: process the login request

    $submittedUsername = trim($_POST['login']);
    $submittedPassword = trim($_POST['pword']);

    if ($submittedUsername == $config['admin_user'] && password_verify($submittedPassword, $config['admin_pass'])) {
        createLoginSession($submittedUsername, $config['admin_pass']);

        redirectTo('/admin.php?action=list');
    } else {
        include('templates/open.page.php');

        print '<p class="err_msg">Error: invalid username and password combination</p><br />';

        include('templates/login.admin.php');
        copyright();
        include('templates/close.page.php');

        die();
    }
}

dbDisconnect();