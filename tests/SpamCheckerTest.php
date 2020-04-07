<?php

namespace App\Tests;

use App\Entity\Comment;
use App\Entity\User;
use App\SpamChecker;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Contracts\HttpClient\ResponseInterface;

class SpamCheckerTest extends TestCase
{
    public function testGetSpamScoreWithInvalidRequest()
    {
        $context = [];

        $author = new User();
        $comment = new Comment();
        $comment->setAuthor($author);
        $comment->setCreatedValue();

        $client = new MockHttpClient([new MockResponse('invalid', ['response_headers' => ['x-akismet-pro-tip: Invalid key']])]);
        $spamChecker = new SpamChecker($client, 'test');

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Unable to check for spam: invalid (Invalid key)');
        $spamChecker->getSpamScore($comment, $context);
    }

    /**
     * @dataProvider getCommentsProvider
     */
    public function testGetSpamScoreSuccess(int $expectedScore, ResponseInterface $response, Comment $comment, array $context)
    {
        $client = new MockHttpClient([$response]);
        $spamChecker = new SpamChecker($client, 'test');

        $score = $spamChecker->getSpamScore($comment, $context);
        $this->assertSame($expectedScore, $score);
    }

    /**
     * @return iterable
     */
    public function getCommentsProvider(): iterable
    {
        $context = [];
        $author = new User();
        $comment = new Comment();
        $comment->setAuthor($author);
        $comment->setCreatedValue();

        $response = new MockResponse('', ['response_headers' => ['x-akismet-pro-tip: discard']]);
        yield 'blatant_spam' => [2, $response, $comment, $context];

        $response = new MockResponse('true');
        yield 'spam' => [1, $response, $comment, $context];

        $response = new MockResponse('false');
        yield 'ham' => [0, $response, $comment, $context];
    }
}
