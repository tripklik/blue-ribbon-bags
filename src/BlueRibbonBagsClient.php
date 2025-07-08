<?php

namespace Tripklik\BlueRibbonBags;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Tripklik\BlueRibbonBags\Data\Currency;
use Tripklik\BlueRibbonBags\Data\Product;
use Tripklik\BlueRibbonBags\Purchase\Request\PurchaseRequest;
use Tripklik\BlueRibbonBags\Purchase\Response\PurchaseResponse;
use Illuminate\Support\Collection;

class BlueRibbonBagsClient
{
    private Client $client;

    public function __construct(
        private readonly string $baseUrl,
        private readonly string $authToken,
    ) {
        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            'headers' => [
                'Authorization' => "Bearer $this->authToken",
                'Content-Type' => 'application/json',
            ],
        ]);
    }

    /**
     * Get available products for a specific currency using POST method
     *
     * @param string|null $currencyCode
     * @return Collection<Product>
     * @throws GuzzleException
     */
    public function getProducts(string $currencyCode = null): Collection
    {
        $payload = [];

        if (!is_null($currencyCode)) {
            $payload['CurrencyCode'] = $currencyCode;
        }

        $response = $this->client->post('Data/GetProductList', [
            'json' => $payload,
        ]);

        $data = json_decode($response->getBody()->getContents(), true);
        return collect($data['Data'])->map(fn ($product) => Product::fromArray($product));
    }

    /**
     * Get available currencies using POST method
     *
     * @return Collection
     * @throws GuzzleException
     */
    public function getCurrencies(): Collection
    {
        $response = $this->client->post('Data/GetAvailableCurrenciesList', [
            'json' => [], // Empty payload for POST request
        ]);

        $data = json_decode($response->getBody()->getContents(), true);
        return collect($data['Data'])->map(fn ($currency) => Currency::fromArray($currency));
    }

    /**
     * Purchase service using POST method
     *
     * @param PurchaseRequest $request
     * @return PurchaseResponse
     * @throws GuzzleException
     */
    public function purchaseService(PurchaseRequest $request): PurchaseResponse
    {
        $response = $this->client->post('Service/Purchase', [
            'json' => $request->toArray(),
        ]);

        $data = json_decode($response->getBody()->getContents(), true);
        return PurchaseResponse::fromArray($data);
    }


    /**
     *  Make a standardized POST request to the API
     *
     * @param string $endpoint
     * @param array $data
     * @return array
     * @throws GuzzleException
     */
    private function post(string $endpoint, array $data = []): array
    {
        $response = $this->client->post($endpoint, [
            'json' => $data,
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }
}