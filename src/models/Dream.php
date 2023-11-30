<?php

class Dream {
    private User $user;
    private String $title;
    private String $description;
    private String $date;
    private int $likes;
    private int $commentAmount;

    public function __construct(User $user, String $title, String $description, String $date, int $likes, int $commentAmount) {
        $this->user = $user;
        $this->title = $title;
        $this->description = $description;
        $this->date = $date;
        $this->likes = $likes;
        $this->commentAmount = $commentAmount;
    }

    public function getUserName(){
        return $this->user->getName();
    }

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

    public function getLikes() {
        return $this->likes;
    }
    public function getCommentsAmount() {
        return $this->commentAmount;
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