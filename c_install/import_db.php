<?php
function importDatabase()
{
    $_SESSION['db_import_started'] = 1;

    // Name of the file
    $filename = DOC_ROOT . '/c_install/ccms.sql';
// Connect to MySQL server
    $conn = MySQL::open_conn();
// Temporary variable, used to store current query
    $templine = '';

    try {
// Read in entire file
        $lines = file($filename);
// Loop through each line

        foreach ($lines as $line) {
// Skip it if it's a comment
            if (substr($line, 0, 2) == '--' || $line == '')
                continue;

// Add this line to the current segment
            $templine .= $line;
// If it has a semicolon at the end, it's the end of the query
            if (substr(trim($line), -1, 1) == ';') {
                // Perform the query
                if (!$conn->query($templine))
                    return false;
                // Reset temp variable to empty
                $templine = '';
            }
        }
    }
    catch(Exception $ex)
    {
        $_SESSION['db_import_done'] = 1;
        ob_end_clean();
        redirectTo('index.php?switch=1');
    }
}