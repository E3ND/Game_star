<?php
    require_once("models/Game.php");
    require_once("models/Message.php");

    class GameDAO implements GameDAOInterface {
        private $conn;
        private $url;
        private $message;

        public function __construct(PDO $conn, $url) {
            $this->conn = $conn;
            $this->url = $url;
            $this->message = new Message($url);
        }

        public function buildGame($data) {
            $game = new Game();

            $game->id = $data["id"];
            $game->title = $data["title"];
            $game->description = $data["description"];
            $game->image = $data["image"];
            $game->trailer = $data["trailer"];
            $game->category = $data["category"];
            $game->length = $data["length"];
            $game->users_id = $data["users_id"];

            return $game;
        }
        public function findAll() {

        }
        public function getLatestGames() {
            $games = [];

            $stmt = $this->conn->query("SELECT * FROM games ORDER BY id DESC");
            $stmt->execute();

            if($stmt->rowCount() > 0) {
                $gamesArray = $stmt->fetchAll();

                foreach($gamesArray as $game) {
                    $games[] = $this->buildGame($game);
                }
            }

            return $games;
        }
        public function getGamesByCategory($category) {
            $games = [];

            $stmt = $this->conn->prepare("SELECT * FROM games 
                    WHERE category = :category
                    ORDER BY id DESC
                ");

            $stmt->bindParam(":category", $category);         

            $stmt->execute();

            if($stmt->rowCount() > 0) {
                $gamesArray = $stmt->fetchAll();

                foreach($gamesArray as $game) {
                    $games[] = $this->buildGame($game);
                }
            }

            return $games;
        }
        public function getGamesByUserId($id) {
            $games = [];

            $stmt = $this->conn->prepare("SELECT * FROM games 
                    WHERE users_id = :users_id
                ");

            $stmt->bindParam(":users_id", $id);         

            $stmt->execute();

            if($stmt->rowCount() > 0) {
                $gamesArray = $stmt->fetchAll();

                foreach($gamesArray as $game) {
                    $games[] = $this->buildGame($game);
                }
            }

            return $games;
        }
        public function findById($id) {
            $game = [];

            $stmt = $this->conn->prepare("SELECT * FROM games 
                    WHERE id = :id
                ");

            $stmt->bindParam(":id", $id);         

            $stmt->execute();

            if($stmt->rowCount() > 0) {
                $gameData = $stmt->fetch();

                return $this->buildGame($gameData);
            } else {
                return false;
            }
        }
        public function findByTitle($title) {

        }
        public function create(Game $game) {
            $stmt = $this->conn->prepare("INSERT INTO games (
                    title, description, image, trailer, category, length, users_id
                ) VALUES (
                    :title, :description, :image, :trailer, :category, :length, :users_id
                )
            ");

            $stmt->bindParam(":title", $game->title);
            $stmt->bindParam(":description", $game->description);
            $stmt->bindParam(":image", $game->image);
            $stmt->bindParam(":trailer", $game->trailer);
            $stmt->bindParam(":category", $game->category);
            $stmt->bindParam(":length", $game->length);
            $stmt->bindParam(":users_id", $game->users_id);

            $stmt->execute();

            $this->message->setMessage("Jogo adicionado com sucesso!", "success", "index.php");
        }
        public function update(Game $game) {
            $stmt = $this->conn->prepare("UPDATE games SET
                title = :title,
                description = :description,
                image = :image,
                category = :category,
                trailer = :trailer,
                length = :length
                WHERE id = :id
            ");

            $stmt->bindParam(":id", $game->id);
            $stmt->bindParam(":title", $game->title);
            $stmt->bindParam(":description", $game->description);
            $stmt->bindParam(":image", $game->image);
            $stmt->bindParam(":category", $game->category);
            $stmt->bindParam(":trailer", $game->trailer);
            $stmt->bindParam(":length", $game->length);

            $stmt->execute();

            $this->message->setMessage("Jogo atualizado com sucesso!", "success", "game.php?id=" . $game->id);
        }
        public function delete($id) {
            $stmt = $this->conn->prepare("DELETE FROM games WHERE id = :id");

            $stmt->bindParam("id", $id);

            $stmt->execute();

            $this->message->setMessage("Jogo removido com sucesso!", "success", "dashboard.php");
        }
    }