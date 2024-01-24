<?php

class Dream
{
    private int $dreamId;
    private User $owner;
    private string $title;
    private string $description;
    private DateTime $date;
    private int $likes;
    private int $commentAmount;
    private string $privacy;

    public function __construct(User $owner, string $title, string $description, DateTime $date, int $likes, int $commentAmount)
    {
        $this->owner = $owner;
        $this->title = $title;
        $this->description = $description;
        $this->date = $date;
        $this->likes = $likes;
        $this->commentAmount = $commentAmount;
    }

    public function getPrivacy(): string
    {
        return $this->privacy;
    }

    public function setPrivacy(string $privacy)
    {
        $this->privacy = $privacy;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getDate(): string
    {
        return $this->date->format("Y-m-d");
    }

    public function setDate($date)
    {
        $this->date = $date;
    }

    public function getLikes(): int
    {
        return $this->likes;
    }

    public function getCommentsAmount(): int
    {
        return $this->commentAmount;
    }

    public function getDreamId(): int
    {
        return $this->dreamId;
    }

    public function setDreamId(int $dreamId): void
    {
        $this->dreamId = $dreamId;
    }

    public function getOwner(): User
    {
        return $this->owner;
    }

}