<?php

namespace App\Services;

use Google\Auth\ApplicationDefaultCredentials;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\HandlerStack;
use Illuminate\Support\Facades\Log;

class GoogleAdsConversionService
{
    protected $developerToken;
    protected $loginCustomerId;
    protected $clientCustomerId;
    protected $credentialsPath;

    public function __construct()
    {
        $this->developerToken = env('GOOGLE_ADS_DEVELOPER_TOKEN');
        $this->loginCustomerId = env('GOOGLE_ADS_LOGIN_CUSTOMER_ID');
        $this->clientCustomerId = env('GOOGLE_ADS_CLIENT_CUSTOMER_ID');
        $this->credentialsPath = env('GOOGLE_APPLICATION_CREDENTIALS');
    }

    /**
     * Envia uma conversão para o Google Ads
     *
     * @param string $conversionName Nome da conversão configurada no Google Ads
     * @param string $leadCode Código único do lead (para rastreio)
     * @param float|null $value Valor da conversão (ex: 300.00)
     * @param string $currency Moeda (padrão BRL)
     * @return array
     */
    public function sendConversion(string $conversionName, string $leadCode, ?float $value = null, string $currency = 'BRL')
    {
        try {
            // 🔐 Autenticação com a conta de serviço
            $scopes = ['https://www.googleapis.com/auth/adwords'];
            $middleware = ApplicationDefaultCredentials::getMiddleware($scopes);
            $stack = HandlerStack::create();
            $stack->push($middleware);

            $client = new Client([
                'handler' => $stack,
                'auth' => 'google_auth',
            ]);

            $conversionTime = now()->toIso8601String();

            $payload = [
                'conversionAdjustments' => [],
                'conversions' => [
                    [
                        'conversionAction' => "customers/{$this->clientCustomerId}/conversionActions/{$conversionName}",
                        'conversionDateTime' => $conversionTime,
                        'conversionValue' => $value ?? 0.0,
                        'currencyCode' => $currency,
                        'orderId' => $leadCode, // usamos o código do lead como ID único
                    ]
                ]
            ];

            $response = $client->post("https://googleads.googleapis.com/v17/customers/{$this->clientCustomerId}:uploadClickConversions", [
                'headers' => [
                    'developer-token' => $this->developerToken,
                    'login-customer-id' => $this->loginCustomerId,
                    'Content-Type' => 'application/json',
                ],
                'json' => $payload,
            ]);

            $result = json_decode($response->getBody()->getContents(), true);
            Log::info("🎯 Conversão enviada para Google Ads", [
                'conversion' => $conversionName,
                'lead_code' => $leadCode,
                'response' => $result,
            ]);

            return ['success' => true, 'response' => $result];
        } catch (RequestException $e) {
            $error = $e->hasResponse()
                ? $e->getResponse()->getBody()->getContents()
                : $e->getMessage();

            Log::error("❌ Erro ao enviar conversão para Google Ads", [
                'conversion' => $conversionName,
                'lead_code' => $leadCode,
                'error' => $error,
            ]);

            return ['success' => false, 'error' => $error];
        }
    }
}
