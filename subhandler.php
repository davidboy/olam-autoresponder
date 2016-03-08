<?php
# ------------------------------------------------
# License and copyright:
# See license.txt for license information.
# ------------------------------------------------

// This file is old and broken and I have no idea what it does.  Enter at your own risk.

# Grab passed variables
$req = "";
$init = FALSE;
foreach ($_REQUEST as $key => $value) {
    $value = urlencode(stripslashes($value));
    if (!($init)) {
        $req .= "?$key=$value";
        $init = TRUE;
    } else {
        $req .= "&$key=$value";
    }
}

# Redirect
header("Location: subscribe.php" . $req);
