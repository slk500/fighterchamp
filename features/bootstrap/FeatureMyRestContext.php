<?php

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use PHPUnit\Framework\TestCase;
use Tests\Database;
use Tests\DatabaseHelper;

/**
 * Defines application features from the specific context.
 */
class FeatureMyRestContext implements Context, \Behat\Behat\Context\SnippetAcceptingContext
{
    /**@var $response \GuzzleHttp\Psr7\Response**/
    public $response;
    public $client;
    public $content;
    public $requestPayload;
    public $headers;
    /**@var $req GuzzleHttp\Psr7\Request**/
    public $request;

    private $databaseHelper;

    public function __construct($baseUri = 'http://nginx/')
    {
        $this->client = new Client([
            'base_uri' => $baseUri,
            'http_errors' => false,
            'headers' => [
                'Content-type' => 'application/json',
                "X-Requested-With" => "XMLHttpRequest"
            ]
        ]);

        $this->databaseHelper = new DatabaseHelper(new Database('dev'));
    }

    /**
     * @BeforeScenario
     */
    public function clearData()
    {
        $this->databaseHelper->truncateAllTables();
    }

    /**
     * @Given /^I have the payload:$/
     */
    public function iHaveThePayload(PyStringNode $requestPayload)
    {
        if (!$this->isJson($requestPayload)) {
            throw new Exception('Not Json');
        }

        $this->requestPayload = $requestPayload;
    }

    public function isJson($string)
    {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }

    /**
     * @When I send a :method request to :uri
     */
    public function iSendARequestTo($method, $uri)
    {
        $request = new Request($method, $uri);

        $this->response = $this->client->send(
            $request,
            [
                'body' => $this->requestPayload
            ]
        );

        $this->content = $this->response->getBody()->getContents();
    }

    /**
     * @Then the response status code should be :statusCode
     */
    public function theResponseStatusCodeShouldBe($statusCode)
    {
        TestCase::assertEquals(
            $statusCode,
            $this->response->getStatusCode()
        );
    }

    /**
     * @Then /^the response should be in JSON$/
     */
    public function theResponseShouldBeInJson()
    {
        TestCase::assertJson($this->content);
    }

    /**
     * @Then the :property property should exist
     */
    public function thePropertyShouldExist($property)
    {
        TestCase::assertArrayHasKey($property, $this->getContentDecode());
    }

    /**
     * @Then the response :headerName header should be :expectedHeaderValue
     */
    public function theResponseHeaderShouldBe($headerName, $expectedHeaderValue)
    {
        TestCase::assertEquals(
            $this->response->getHeaderLine($headerName),
            $expectedHeaderValue
        );
    }

    /**
     * @When the :property property should be equal to :expectedPropertyValue
     */
    public function thePropertyShouldBeEqualTo($property, $expectedPropertyValue)
    {
        $data = json_decode($this->content, true);

        if ($expectedPropertyValue == 'true') {
            $expectedPropertyValue = true;
        } elseif ($expectedPropertyValue == 'false') {
            $expectedPropertyValue = false;
        }

        TestCase::assertEquals($data[$property], $expectedPropertyValue);
    }

    /**
     * @Then the :propert1 property should contain :numberOfElements elements
     */
    public function thePropertyInShouldContainElements($property1, $numberOfElements)
    {
        TestCase::assertEquals(
            count($this->getContentDecode()[$property1]),
            $numberOfElements
        );
    }

    public function getContentDecode(): array
    {
        return json_decode($this->content, true);
    }

    /**
     * @When the :arg1 property in :arg2 should be equal to :expectedValue
     */
    public function thePropertyInShouldBeEqualTo($arg1, $arg2, $expectedValue)
    {
        TestCase::assertEquals($this->getContentDecode()[$arg2][0][$arg1], $expectedValue);
    }

    /**
     * @Given I set the :key header to be :value
     */
    public function iSetTheHeaderToBe($key, $value)
    {
        $this->headers = [$key => $value];
    }

    /**
     * @Given /^the response should be$/
     */
    public function theResponseShouldBe(PyStringNode $string)
    {
        TestCase::assertJsonStringEqualsJsonString(
            $this->content,
            $string->getRaw()
        );
    }

    /**
     * @Then /^the response should contain "([^"]*)" field which is not empty$/
     */
    public function theResponseShouldContainFieldWhichIsNotEmpty($arg1)
    {
        TestCase::assertNotEmpty($this->getContentDecode()['data'][$arg1]);
    }
}
