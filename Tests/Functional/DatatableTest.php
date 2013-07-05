<?php

namespace NetTeam\Bundle\DataTableBundle\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Client;

/**
 * Util do testowania datatable
 */
class DatatableTest extends \PHPUnit_Framework_Assert
{
    const URL = 'list';

    /**
     * @var Client
     */
    private $client;

    /**
     * @var string
     */
    private $datatableName;

    /**
     * @var array
     */
    private $params;

    /**
     * @param Client $client
     * @param string $datatableName nazwa serwisu
     */
    public function __construct(Client $client, $datatableName)
    {
        $this->client = $client;
        $this->datatableName = $datatableName;
    }

    /**
     * Przekazanie do datatable dodatkowych opcji. Niektóre tabele wymagają dodatkowych parametrów.
     *
     * @param array $params lista parametrów, klucz i wartości to stringi
     *
     * @return self
     */
    public function setQueryParams(array $params)
    {
        $this->params = $params;

        return $this;
    }

    /**
     * Sprawdzenie, czy datatable zwróciła odpowiedź JSON dla zadanego filtru.
     *
     * @param DatatableFilterInterface $filters wartości filtrów
     */
    public function assertResponse(DatatableFilterInterface $filter = null)
    {
        $url = $this->buildUrl($filter);

        $this->crawler = $this->client->request('GET', $url);
        $response = $this->client->getResponse();

        $this->assertTrue($response->isOk(), 'Response status code is not 200');
        $this->assertNotEmpty($response->getContent(), 'Response is empty');
        $this->assertNotNull(json_decode($response->getContent()), 'Response is not json');
    }

    /**
     * Buduje url dla datatable, łącznie z filtrami.
     *
     * @param DatatableFilterInterface $filter wartości filtrów
     *
     * @return string
     */
    private function buildUrl(DatatableFilterInterface $filter = null)
    {
        $url = sprintf("%s/%s", self::URL, $this->datatableName);
        $params = array();

        // parametry ustawione przez @see setQueryParams
        if (null !== $this->params) {
            foreach ($this->params as $key => $value) {
                $params[] = sprintf('%s=%s', urlencode($key), urlencode($value));
            }
        }

        // parametry z filtru
        if (null !== $filter) {
            $params[] = $filter->getQueryString($this->datatableName);
        }

        if ($params) {
            $url = $url . '?' . implode('&', $params);
        }

        return $url;
    }

    private function dumpResponse($filename = '/tmp/resp.html')
    {
        $f = fopen($filename, "wt");
        fwrite($f, $this->client->getResponse()->getContent());
        fclose($f);
    }
}
