<?php

declare(strict_types=1);

namespace Abriolon\Scrappy;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\DomCrawler\Crawler;

class Api
{
    public function getCountry(): array
    {
        $client = HttpClient::create();
        $response = $client->request('GET', 'https://fr.wikipedia.org/wiki/Liste_des_pays_par_population');
        $html = $response->getContent();

        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML($html);
        libxml_clear_errors();

        $xpath = new \DOMXPath($dom);
        $rows = $xpath->query('//table[contains(@class, "wikitable")]/tbody/tr');

        $countries = [];
        foreach ($rows as $row) {
            $countryName = $xpath->evaluate('string(.//td[2]/span/a)', $row);
            $population = $xpath->evaluate('string(.//td[3])', $row);
            $countries[$countryName] = $population;
        }

        return $countries;
    }
}

$api = new Api();
$countries = $api->getCountry();
echo json_encode($countries);
