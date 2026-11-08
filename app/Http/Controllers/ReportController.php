<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\PropertyType;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $baseQuery = $this->scopedPropertiesQuery($user);

        $totalProperties = (clone $baseQuery)->count();
        $propertiesForSale = (clone $baseQuery)->where('operation_type', 'venta')->count();
        $propertiesForRent = (clone $baseQuery)->where('operation_type', 'alquiler')->count();
        $availableProperties = (clone $baseQuery)->where('status', 'disponible')->count();
        $soldProperties = (clone $baseQuery)->where('status', 'vendido')->count();
        $rentedProperties = (clone $baseQuery)->where('status', 'alquilado')->count();

        $propertiesByType = PropertyType::withCount([
            'properties' => $this->scopeQueryTo($user),
        ])->orderByDesc('properties_count')->get();

        $propertiesByStatus = (clone $baseQuery)
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->orderByDesc('count')
            ->get();

        $propertiesByOperation = (clone $baseQuery)
            ->selectRaw('operation_type, count(*) as count')
            ->groupBy('operation_type')
            ->orderByDesc('count')
            ->get();

        $avgPriceByType = PropertyType::selectRaw('property_types.id, property_types.name, AVG(properties.price) as avg_price')
            ->join('properties', 'properties.property_type_id', '=', 'property_types.id')
            ->when(! $user->isAdmin(), fn (Builder $query): Builder => $query->where('properties.user_id', $user->id))
            ->groupBy('property_types.id', 'property_types.name')
            ->orderByDesc('avg_price')
            ->get();

        $recentProperties = (clone $baseQuery)
            ->with(['propertyType', 'user'])
            ->latest()
            ->limit(10)
            ->get();

        return view('dashboard.reports.index', compact(
            'totalProperties',
            'propertiesForSale',
            'propertiesForRent',
            'availableProperties',
            'soldProperties',
            'rentedProperties',
            'propertiesByType',
            'propertiesByStatus',
            'propertiesByOperation',
            'avgPriceByType',
            'recentProperties'
        ));
    }

    public function propertiesPdf(Request $request): Response
    {
        $properties = $this->scopedPropertiesQuery($request->user())
            ->with(['propertyType', 'user'])
            ->latest()
            ->get();

        $generatedAt = now();

        $pdf = Pdf::loadView('reports.properties-pdf', [
            'properties' => $properties,
            'user' => $request->user(),
            'generatedAt' => $generatedAt,
        ])->setPaper('a4', 'landscape');

        return $pdf->download('reporte-propiedades-'.$generatedAt->format('Y-m-d').'.pdf');
    }

    /** Agents can report only on their own properties; admins can report on all. */
    private function scopedPropertiesQuery($user): Builder
    {
        $query = Property::query();

        if (! $user->isAdmin()) {
            $query->where('user_id', $user->id);
        }

        return $query;
    }

    /** @return callable(Builder): Builder */
    private function scopeQueryTo($user): callable
    {
        return function (Builder $query) use ($user): Builder {
            if ($user->isAdmin()) {
                return $query;
            }

            return $query->where('user_id', $user->id);
        };
    }
}
