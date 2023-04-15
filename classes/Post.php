<?php

class Post {
    private $pdo;
    private $table = 'posts';

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function createPost($title, $content, $userId)
    {
        // remove all tags from input
        $content = htmlspecialchars(strip_tags($content));
        $title = htmlspecialchars(strip_tags($title));
        $userId = htmlspecialchars(strip_tags($userId));

        $errors = [];
        $query = "INSERT INTO $this->table (title, content, user_id) VALUES (:title, :content, :user_id)";
        try{
            $stmt = $this->pdo->prepare($query);
            $stmt->execute(array(':title' => $title, ':content' => $content, ':user_id' => $userId));
            return true;
        } catch(PDOException $e){
            $errors[] =  "Connection failed: " . $e->getMessage();
            return $errors;
        }
    }
    public function readAllPosts($pageNumber)
    {
        $errors = [];
        $query = "SELECT $this->table .* , users.username FROM $this->table LEFT JOIN users ON $this->table.user_id = users.id ORDER BY $this->table.created_at DESC";
        try{
            $stmt = $this->pdo->prepare($query);
            $stmt->execute();
            $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $count = count($posts);
            $posts = array_slice($posts, ($pageNumber - 1) * 5, 5);
            $posts['count'] = $count;
            return $posts;
        } catch(PDOException $e){
            $errors[] =  "Connection failed: " . $e->getMessage();
            return $errors;
        }
    }
    public function readPost($id)
    {
        $errors = [];
        $query = "SELECT p.title , p.content, p.created_at, p.user_id , u.username FROM $this->table p LEFT JOIN users u ON p.user_id = u.id WHERE p.id = :id LIMIT 0,1";
        
        try {
            $stmt = $this->pdo->prepare($query);
            $stmt->execute(array(':id' => $id));
            $post = $stmt->fetch(PDO::FETCH_ASSOC);
            return $post;
        } catch(PDOException $e){
            $errors[] =  "Connection failed: " . $e->getMessage();
            return $errors;
        }
    }
    public function deletePost($id)
    {
        $errors = [];
        $query = "DELETE FROM $this->table WHERE id = :id";
        try{
            $stmt = $this->pdo->prepare($query);
            $stmt->execute(array(':id' => $id));

            return true;
        } catch(PDOException $e){
            $errors[] =  "Connection failed: " . $e->getMessage();
            return $errors;
        }
    }
    public function updatePost($title, $content , $id)
    {
        $errors = [];
        $query = "UPDATE $this->table SET title = :title, content = :content WHERE id = :id";
        try{
            $stmt = $this->pdo->prepare($query);
            $stmt->execute(array(':title' => $title, ':content' => $content, ':id' => $id));
            print_r($stmt);
            return true;
        }catch(PDOException $e){
            $errors[] =  "Connection failed: " . $e->getMessage();
            return $errors;
        }
    }
    public function readAllMyPosts($userId, $pageNumber)
    {
        $errors = [];
        $query = "SELECT $this->table .* , users.username FROM $this->table LEFT JOIN users ON $this->table.user_id = users.id WHERE $this->table.user_id = :user_id ORDER BY $this->table.created_at DESC";
        try {
            $stmt = $this->pdo->prepare($query);
            $stmt->execute(array(':user_id' => $userId));
            $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $count = count($posts);
            $posts = array_slice($posts, ($pageNumber - 1) * 5, 5);
            $posts['count'] = $count;
            return $posts;
        } catch(PDOException $e){
            $errors[] =  "Connection failed: " . $e->getMessage();
            return $errors;
        }
    }
    public function SearchForPosts($keyword, $pageNumber)
    {
        $errors = [];
        $query = "SELECT $this->table .* , users.username FROM $this->table LEFT JOIN users ON $this->table.user_id = users.id WHERE $this->table.title LIKE :keyword OR $this->table.content LIKE  :keyword ORDER BY $this->table.created_at DESC";
        try {
            $stmt = $this->pdo->prepare($query);
            $stmt->execute(array(':keyword' => "%$keyword%"));
            $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $count = count($posts);
            $posts = array_slice($posts, ($pageNumber - 1) * 5, 5);
            $posts['count'] = $count;
            return $posts;
        } catch(PDOException $e){
            $errors[] =  "Connection failed: " . $e->getMessage();
            return $errors;
        } 
    }
}