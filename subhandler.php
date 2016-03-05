<?php
# ------------------------------------------------
# License and copyright:
# See license.txt for license information.
# ------------------------------------------------

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
header("Location: s.php" . $req);
