<?php
function customErrorHandler($errno, $errstr, $errfile, $errline) {
    error_log("Error [$errno]: $errstr in $errfile on line $errline", 3, "error_log.txt");

    if ($errno == E_WARNING || $errno == E_NOTICE) {
        header("Location: 400.php");
    } else {
        header("Location: 500.php");
    }
    exit();
}

set_error_handler("customErrorHandler");

register_shutdown_function(function() {
    $error = error_get_last();
    if ($error && ($error['type'] === E_ERROR || $error['type'] === E_PARSE)) {
        header("Location: 500.php");
        exit();
    }
});
?>
