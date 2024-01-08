<?php

class Comment
{
    private int $comment_id;
    private int $user_id;
    private int $dream_id;
    private String $comment_content;
    private DateTime $date;
    public function __construct(int $comment_id, int $user_id, int $dream_id, String $comment_content, DateTime $date) {
        $this->comment_id = $comment_id;
        $this->user_id = $user_id;
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


}