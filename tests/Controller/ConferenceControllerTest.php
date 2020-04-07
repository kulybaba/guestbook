<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class ConferenceControllerTest extends WebTestCase //PantherTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $client->request(Request::METHOD_GET, '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h3', 'Conferences');
    }

    public function testCommentSubmission()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'testuser',
            'PHP_AUTH_PW'   => '11111111',
        ]);
        $client->request(Request::METHOD_POST, '/conference/amsterdam-title1');
        $client->submitForm('Comment', [
            'comment_form[text]' => 'Some feedback from an automated functional test',
        ]);

        $this->assertResponseRedirects();
        $client->followRedirect();
        $this->assertSelectorExists('h3:contains("Comments (2):")');
    }

    public function testConferencePage()
    {
        $client = static::createClient();
        $crawler = $client->request(Request::METHOD_GET, '/');

        $this->assertCount(4, $crawler->filter('p'));

        $client->clickLink('Title1');

        $this->assertResponseIsSuccessful();
        $this->assertPageTitleContains('Conference - Title1');
        $this->assertSelectorTextContains('h3', 'Title1');
        $this->assertSelectorExists('h3:contains("Comments (1):")');
    }
}
