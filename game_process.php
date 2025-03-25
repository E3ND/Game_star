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
    $gameDao = new GameDAO($conn, $BASE_URL);

    $type = filter_input(INPUT_POST, "type");

    $userData = $userDao->verifyToken();

    // print_r($type);exit;

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
            $game->users_id = $userData->id;

            $uploadImage = upload($_FILES, "games");

            if($uploadImage === false) {
                $message->setMessage("Tipo inválido de imagem, insira png, jpeg ou jpg", "error", "index.php");
            } else {
                $game->image = $uploadImage;
            }

            $gameDao->create($game);
        } else {
            $message->setMessage("Você precisa adicionar pelo menos: título, descrição e categoria", "error", "back");
        }
    } else if($type === 'delete') {
        $id = filter_input(INPUT_POST, "id");

        $game = $gameDao->findById($id);

        if($game) {
            if($game->users_id === $userData->id) {
                $gameDao->delete($game->id);
            } else {
                $message->setMessage("Informações inválidas", "error", "index.php");
            }
        } else {
            $message->setMessage("Informações inválidas", "error", "index.php");
        }


    } else if($type === 'update') {
        $id = filter_input(INPUT_POST, "id");
        $title = filter_input(INPUT_POST, "title");
        $description = filter_input(INPUT_POST, "description");
        $trailer = filter_input(INPUT_POST, "trailer");
        $category = filter_input(INPUT_POST, "category");
        $length = filter_input(INPUT_POST, "length");

        $gameData = $gameDao->findById($id);

        if($gameData) {
            if($gameData->users_id === $userData->id) {
                if(!empty($title) && !empty($description) && !empty($category)) {
                    $gameData->id = $id;
                    $gameData->title = $title;
                    $gameData->description = $description;
                    $gameData->trailer = $trailer;
                    $gameData->category = $category;
                    $gameData->length = $length;

                    //Todo fluxo de apagar a imagem alterada
                    $uploadImage = upload($_FILES, "games");

                    if($uploadImage === false) {
                        $message->setMessage("Tipo inválido de imagem, insira png, jpeg ou jpg", "error", "index.php");
                    } else {
                        $gameData->image = $uploadImage;
                    }

                    $gameDao->update($gameData);
                } else {
                    $message->setMessage("Você precisa adicionar pelo menos: título, descrição e categoria", "error", "back");
                }

                // $gameDao->update($game->id);
            } else {
                $message->setMessage("Informações inválidas", "error", "index.php");
            }
        } else {
            $message->setMessage("Informações inválidas", "error", "index.php");
        }
    } else {
        $message->setMessage("Informações inválidas", "error", "index.php");
    }