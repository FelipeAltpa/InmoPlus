<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\RatesMicroserviceClient;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use UnexpectedValueException;

class ExchangeRateController extends Controller
{
    public function __invoke(Request $request, RatesMicroserviceClient $exchangeRates): JsonResponse
    {
        $validated = $request->validate([
            'quote' => ['required', 'string', 'in:USD,EUR'],
        ]);

        try {
            $data = $exchangeRates->copRates();
        } catch (ConnectionException|RequestException|UnexpectedValueException $exception) {
            report($exception);

            return response()->json([
                'message' => 'No se pudo consultar el tipo de cambio en este momento. Intenta nuevamente más tarde.',
                'source' => 'Frankfurter',
            ], 503);
        }

        $quote = $validated['quote'];

        return response()->json([
            'date' => $data['date'],
            'base' => 'COP',
            'quote' => $quote,
            'rate' => $data['rates'][$quote],
            'stale' => $data['stale'] ?? false,
            'source' => 'Frankfurter',
            'source_url' => 'https://frankfurter.dev/',
            'message' => 'Tasa de referencia diaria; no corresponde a una cotización bancaria en tiempo real.',
        ]);
    }
}
