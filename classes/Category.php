<?php

class Category{
    private $pdo;
    private $table = 'categories';

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function getCategories()
    {
        $errors = [];
        $query = "SELECT * FROM $this->table";
        try{
            $stmt = $this->pdo->prepare($query);
            $stmt->execute();
            $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $categories;
        } catch(PDOException $e){
            $errors[] =  "Connection failed: " . $e->getMessage();
            return $errors;
        }
    }

    
}