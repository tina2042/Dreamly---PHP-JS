<?php

class Comment
{
    private int $comment_id;
    private User $owner;
    private int $dream_id;
    private string $comment_content;
    private DateTime $date;

    public function __construct(int $comment_id, User $owner, int $dream_id, string $comment_content, DateTime $date)
    {
        $this->comment_id = $comment_id;
        $this->owner = $owner;
        $this->dream_id = $dream_id;
        $this->comment_content = $comment_content;
        $this->date = $date;

    }

    public function getCommentContent(): string
    {
        return $this->comment_content;
    }

    public function getDate(): DateTime
    {
        return $this->date;
    }

    public function getCommentId(): int
    {
        return $this->comment_id;
    }

    public function getOwner(): User
    {
        return $this->owner;
    }

    public function getDreamId(): int
    {
        return $this->dream_id;
    }


}