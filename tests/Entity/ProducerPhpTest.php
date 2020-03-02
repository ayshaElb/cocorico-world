<?php

namespace App\Tests\Entity;

use App\Entity\User;
use App\Entity\Producer;
use PHPUnit\Framework\TestCase;

class ProducerPhpTest extends TestCase
{
    public function testProducerCreate()
    {
                        
        $producer = new Producer(); // create Producer Object
        $producer  ->setSocialReason("Test")
                    ->setEmail("test@test.com")
                    ->setSiretNumber("12345678912345")
                    ->setAddress("1 rue du test")
                    ->setPostalCode("78000")
                    ->setCity("Test")
                    ->setFirstname("Test")
                    ->setLastname("Test")
                    ->setTelephone("0606060606")
                    ->setStatus("Test")
                    ->setAvatar("Test")
                    ->setDescription("Test")
                    ->setUser(New User());
        $this->assertEquals("Test", $producer->getSocialReason());
        $this->assertEquals("test@test.com", $producer->getEmail());
        $this->assertEquals("12345678912345", $producer->getSiretNumber());
        $this->assertEquals("1 rue du test", $producer->getAddress());
        $this->assertEquals("78000", $producer->getPostalCode());
        $this->assertEquals("Test", $producer->getCity());
        $this->assertEquals("Test", $producer->getFirstName());
        $this->assertEquals("Test", $producer->getLastname());
        $this->assertEquals("0606060606", $producer->getTelephone());
        $this->assertEquals("Test", $producer->getStatus());
        $this->assertEquals("Test", $producer->getAvatar());
        $this->assertEquals("Test", $producer->getDescription());
        $this->assertInstanceOf(User::class, $producer->getUser());
        
    }

    Public Function AddProductTest()
    {
        $producer = new Producer();
        $producer->addProduct();
        $this->assertInstanceOf(Product::class, $producer->addProduct());
    }

    Public Function AddRemoveProductTest()
    {
        $producer = new Producer();
        $producer->removeProduct();
        $this->assertInstanceOf(Product::class, $producer->removeProduct());
    }
    
}
