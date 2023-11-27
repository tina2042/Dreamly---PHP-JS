<?php

class Dream {
    //private $id;
    //private $userId;
    private $title;
    private $description;
    private $date;

    public function __construct($userId, $title, $description, $date) {
       // $this->userId = $userId;
       // $this->title = $title;
        $this->description = $description;
        $this->date = $date;
    }

    // Getter and Setter methods for private properties
    /*
    public function getId() {
        return $this->id;
    }

    public function getUserId() {
        return $this->userId;
    }
        */
    public function getTitle() {
        return $this->title;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function getDate() {
        return $this->date;
    }

    public function setDate($date) {
        $this->date = $date;
    }

    // Additional methods for dream management can be added as needed

    public function save() {
        // Logic to save the dream to a database or any other storage mechanism
        // You can use database queries or an ORM (Object-Relational Mapping) library here
        // For simplicity, we'll just print a message in this example
        echo "Dream saved successfully!\n";
    }

    public function delete() {
        // Logic to delete the dream from the storage
        // Again, you might want to use database queries or an ORM library
        // For simplicity, we'll just print a message in this example
        echo "Dream deleted successfully!\n";
    }

    // You can add more methods for dream-related functionalities as per your application requirements
}