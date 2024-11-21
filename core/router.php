<?php
    session_start();
    require_once("../config/path.php");
    require_once(CONFIG."/routes.php");
    require_once(CORE."/include.php");

    $uri = substr(trim(parse_url($_SERVER["REQUEST_URI"])['path'], '/'), 9);
    
    if (array_key_exists($uri, $routes)) {
        if (strlen($uri) == 0) {
            if (array_key_exists('token', $_SESSION)) {
                return_user("table/student");
            } else {
                require_once(CONTR."/index.php");
            }
        } else {
            $uris = explode('/', $uri);

            if ($uris[0] == 'view') {
                require_once(VIEWS."/".$uri.".html");
            } else {
                require_once(CONTR."/".$uri.".php");
            }
        }
    } else {
            require_once(ERROR."/404.php");
    }

?>