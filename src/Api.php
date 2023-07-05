<?php

declare(strict_types=1);

namespace Abriolon\Scrappy;

use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpClient\HttpClient;

class Api
{
    public function getCountry(): array
    {
        $client = HttpClient::create();
        $response = $client->request('GET', 'https://fr.wikipedia.org/wiki/Liste_des_pays_par_population');
        $html = $response->getContent();

        $crawler = new Crawler($html);
        $crawler = $crawler->filter('table tr td:nth-child(2) a');

        $countries = [];
        foreach ($crawler as $domElement) {
            $countries[] = $domElement->nodeValue;
        }

        return $countries;
    }
}

$api = new Api();
$crawler = $api->getCountry();
echo json_encode($crawler);