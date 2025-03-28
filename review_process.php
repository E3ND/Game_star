<?php

    require_once("globals.php");
    require_once("db.php");
    require_once("models/Game.php");
    require_once("models/Message.php");
    require_once("models/Review.php");
    require_once("dao/UserDAO.php");
    require_once("dao/GameDAO.php");
    require_once("dao/ReviewDAO.php");

    $message = new Message($BASE_URL);
    $userDao = new UserDAO($conn, $BASE_URL);
    $gameDao = new GameDAO($conn, $BASE_URL);
    $reviewDao = new ReviewDAO($conn, $BASE_URL);

    $userData = $userDao->verifyToken();

    $type = filter_input(INPUT_POST ,"type");


    if($type === "create") {
        $rating = filter_input(INPUT_POST ,"rating");
        $review = filter_input(INPUT_POST ,"review");
        $games_id = filter_input(INPUT_POST ,"games_id");

        $reviewObject = new Review();

        $gameData = $gameDao->findById($games_id);

        if($gameData) {
            if(!empty($rating) && !empty($review) && !empty($games_id)) {
                $reviewObject->rating = $rating;
                $reviewObject->review = $review;
                $reviewObject->games_id = $games_id;
                $reviewObject->users_id = $userData->id;

                $reviewDao->create($reviewObject);
            } else {
                $message->setMessage("Voçê precisa inserir a nota e o comentário", "error", "back");
            }
        } else {
            $message->setMessage("Informações inválidas", "error", "index.php");
        }
    } else {
        $message->setMessage("Informações inválidas", "error", "index.php");
    }