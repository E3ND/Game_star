<?php

    class User {
        public $id;
        public $name;
        public $lastName;
        public $email;
        public $password;
        public $image;
        public $bio;
        public $token;

        public function generateToken() {
            return bin2hex(random_bytes(50));
        }

        public function generatePassword($password) {
            return password_hash($password, PASSWORD_DEFAULT);
        }

        public function getFullName($user) {
            return $user->name . " " . $user->lastName;
        }
    }
    
    interface UserDAOInterface {
        public function buildUser($data);
        public function create(User $user, $authUser = false);
        public function update(User $user, $redirect = true);
        public function verifyToken($protected = false);
        public function setTokenToSession($token, $name, $redirect = false);
        public function authenticateUser($email, $password);
        public function changePassword(User $user);
        public function findByToken($token);
        public function findByEmail($email);
        public function finById($id);
        public function destroyToken();
    }