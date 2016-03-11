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
