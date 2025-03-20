<?php

    class Game {
        public $id;
        public $title;
        public $description;
        public $image;
        public $trailer;
        public $category;
        public $length;
        public $users_id;

    }

    interface GameDAOInterface {
        public function buildGame($data);
        public function findAll();
        public function getLastesGames();
        public function getGamesByCategory($category);
        public function getGamesByUserId($id);
        public function findById($id);
        public function findByTitle($title);
        public function create(Game $Game);
        public function update(Game $movie);
        public function delete($id);
    }