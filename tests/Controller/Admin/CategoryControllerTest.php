<?php

namespace Tests\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class CategoryControllerTest extends WebTestCase {
    private $client;

    public function __construct() {
        $this->client = static::createClient();    
    }
    
    public function testAnonymousUserCannotSeeAndModifyCategoryData(): void
    {
        // Request a specific page
        $this->client->request('GET', '/admin/category');

        // Validate a successful response and some content
        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

}