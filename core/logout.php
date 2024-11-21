<?php

    include('include.php');

    session_start();
    session_destroy();

    return_user('../auth.php?status=901');

?>