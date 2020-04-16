<?php

namespace App\Tests;

use Symfony\Component\Mime\Email;

class MailerTest extends Email
{
    public function testMailerAssertions()
    {
        $client = static::createClient();
        $client->request('GET', '/');
        $this->assertEmailCount(1);
        $event = $this->getMailerEvent(0);
        $this->assertEmailIsQueued($event);
        $email = $this->getMailerMessage(0);
        $this->assertEmailHeaderSame($email, 'To', 'test@example.com');
        $this->assertEmailTextBodyContains($email, 'Bar');
        $this->assertEmailAttachmentCount($email, 1);
    }
}
