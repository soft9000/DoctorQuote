<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Doctor Quote Creator - Version 1.0</title>
        <link rel="stylesheet" type="text/css" href="drquote.css">
    </head>
    <body>

        <?php
        include_once 'CodeCreateQuote.php';

        HtmlEcho("START");

        if (HasValidQuote() === true) {
            $rec = SaveValidatedQuote();
            if ($rec === false) {
                HtmlEcho("Error in SaveValidatedQuote!");
            } else {
                HtmlEcho("Saved quote #" . $rec);
            }
        }

        ShowCreateQuote();

        global $COPYRIGHT;
        echo $COPYRIGHT;
        ?>
    </body>
</html>

