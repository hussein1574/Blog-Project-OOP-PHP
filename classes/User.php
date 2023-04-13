<?php

class User {
    private $pdo;
    private $table = 'users';

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function register($email, $username, $password)
    {
        // validate the form
        $errors = [];

        if(empty($email)) {
            $errors[] = 'Email is required';
        }
        if(empty($username)) {
            $errors[] = 'Username is required';
        }
        if(empty($password)) {
            $errors[] = 'Password is required';
        }
        
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo $email;
            $errors[] = 'Email is invalid';
        }
        // remove all tags from input
        $email = htmlspecialchars(strip_tags($email));
        $username = htmlspecialchars(strip_tags($username));
        $password = htmlspecialchars(strip_tags($password));

        $query = "SELECT COUNT(*) FROM $this->table WHERE username = :username OR email = :email";
        try{
            $stmt = $this->pdo->prepare($query);
            $stmt->execute(array(':email' => $email, ':username' => $username));

            $count = $stmt->fetchColumn();

            if ($count > 0) {
                $errors[] = "Username or email address already in use.";
        }
        }catch(PDOException $e){
            $errors[] =  "Connection failed: " . $e->getMessage();
        }

        if(empty($errors))
        {
            try {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $query = "INSERT INTO $this->table (email, username, password) VALUES (:email, :username, :password)";
                $stmt = $this->pdo->prepare($query);
                $stmt->execute(array(':email' => $email, ':username' => $username, ':password' => $hash));
                return true;

            } catch(PDOException $e){
                $errors[] =  "Connection failed: " . $e->getMessage();
                return $errors;
            }
        }
        else
        {
            return $errors;
        }
    }
    public function login($email, $password)
    {
        // validate the form
        $errors = [];

        if(empty($email)) {
            $errors[] = 'Email is required';
        }
        if(empty($password)) {
            $errors[] = 'Password is required';
        }
        // remove all tags from input
        $email = htmlspecialchars(strip_tags($email));
        $password = htmlspecialchars(strip_tags($password));
        
        $query = "SELECT * FROM $this->table WHERE email = :email";

        try{
            $stmt = $this->pdo->prepare($query);
            $stmt->execute(array(':email' => $email));

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // check if the user exists
            if(!$user) {
                $errors[] = "User does not exist";
            }

            //check if the password is correct
            if(!password_verify($password, $user['password'])) {
                $errors[] = "Password is incorrect";
            }

            if(empty($errors))
            {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];

                return true;
                exit;

            }
            else
            {
                return $errors;
            }
        } catch (PDOException $e) {
            $errors[] =  "Connection failed: " . $e->getMessage();
            return $errors;
        }
    }
    public function logout()
    {
        session_destroy();
        unset($_SESSION['user_id']);
        unset($_SESSION['username']);
        unset($_SESSION['email']);
        return true;
    }
    public function changeUserName($userId, $username)
    {
         $errors = [];

        if(empty($username)) {
            $errors[] = 'Username is required';
        }
        if(!empty($errors))
        {
            return $errors;
        }
        $username = htmlspecialchars(strip_tags($username));
        $query = "UPDATE $this->table SET username = :username WHERE id = :id";
        try {
            $stmt = $this->pdo->prepare($query);
            $stmt->execute(array(':username' => $username, ':id' => $userId));
            $_SESSION['username'] = $username;
            return true;
        } catch(PDOException $e){
            $errors[] =  "Connection failed: " . $e->getMessage();
            return $errors;
        }
    }
    public function changeEmail($userId, $email)
    {
         $errors = [];

        if(empty($email)) {
            $errors[] = 'Email is required';
        }     
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo $email;
            $errors[] = 'Email is invalid';
        }
        if(!empty($errors))
        {
            return $errors;
        }
        
        $email = htmlspecialchars(strip_tags($email));
         $query = "SELECT COUNT(*) FROM $this->table WHERE email = :email";
        try{
            $stmt = $this->pdo->prepare($query);
            $stmt->execute(array(':email' => $email));

            $count = $stmt->fetchColumn();

            if ($count > 0) {
                $errors[] = "Email address already in use.";
        }
        }catch(PDOException $e){
            $errors[] =  "Connection failed: " . $e->getMessage();
        }
        if(!empty($errors))
        {
            return $errors;
        }
        $query = "UPDATE $this->table SET email = :email WHERE id = :id";
        try {
            $stmt = $this->pdo->prepare($query);
            $stmt->execute(array(':email' => $email, ':id' => $userId));
            $_SESSION['email'] = $email;
            return true;
        } catch(PDOException $e){
            $errors[] =  "Connection failed: " . $e->getMessage();
            return $errors;
        }
    }
    public function changePassword($userId, $oldPassword, $newPassword)
    {
         $errors = [];

        if(empty($newPassword)) {
            $errors[] = 'New password is required';
        }

        $password = htmlspecialchars(strip_tags($newPassword));
        
        $query = "SELECT * FROM $this->table WHERE id = :id";
        try{
            $stmt = $this->pdo->prepare($query);
            $stmt->execute(array(':id' => $userId));

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // check if the user exists
            if(!$user) {
                $errors[] = "User does not exist";
            }

            //check if the password is correct
            if(!password_verify($oldPassword, $user['password'])) {
                $errors[] = "Password is incorrect";
            }

            if(empty($errors))
            {
                $hash = password_hash($newPassword, PASSWORD_DEFAULT);
                $query = "UPDATE $this->table SET password = :password WHERE id = :id";
                $stmt = $this->pdo->prepare($query);
                $stmt->execute(array(':password' => $hash, ':id' => $userId));
                return true;
            }
            else
            {
                return $errors;
            }
        } catch (PDOException $e) {
            $errors[] =  "Connection failed: " . $e->getMessage();
            return $errors;
        }
    }
}