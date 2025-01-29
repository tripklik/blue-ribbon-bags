# Blue Ribbon Bags Laravel Package

A Laravel package for integrating with the Blue Ribbon Bags API. This package provides a clean and type-safe way to interact with the Blue Ribbon Bags service for baggage tracking and protection services.

![Tripklik](https://tripklik.com/wp-content/uploads/2023/07/logo.svg)

Powered by [Tripklik - BY INFOTEK TECHNOLOGY FZCO](https://tripklik.com)

## Installation

You can install the package via composer:

```bash
composer require tripklik/blue-ribbon-bags
```

## Configuration

Publish the configuration file:

```bash
php artisan vendor:publish --provider="Tripklik\BlueRibbonBags\BlueRibbonBagsServiceProvider"
```

Add your Blue Ribbon Bags credentials to your `.env` file:

```env
BLUE_RIBBON_BAGS_BASE_URL=https://validation-api.blueribbonbags.com/api/
BLUE_RIBBON_BAGS_AUTH_TOKEN=your-auth-token
```

## Usage

### Getting Available Products

```php
use Tripklik\BlueRibbonBags\BlueRibbonBagsClient;

public function getProducts(BlueRibbonBagsClient $client)
{
    $products = $client->getProducts('USD');
    
    foreach ($products as $product) {
        echo "Product: {$product->productName}\n";
        echo "Price: {$product->productPrice} {$product->currencyCode}\n";
        echo "Coverage: {$product->bagCoverage}\n";
    }
}
```

### Getting Available Currencies

```php
use Tripklik\BlueRibbonBags\BlueRibbonBagsClient;

public function getCurrencies(BlueRibbonBagsClient $client)
{
    $currencies = $client->getCurrencies();
    
    foreach ($currencies as $currency) {
        echo "Currency: {$currency->currencyName} ({$currency->currencyCode})\n";
    }
}
```

### Purchasing a Service

```php
use Tripklik\BlueRibbonBags\Purchase\Request\PurchaseRequest;
use Tripklik\BlueRibbonBags\Purchase\Request\Passenger;
use Tripklik\BlueRibbonBags\Purchase\Request\PassengersCollection;

public function purchaseService(BlueRibbonBagsClient $client)
{
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
        ),
        new Passenger(
            orderSequence: 2,
            lastName: 'Smith',
            firstName: 'Jane',
            email: 'jane@example.com',
            airlineCode: 'AA',
            airlineCodeType: 'IATA',
            airlineConfirmationNumber: 'ABC456',
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
        flightDetails: 'Flight details here',
        departureDt: new DateTime('2024-02-01 10:00:00'),
        lastArrivalDt: new DateTime('2024-02-02 15:00:00'),
        currencyCode: 'USD',
        agentEmailSend: 'agent@example.com',
        passengerList: $passengers
    );

    $response = $client->purchaseService($request);

    echo "Service Number: {$response->serviceNumber}\n";
    echo "Total Price: {$response->totalPrice}\n";
    echo "Status: {$response->statusCode}\n";
}
```

## Error Handling

The package provides detailed error information in the response objects. Always check the `status` and `errors` properties of the response:

```php
$response = $client->purchaseService($request);

if (!$response->status) {
    foreach ($response->errors as $error) {
        // Handle errors
        echo "Error: {$error}\n";
    }
}
```

## Available Methods

### BlueRibbonBagsClient

- `getProducts(string $currencyCode)`: Get available products for a specific currency
- `getCurrencies()`: Get list of available currencies
- `purchaseService(PurchaseRequest $request)`: Purchase a baggage protection service

## Contributing

Please see [CONTRIBUTING.md](CONTRIBUTING.md) for details.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.