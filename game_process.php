<?php

    require_once("globals.php");
    require_once("db.php");
    require_once("models/Game.php");
    require_once("models/Message.php");
    require_once("dao/UserDAO.php");
    require_once("dao/GameDAO.php");
    require_once("utils/generateChars.php");
    require_once("utils/uploadImage.php");

    $message = new Message($BASE_URL);
    $userDao = new UserDAO($conn, $BASE_URL);

    $type = filter_input(INPUT_POST, "type");

    $userData = $userDao->verifyToken();

    if($type === "create") {
        $title = filter_input(INPUT_POST, "title");
        $description = filter_input(INPUT_POST, "description");
        $trailer = filter_input(INPUT_POST, "trailer");
        $category = filter_input(INPUT_POST, "category");
        $length = filter_input(INPUT_POST, "length");

        $game = new game();

        if(!empty($title) && !empty($description) && !empty($category)) {
            $game->title = $title;
            $game->description = $description;
            $game->trailer = $trailer;
            $game->category = $category;
            $game->length = $length;

            $uploadImage = upload($_FILES, "games");

            if($uploadImage === false) {
                $message->setMessage("Tipo inválido de imagem, insira png, jpeg ou jpg", "error", "index.php");
            }

            $game->image = $uploadImage;

            print_r($_POST); print_r($_FILES);exit;

            $gameDao->create($game);
        } else {
            $message->setMessage("Você precisa adicionar pelo menos: título, descrição e categoria", "error", "back");
        }
    } else {
        $message->setMessage("Informações inválidas", "error", "index.php");
    }