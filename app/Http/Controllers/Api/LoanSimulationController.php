<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class LoanSimulationController extends Controller
{
    public function __invoke(Request $request)
    {
        $data = $request->validate([
            'principal' => ['required', 'numeric', 'min:1000000', 'max:10000000000'],
            'annual_rate' => ['required', 'numeric', 'min:0', 'max:100'],
            'months' => ['required', 'integer', 'min:1', 'max:360'],
        ]);
        $url = rtrim((string) config('services.loan_service.url'), '/');
        $token = (string) config('services.loan_service.token');
        if ($url === '' || $token === '') {
            return response()->json(['message' => 'Simulador no configurado.'], 503);
        }
        try {
            $response = Http::timeout(5)->acceptJson()->withHeaders(['X-Service-Token' => $token])->post($url.'/api/simulations', $data);

            return response()->json($response->json(), $response->status());
        } catch (\Throwable $exception) {
            report($exception);

            return response()->json(['message' => 'El simulador está temporalmente fuera de servicio.'], 503);
        }
    }
}
