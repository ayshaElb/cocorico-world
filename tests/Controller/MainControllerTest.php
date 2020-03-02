<?php


namespace App\Tests\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MainControllerTest extends WebTestCase
{
    
    public function testPagesAccess()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'username',
            'PHP_AUTH_PW'   => 'pa$$word',
        ]);
        $client->request('GET', '/');

         // Affirme que le status code de la requête est entre 200 et 299
         $this->assertEquals(200, $client->getResponse()->isRedirect());
    }



}