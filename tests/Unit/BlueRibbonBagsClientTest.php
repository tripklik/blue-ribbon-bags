<?php

namespace Tripklik\BlueRibbonBags\Tests\Unit;

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Middleware;
use Tripklik\BlueRibbonBags\BlueRibbonBagsClient;
use Tripklik\BlueRibbonBags\Data\Currency;
use Tripklik\BlueRibbonBags\Data\Product;
use Tripklik\BlueRibbonBags\Purchase\Request\Passenger;
use Tripklik\BlueRibbonBags\Purchase\Request\PassengersCollection;
use Tripklik\BlueRibbonBags\Purchase\Request\PurchaseRequest;
use DateTime;

class BlueRibbonBagsClientTest extends TestCase
{
    private array $container = [];
    private MockHandler $mock;
    private BlueRibbonBagsClient $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mock = new MockHandler();
        $handlerStack = HandlerStack::create($this->mock);

        // Add history middleware
        $history = Middleware::history($this->container);
        $handlerStack->push($history);

        $this->client = new BlueRibbonBagsClient(
            'https://api.test.com',
            'test-token',
            new Client(['handler' => $handlerStack])
        );
    }

    public function testGetProducts()
    {
        // Mock response
        $this->mock->append(new Response(200, [], json_encode([
            'Status' => true,
            'Data' => [
                [
                    'ProductCode' => 'GOLD',
                    'ProductName' => 'Gold Package',
                    'ProductPrice' => 99.99,
                    'CurrencyCode' => 'USD',
                    'CurrencyName' => 'US Dollar',
                    'BagCoverage' => 2500.00,
                    'CurrencySymbol' => '$',
                    'AlternateCurrency' => null,
                    'Caption' => null,
                    'ProductTypeCode' => 'STD'
                ]
            ]
        ])));

        $products = $this->client->getProducts('USD');

        $this->assertCount(1, $products);
        $this->assertInstanceOf(Product::class, $products->first());
        $this->assertEquals('GOLD', $products->first()->productCode);

        // Assert request was made correctly
        $request = $this->container[0]['request'];
        $this->assertEquals('POST', $request->getMethod());
        $this->assertEquals('Data/GetProductList', trim($request->getUri()->getPath(), '/'));
    }

    public function testGetCurrencies()
    {
        // Mock response
        $this->mock->append(new Response(200, [], json_encode([
            'Status' => true,
            'Data' => [
                [
                    'CurrencyCode' => 'USD',
                    'CurrencyName' => 'US Dollar',
                    'CurrencySymbol' => '$',
                    'Rate' => 1.0,
                    'BusinessUnitPayoutFactor' => null
                ]
            ]
        ])));

        $currencies = $this->client->getCurrencies();

        $this->assertCount(1, $currencies);
        $this->assertInstanceOf(Currency::class, $currencies->first());
        $this->assertEquals('USD', $currencies->first()->currencyCode);

        // Assert request was made correctly
        $request = $this->container[0]['request'];
        $this->assertEquals('POST', $request->getMethod());
        $this->assertEquals('Data/GetAvailableCurrenciesList', trim($request->getUri()->getPath(), '/'));
    }

    public function testPurchaseService()
    {
        // Mock response
        $this->mock->append(new Response(200, [], json_encode([
            'Status' => true,
            'StatusCode' => 'SUCCESS',
            'Data' => [
                'ServiceNumber' => 'BRB123456',
                'TotalPrice' => 99.99,
                'TotalCharge' => 99.99
            ],
            'Errors' => [],
            'Warnings' => []
        ])));

        $passengers = new PassengersCollection([
            new Passenger(
                orderSequence: 1,
                lastName: 'Smith',
                firstName: 'John',
                email: 'john@example.com',
                airlineCode: 'AA',
                airlineCodeType: 'IATA',
                airlineConfirmationNumber: 'ABC123',
                phone: '',
                sendSMS: true
            )
        ]);

        $request = new PurchaseRequest(
            productCode: 'GOLD',
            isInternational: true,
            promoCode: '',
            userLogin: '',
            userPassword: '',
            customerReferenceNumber: '',
            replaceServiceNumberWithCRN: false,
            flightDetails: 'Test Flight',
            departureDt: new DateTime('2024-02-01 10:00:00'),
            lastArrivalDt: new DateTime('2024-02-02 15:00:00'),
            currencyCode: 'USD',
            agentEmailSend: 'agent@example.com',
            passengerList: $passengers
        );

        $response = $this->client->purchaseService($request);

        $this->assertEquals('BRB123456', $response->serviceNumber);
        $this->assertEquals(99.99, $response->totalPrice);
        $this->assertTrue($response->status);

        // Assert request was made correctly
        $request = $this->container[0]['request'];
        $this->assertEquals('POST', $request->getMethod());
        $this->assertEquals('Service/Purchase', trim($request->getUri()->getPath(), '/'));
    }

    public function testHandlesErrors()
    {
        // Mock error response
        $this->mock->append(new Response(200, [], json_encode([
            'Status' => false,
            'StatusCode' => 'ERROR',
            'Data' => null,
            'Errors' => ['Invalid product code'],
            'Warnings' => []
        ])));

        $passengers = new PassengersCollection([
            new Passenger(
                orderSequence: 1,
                lastName: 'Smith',
                firstName: 'John',
                email: 'john@example.com',
                airlineCode: 'AA',
                airlineCodeType: 'IATA',
                airlineConfirmationNumber: 'ABC123',
                phone: '',
                sendSMS: true
            )
        ]);

        $request = new PurchaseRequest(
            productCode: 'INVALID',
            isInternational: true,
            promoCode: '',
            userLogin: '',
            userPassword: '',
            customerReferenceNumber: '',
            replaceServiceNumberWithCRN: false,
            flightDetails: 'Test Flight',
            departureDt: new DateTime('2024-02-01 10:00:00'),
            lastArrivalDt: new DateTime('2024-02-02 15:00:00'),
            currencyCode: 'USD',
            agentEmailSend: 'agent@example.com',
            passengerList: $passengers
        );

        $response = $this->client->purchaseService($request);

        $this->assertFalse($response->status);
        $this->assertCount(1, $response->errors);
        $this->assertEquals('Invalid product code', $response->errors[0]);
    }
}