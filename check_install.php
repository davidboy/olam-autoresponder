<?php 
# ------------------------------------------------
# License and copyright:
# See license.txt for license information.
# ------------------------------------------------

# Does the config table exist?
$query = "SHOW TABLES LIKE 'InfResp_config'";
$result = mysql_query($query) OR die("Invalid query: " . mysql_error());
if (mysql_num_rows($result) == 0) {
     # No, the defs aren't installed!
     $contents = GrabFile('defs.sql');
     if ($contents == FALSE) {
          die("Could not find the defs.sql file!\n");
     }

     # Process the defs file.
     preg_match_all('/-- Start command --(.*?)-- End command --/ims', $contents, $queries);
     for ($i=0; $i < sizeof($queries[1]); $i++) {
          $query = $queries[1][$i];
          # echo nl2br($query) . "<br>\n";
          $result = mysql_query($query) OR die("Invalid query: " . nl2br(mysql_error()));
     }
}

?>