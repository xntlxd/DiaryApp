<?php

    if (!defined("ROOT")) {
        define("ROOT", dirname(__DIR__));
        define("APP", ROOT."/app");
        define("CORE", ROOT."/core");
        define("CONFIG", ROOT."/config");
        define("PUBLIC", ROOT."/public");
        define("CONTR", APP."/controllers");
        define("INC", APP."/inc");
        define("VIEWS", APP."/views");
        define("ERROR", VIEWS."/error");
        define("FUNC", CORE."/functions.php");
    }

?>