<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Reporte de Propiedades — {{ $generatedAt->format('Y-m-d') }}</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 18mm 16mm 16mm 16mm;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10pt;
            color: #1f2937;
        }

        .header {
            border-bottom: 3px solid #4f46e5;
            padding-bottom: 10px;
            margin-bottom: 16px;
            overflow: hidden;
        }

        .header .brand {
            font-size: 20pt;
            font-weight: bold;
            color: #4f46e5;
        }

        .header .brand span {
            color: #111827;
        }

        .header .title {
            font-size: 13pt;
            font-weight: bold;
            color: #374151;
        }

        .meta {
            margin-bottom: 14px;
            width: 100%;
        }

        .meta td {
            padding: 2px 0;
            font-size: 10pt;
        }

        .meta .label {
            font-weight: bold;
            color: #4b5563;
            width: 130px;
        }

        table.data {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        table.data th {
            background: #4f46e5;
            color: #ffffff;
            font-weight: bold;
            font-size: 9pt;
            text-align: left;
            padding: 6px 7px;
            border: 1px solid #3730a3;
        }

        table.data td {
            padding: 5px 7px;
            border: 1px solid #d1d5db;
            font-size: 9pt;
            vertical-align: top;
        }

        table.data tr:nth-child(even) td {
            background: #f3f4f6;
        }

        .col-id   { width: 6%; }
        .col-type { width: 12%; }
        .col-city { width: 14%; }
        .col-price { width: 14%; }
        .col-op   { width: 10%; }
        .col-status { width: 12%; }
        .col-agent { width: 16%; }

        .empty {
            text-align: center;
            color: #6b7280;
            padding: 20px 0;
            font-style: italic;
        }

        .footer {
            margin-top: 18px;
            padding-top: 8px;
            border-top: 1px solid #9ca3af;
            font-size: 8pt;
            color: #6b7280;
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="header">
        <div class="brand">{{ config('app.name', 'InmoPlus') }}</div>
        <div class="title">Reporte de Propiedades</div>
    </div>

    <table class="meta">
        <tr>
            <td class="label">Fecha de generación:</td>
            <td>{{ $generatedAt->locale('es')->isoFormat('D [de] MMMM [de] YYYY') }} — {{ $generatedAt->format('H:i') }}</td>
        </tr>
        <tr>
            <td class="label">Generado por:</td>
            <td>{{ $user->name }} ({{ $user->email }})</td>
        </tr>
        <tr>
            <td class="label">Total de propiedades:</td>
            <td>{{ count($properties) }}</td>
        </tr>
    </table>

    @if (count($properties) > 0)
        <table class="data">
            <thead>
                <tr>
                    <th class="col-id">ID</th>
                    <th>Título</th>
                    <th class="col-type">Tipo</th>
                    <th class="col-city">Ciudad</th>
                    <th class="col-price">Precio</th>
                    <th class="col-op">Operación</th>
                    <th class="col-status">Estado</th>
                    <th class="col-agent">Agente</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($properties as $property)
                    <tr>
                        <td>{{ $property->id }}</td>
                        <td>{{ $property->title }}</td>
                        <td>{{ $property->propertyType->name ?? '—' }}</td>
                        <td>{{ $property->city }}</td>
                        <td>COP ${{ number_format((float) $property->price, 0, ',', '.') }}{{ $property->operation_type === 'alquiler' ? ' /mes' : '' }}</td>
                        <td>{{ ucfirst($property->operation_type) }}</td>
                        <td>{{ ucfirst($property->status) }}</td>
                        <td>{{ $property->user->name ?? '—' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p class="empty">No hay propiedades disponibles para el reporte.</p>
    @endif

    <div class="footer">
        {{ config('app.name', 'InmoPlus') }} — Reporte generado el {{ $generatedAt->locale('es')->isoFormat('D [de] MMMM [de] YYYY') }} por {{ $user->name }}.
    </div>

</body>
</html>
