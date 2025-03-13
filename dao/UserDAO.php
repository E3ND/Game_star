<?php

    require_once("models/User.php");
    require_once("models/Message.php");

    $is_dev = true;

function debug() {
    global $is_dev;

    if ($is_dev) {
        $debug_arr = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        $line = $debug_arr[0]['line'];
        $file = $debug_arr[0]['file'];
    
        header('Content-Type: text/plain');

        echo "linha: $line\n";
        echo "arquivo: $file\n\n";
        print_r(array('GET' => $_GET, 'POST' => $_POST, 'SERVER' => $_SERVER));
        exit;
    }
}

    class UserDAO implements UserDAOInterface {
        private $conn;
        private $url;
        private $message;

        public function __construct(PDO $conn, $url) {
            $this->conn = $conn;
            $this->url = $url;
            $this->message = new Message($url);
        }

        public function buildUser($data) {
            $user = new User();

            $user->id = $data["id"];
            $user->name = $data["name"];
            $user->lastName = $data["lastname"];
            $user->email = $data["email"];
            $user->password = $data["password"];
            $user->image = $data["image"];
            $user->bio = $data["bio"];
            $user->token = $data["token"];

            return $user;
        }
        
        public function create(User $user, $authUser = false) {
            $stmt = $this->conn->prepare(
                "INSERT INTO users(name, lastname, email, password, token) 
                VALUES(:name, :lastName, :email, :password, :token)"
            );

            $stmt->bindParam(":name", $user->name);
            $stmt->bindParam(":lastName", $user->lastName);
            $stmt->bindParam(":email", $user->email);
            $stmt->bindParam(":password", $user->password);
            $stmt->bindParam(":token", $user->token);

            $stmt->execute();

            if($authUser) {
                $this->setTokenToSession($user->token, $user->name);
            }
        }
        public function update(User $user, $redirect = true) {
            $stmt = $this->conn->prepare("UPDATE users SET 
                name = :name, 
                lastname = :lastName, 
                email = :email, 
                image = :image, 
                token = :token, 
                bio = :bio
                WHERE id = :id"
            );

            $stmt->bindParam(":id", $user->id);
            $stmt->bindParam(":name", $user->name);
            $stmt->bindParam(":lastName", $user->lastName);
            $stmt->bindParam(":email", $user->email);
            $stmt->bindParam(":image", $user->image);
            $stmt->bindParam(":token", $user->token);
            $stmt->bindParam(":bio", $user->bio);

            $stmt->execute();

            if($redirect) {
                $this->message->setMessage("Seja bem-vindo $user->name", "success", "editprofile.php");
            }
        }
        public function verifyToken($protected = false) {
            if(!empty($_SESSION["token"])) {
                $token = $_SESSION["token"];

                $user = $this->findByToken($token);

                if($user) {
                    return $user;
                } else if($protected) {
                    $this->message->setMessage("Faça a autenticação para acessar esta página", "error", "index.php");
                }
            } else if($protected) {
                $this->message->setMessage("Faça a autenticação para acessar esta página", "error", "index.php");
            }
        }
        public function setTokenToSession($token, $name, $redirect = true) {
            $_SESSION["token"] = $token;

            if($redirect) {
                $this->message->setMessage("Seja bem-vindo $name", "success", "editprofile.php");
            }
        }
        public function authenticateUser($email, $password) {
            $user = $this->findByEmail($email);

            if($user) {
                if(password_verify($password, $user->password)) {
                    $token = $user->generateToken();

                    $this->setTokenToSession($token, $user->name, false);

                    $user->token = $token;

                    $this->update($user, false);

                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
        public function changePassword(User $user) {
            
        }
        public function findByToken($token) {
            if($token == "") return false;

            $stmt = $this->conn->prepare("SELECT * FROM users WHERE token = :token");
            $stmt->bindParam(":token", $token);
            $stmt->execute();

            if($stmt->rowCount() === 0) return false;

            $data = $stmt->fetch();
            $user = $this->buildUser($data);

            return $user;
        }
        public function findByEmail($email) {
            if($email == "") return false;

            $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = :email");
            $stmt->bindParam(":email", $email);
            $stmt->execute();

            if($stmt->rowCount() === 0) return false;

            $data = $stmt->fetch();
            $user = $this->buildUser($data);

            return $user;
        }
        public function finById($id) {
            
        }

        public function destroyToken() {
            $_SESSION["token"] = "";

            $this->message->setMessage("Você fez o logout com sucesso!", "success", "index.php");
        }
    }