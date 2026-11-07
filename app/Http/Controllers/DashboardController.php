<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\PropertyType;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Clients use the public catalog instead of management statistics.
        if ($user->isClient()) {
            return redirect()->route('home');
        }

        $baseQuery = $user->isAdmin() ? Property::query() : Property::where('user_id', $user->id);

        $totalProperties = (clone $baseQuery)->count();
        $propertiesForSale = (clone $baseQuery)->where('operation_type', 'venta')->count();
        $propertiesForRent = (clone $baseQuery)->where('operation_type', 'alquiler')->count();
        $availableProperties = (clone $baseQuery)->where('status', 'disponible')->count();

        $propertiesByType = (clone $baseQuery)
            ->select('property_type_id')
            ->selectRaw('count(*) as count')
            ->groupBy('property_type_id')
            ->pluck('count', 'property_type_id');

        $typeNames = PropertyType::whereIn('id', $propertiesByType->keys())
            ->pluck('name', 'id');

        $chartLabels = $typeNames->values()->all();
        $chartData = $typeNames->keys()->map(fn ($id) => $propertiesByType[$id] ?? 0)->all();

        if ($user->isAdmin()) {
            $recentProperties = Property::with(['propertyType', 'user'])
                ->latest()
                ->limit(10)
                ->get();
        } else {
            $recentProperties = Property::with(['propertyType'])
                ->where('user_id', $user->id)
                ->latest()
                ->limit(10)
                ->get();
        }

        return view('dashboard.index', compact(
            'totalProperties',
            'propertiesForSale',
            'propertiesForRent',
            'availableProperties',
            'recentProperties',
            'chartLabels',
            'chartData'
        ));
    }
}
