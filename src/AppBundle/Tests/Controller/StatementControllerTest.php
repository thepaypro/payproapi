<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class StatementControllerTest extends WebTestCase
{
    public function testGetstatement()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/statements');
    }

}
