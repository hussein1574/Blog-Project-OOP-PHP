<?php

class Comment {

    private $pdo;
    private $table = 'comments';

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function createComment($content, $postId, $userId)
    {
        // remove all tags from input
        $content = htmlspecialchars(strip_tags($content));
        $postId = htmlspecialchars(strip_tags($postId));


        $errors = [];
        $query = "INSERT INTO $this->table (content, post_id, user_id) VALUES (:content, :post_id, :user_id)";
        try{
            $stmt = $this->pdo->prepare($query);
            $stmt->execute(array(':content' => $content, ':post_id' => $postId, ':user_id' => $userId));
            return true;
        } catch(PDOException $e){
            $errors[] =  "Connection failed: " . $e->getMessage();
            return $errors;
        }
    }
    public function readAllComments($postId)
    {
        $errors = [];
        $query = "SELECT $this->table .* , users.username FROM $this->table LEFT JOIN users ON $this->table.user_id = users.id WHERE $this->table.post_id = :post_id ORDER BY $this->table.created_at DESC";
        try{
            $stmt = $this->pdo->prepare($query);
            $stmt->execute(array(':post_id' => $postId));
            $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $comments;
        } catch(PDOException $e){
            $errors[] =  "Connection failed: " . $e->getMessage();
            return $errors;
        }
    }

}