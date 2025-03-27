<?php

    require_once("globals.php");
    require_once("db.php");
    require_once("models/Game.php");
    require_once("models/Message.php");
    require_once("dao/UserDAO.php");
    require_once("dao/GameDAO.php");

    $message = new Message($BASE_URL);
    $userDao = new UserDAO($conn, $BASE_URL);
    $gameDao = new GameDAO($conn, $BASE_URL);

    $userData = $userDao->verifyToken();

    $type = filter_input(INPUT_POST ,"type");


    if($type === "create") {

    }