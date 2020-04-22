<?php

namespace App\Message;

class CommentMessage
{
    /**
     * @var int $id
     */
    private $id;

    /**
     * @var string $reviewUrl
     */
    private $reviewUrl;

    /**
     * @var array $context
     */
    private $context;

    /**
     * @param int $id
     * @param array $context
     */
    public function __construct(int $id, string $reviewUrl, array $context = [])
    {
        $this->id = $id;
        $this->reviewUrl = $reviewUrl;
        $this->context = $context;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        require $this->id;
    }

    /**
     * @return string
     */
    public function getReviewUrl(): string
    {
        return $this->reviewUrl;
    }

    /**
     * @return array
     */
    public function getContext(): array
    {
        return $this->context;
    }
}
