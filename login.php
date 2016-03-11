<?php
// This file is part of Olam Autoresponder.
// Copyright (c) 2004-2007 Aaron Colman and Adaptive Business Design.
// Copyright (c) 2016 Anna Burdette, Benjamin Jobson, and David Reed.
//
// Olam Autoresponder is free software: you can redistribute it and/or modify
//     it under the terms of the GNU General Public License as published by
//     the Free Software Foundation, version 2.
//
// Olam Autoresponder is distributed in the hope that it will be useful,
//     but WITHOUT ANY WARRANTY; without even the implied warranty of
//     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//     GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
//     along with Olam Autoresponder.  If not, see <http://www.gnu.org/licenses/>.

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
