<?php

namespace App;

use App\Entity\Comment;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class SpamChecker
{
    /**
     * @var HttpClientInterface $httpClient
     */
    private $httpClient;

    private $endpoint;

    public function __construct(HttpClientInterface $httpClient, string $akismetKey)
    {
        $this->httpClient = $httpClient;
        $this->endpoint = sprintf('https://%s.rest.akismet.com/1.1/comment-check', $akismetKey);
    }

    /**
     * @param Comment $comment
     * @param array $context
     *
     * @return int Spam score: 0 - not spam, 1 - maybe spam, 2 - blatant spam
     *
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function getSpamScore(Comment $comment, array $context): int
    {
        $response = $this->httpClient->request(Request::METHOD_POST, $this->endpoint, [
            'body' => array_merge($context, [
                'blog' => 'https://guestbook.example.com',
                'comment_type' => 'comment',
                'comment_author' => $comment->getAuthor(),
                'comment_author_email' => $comment->getAuthor()->getEmail() ?? $comment->getAuthor()->getUsername(),
                'comment_content' => $comment->getText(),
                'comment_date_gmt' => $comment->getCreated()->format('c'),
                'blog_lang' => 'en',
                'blog_charset' => 'UTF-8',
                'is_test' => true,
            ]),
        ]);

        $headers = $response->getHeaders();
        if (($headers['x-akismet-pro-tip'][0] ?? '') === 'discard') {
            return 2;
        }

        $content = $response->getContent();
        if (isset($headers['x-akismet-pro-tip'][0])) {
            throw new \RuntimeException(sprintf('Unable to check for spam:   %s (%s)', $content, $headers['x-akismet-pro-tip'][0]));
        }

        return $content === 'true' ? 1 : 0;
    }
}
