<?php

namespace Tripklik\BlueRibbonBags;

use GuzzleHttp\Client;
use Tripklik\BlueRibbonBags\Data\Currency;
use Tripklik\BlueRibbonBags\Data\Product;
use Tripklik\BlueRibbonBags\Purchase\Request\PurchaseRequest;
use Tripklik\BlueRibbonBags\Purchase\Response\PurchaseResponse;

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
                'Authorization' => "Bearer {$this->authToken}",
                'Accept' => 'application/json',
            ],
        ]);
    }

    public function getProducts(string $currencyCode): array
    {
        $response = $this->client->get('/api/products', [
            'query' => ['currencyCode' => $currencyCode],
        ]);

        $data = json_decode($response->getBody()->getContents(), true);
        return array_map(fn (array $product) => Product::fromArray($product), $data);
    }

    public function getCurrencies(): array
    {
        $response = $this->client->get('/api/currencies');
        $data = json_decode($response->getBody()->getContents(), true);
        return array_map(fn (array $currency) => Currency::fromArray($currency), $data);
    }

    public function purchaseService(PurchaseRequest $request): PurchaseResponse
    {
        $response = $this->client->post('/api/purchase', [
            'json' => $request->toArray(),
        ]);

        $data = json_decode($response->getBody()->getContents(), true);
        return PurchaseResponse::fromArray($data);
    }
}
