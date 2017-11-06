<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Quick Quote Review - Version 1.0</title>
        <link rel="stylesheet" type="text/css" href="drquote.css">
        <link rel="stylesheet" type="text/css" href="drquote.css">
    </head>
    <body>

        <?php
        include_once "headers.php";

        ShowHomeLink();

        $nav = new IpTracker();
        if ($nav->isLocal()) {
            HtmlEcho("Local Database");
        } else {
            HtmlEcho("Public Database");
        }

        global $DBFILE;
        global $BACKUP;

        $ztime = 'Not Found';
        echo "<br />";
        if (file_exists($DBFILE)) {
            $ztime = filemtime($DBFILE);
            echo "<b>Database Last Updated: </b>" . date("F d, Y @ H:i:s.", $ztime);
        } else {
            echo "<b>Database Last Updated: </b>" . $ztime;
        }

        $backup = 'Never';
        echo "<br />";
        if (file_exists($BACKUP)) {
            $backup = filemtime($BACKUP);
            echo "<b>Backup Exported: </b>" . date("F d, Y @ H:i:s.", $backup);
        } else {
            echo "<b>Backup Exported: </b>" . $backup;
        }
        echo "<br />";

        global $COPYRIGHT;
        echo $COPYRIGHT;
        ?>
    </body>
</html>

