<?php

use Symfony\Component\HttpClient\HttpClient;

require '../vendor/autoload.php';

$client = HttpClient::create();
$response = $client->request('GET', 'https://fr.wikipedia.org/wiki/Liste_des_pays_par_population');

if ($response->getStatusCode() === 200) {
    $content = $response->getContent();

    // Utilisation de l'analyseur DOM de PHP pour extraire les données
    $dom = new DOMDocument();
    @$dom->loadHTML($content);
    $xpath = new DOMXPath($dom);

    $rows = $xpath->query('//table[contains(@class, "wikitable")]/tbody/tr');

    if ($rows->length > 0) {
        echo '<ul>' . PHP_EOL;

        foreach ($rows as $index => $row) {
            $countryNode = $xpath->query('.//span[@class="datasortkey"]/a', $row)->item(0);
            $populationNodes = $xpath->query('.//td[@align="right"]', $row);

            if ($countryNode && $populationNodes->length >= 2) {
                $country = $countryNode->textContent;
                $population1 = $populationNodes->item(0)->textContent;
                $population2 = $populationNodes->item(1)->textContent;

                echo '<li>' . PHP_EOL;
                echo '<span class="country">' . $country . '</span>' . PHP_EOL;
                echo '<span class="population">' . $population1 . '</span>' . PHP_EOL;
                echo '<span class="population">' . $population2 . '</span>' . PHP_EOL;
                echo '</li>' . PHP_EOL;
            }
        }

        echo '</ul>';
    } else {
        echo 'Aucune donnée trouvée.';
    }
} else {
    echo 'Erreur lors de la requête HTTP.';
}
