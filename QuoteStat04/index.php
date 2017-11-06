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
        include_once 'headers.php';


        if (isset($_REQUEST["op"])) {
            $op = $_REQUEST["op"];

            switch ($op[0]) {
                /* Need to support pages!
                  case '1':
                  HtmlEcho("CREATE<br>");
                  ShowCreateQuote();
                  exit();
                  break; */
                case '2':
                    HtmlEcho("New &amp; Uncategorized<br>");
                    ShowGbuReview();
                    exit();
                    break;
                case '3':
                    HtmlEcho("Community Quote Status<br>");
                    ShowHistoryReview();
                    exit();
                    break;
            }
        }
        ShowFormWelcome("index.php");

        global $COPYRIGHT;
        echo $COPYRIGHT;
        ?>
    </body>
</html>
