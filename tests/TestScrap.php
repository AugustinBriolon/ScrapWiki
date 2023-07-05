<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Abriolon\Scrappy\Api;

class ApiTest extends TestCase
{
    public function testGetH2Elements(): void
    {
        $api = new Api();
        $crawler = $api->getCountry();

        $this->assertIsArray($crawler);
        $this->assertNotEmpty($crawler);
    }
}