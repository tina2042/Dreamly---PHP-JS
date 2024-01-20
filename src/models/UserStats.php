<?php
class UserStats {
    private int $dreams_amount;
    private int $like_amount;
    private int $comment_amount;
    public function __construct(int $dreams_amount1, int $like_amount1, int $comment_amount1)
    {
        $this->dreams_amount = $dreams_amount1;
        $this->like_amount = $like_amount1;
        $this->comment_amount = $comment_amount1;
    }

    public function getDreamsAmount(): int
    {
        return $this->dreams_amount;
    }

    public function getLikeAmount(): int
    {
        return $this->like_amount;
    }

    public function getCommentAmount(): int
    {
        return $this->comment_amount;
    }

}