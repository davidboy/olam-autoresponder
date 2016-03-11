<?php
# ------------------------------------------------
# License and copyright:
# See license.txt for license information.
# ------------------------------------------------

# Does the config table exist?
$query = "SHOW TABLES LIKE 'InfResp_config'";
$result = $DB->query($query) OR die("Invalid query: " . $DB->error);
if ($result->num_rows == 0) {
    # No, the defs aren't installed!
    $contents = grabFile('defs.sql');
    if ($contents == FALSE) {
        die("Could not find the defs.sql file!\n");
    }

    # Process the defs file.
    preg_match_all('/-- Start command --(.*?)-- End command --/ims', $contents, $queries);
    for ($i = 0; $i < sizeof($queries[1]); $i++) {
        $query = $queries[1][$i];
        # echo nl2br($query) . "<br>\n";
        $DB->query($query) or die("Invalid query: " . $DB->error);
    }
}

?>
