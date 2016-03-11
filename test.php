<?php

require_once 'evilness-filter.php';

$test_strings = array(
    '<img onclick="stuff" />',
    '<img src="foobar.png" />',
    '<script></script>'
);

foreach ($test_strings as $test_string) {
    if (removeEvilTags($test_string) != removeEvilTagsOld($test_string)) {
        echo 'FAIL: ' . htmlspecialchars(removeEvilTags($test_string)) . ' vs ' . htmlspecialchars(removeEvilTagsOld($test_string)) . '<br />';
    } else {
        echo 'GOOD: ' . htmlspecialchars(removeEvilTags($test_string)) . ' vs ' . htmlspecialchars(removeEvilTagsOld($test_string)) . '<br />';
    }
}

echo 'DONE';