<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use UnexpectedValueException;

class RatesMicroserviceClient
{
    /**
     * @return array{date: string, rates: array<string, float>, stale: bool}
     */
    public function copRates(): array
    {
        try {
            $data = Http::acceptJson()
                ->withHeaders(['X-Service-Token' => (string) config('services.rates_service.token')])
                ->timeout(10)
                ->retry(2, 200)
                ->get(rtrim(config('services.rates_service.base_url'), '/').'/api/rates')
                ->throw()
                ->json();
        } catch (ConnectionException $exception) {
            return $this->frankfurterRates();
        } catch (RequestException $exception) {
            if ($exception->response->serverError()) {
                return $this->frankfurterRates();
            }

            throw $exception;
        }

        if (! is_array($data) || ($data['base'] ?? null) !== 'COP' || ! is_string($data['date'] ?? null)
            || ! is_numeric($data['rates']['USD'] ?? null) || ! is_numeric($data['rates']['EUR'] ?? null)) {
            throw new UnexpectedValueException('Rates Service devolvió tasas COP inesperadas.');
        }

        return [
            'date' => $data['date'],
            'rates' => ['USD' => (float) $data['rates']['USD'], 'EUR' => (float) $data['rates']['EUR']],
            'stale' => (bool) ($data['stale'] ?? false),
        ];
    }

    private function frankfurterRates(): array
    {
        return Cache::remember('frankfurter.cop_rates', now()->addHours(12), function () {
            $rows = Http::acceptJson()
                ->timeout(8)
                ->get(rtrim(config('services.frankfurter.base_url'), '/').'/rates', [
                    'base' => 'COP',
                    'quotes' => 'USD,EUR',
                ])
                ->throw()
                ->json();

            $rates = [];
            $date = null;
            foreach (is_array($rows) ? $rows : [] as $row) {
                if (is_array($row) && in_array($row['quote'] ?? null, ['USD', 'EUR'], true)
                    && is_numeric($row['rate'] ?? null) && (float) $row['rate'] > 0) {
                    $rates[$row['quote']] = (float) $row['rate'];
                    $date ??= $row['date'] ?? null;
                }
            }

            if (! is_string($date) || ! isset($rates['USD'], $rates['EUR'])) {
                throw new UnexpectedValueException('Frankfurter devolvió tasas COP incompletas.');
            }

            return ['date' => $date, 'rates' => $rates, 'stale' => false];
        });
    }
}
