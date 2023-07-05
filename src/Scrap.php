<?php

require '../vendor/autoload.php';

use Symfony\Component\HttpClient\HttpClient;

class Scraper
{
    public function scrapePage(): array
    {
        $httpClient = HttpClient::create();
        $response = $httpClient->request('GET', 'https://fr.wikipedia.org/wiki/Liste_des_pays_par_population');
        $content = $response->getContent();

        $dom = new DOMDocument();
        @$dom->loadHTML($content);

        $xpath = new DOMXPath($dom);
        $elements = $xpath->query('.//span[@class="datasortkey"]/a');

        $data = [];
        foreach ($elements as $element) {
            if ($element instanceof DOMElement) {
                $name = $element->textContent;
                $value = '';
                $nextSibling = $element->nextSibling;

                if ($nextSibling instanceof DOMElement) {
                    $value = $nextSibling->textContent;
                }

                $data[$name] = $value;
            }
        }

        return $data;
    }
}

$scraper = new Scraper();
$result = $scraper->scrapePage();

print_r($result);

