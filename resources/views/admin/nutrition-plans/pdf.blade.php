<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Plan de Alimentación</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            margin-bottom: 20px;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .info {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        .info-col {
            display: table-cell;
            width: 50%;
        }
        .macros {
            width: 100%;
            margin-bottom: 30px;
            border-collapse: collapse;
        }
        .macros th, .macros td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        .macros th {
            background-color: #f0f0f0;
        }
        .meal-block {
            margin-bottom: 30px;
            page-break-inside: avoid;
        }
        .meal-title {
            background-color: #f0f0f0;
            padding: 8px;
            font-size: 16px;
            font-weight: bold;
            border: 1px solid #ddd;
        }
        .food-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .food-table th, .food-table td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: center;
        }
        .food-name {
            text-align: left !important;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <div class="header">
        @if($nutritionPlan->coach && $nutritionPlan->coach->logo_path)
            <img src="{{ storage_path('app/public/' . $nutritionPlan->coach->logo_path) }}" alt="Logo Coach" style="max-height: 80px; margin-bottom: 10px;">
        @endif
        <h1>Plan de Alimentación</h1>
        @if($nutritionPlan->coach)
            <p style="margin: 5px 0 0 0; font-size: 14px; color: #555;">Coach: {{ $nutritionPlan->coach->name }}</p>
        @endif
    </div>

    <div class="info">
        <div class="info-col">
            <strong>Cliente:</strong> {{ $nutritionPlan->client->name ?? 'N/A' }}<br>
            <strong>Objetivo:</strong> {{ ucfirst(str_replace('_', ' ', $nutritionPlan->objective)) }}<br>
            <strong>TDEE:</strong> {{ $nutritionPlan->tdee }} kcal
        </div>
        <div class="info-col">
            <strong>Peso:</strong> {{ $nutritionPlan->weight }} kg<br>
            <strong>Altura:</strong> {{ $nutritionPlan->height }} cm<br>
            <strong>Edad:</strong> {{ $nutritionPlan->age }} años
        </div>
    </div>

    <table class="macros">
        <thead>
            <tr>
                <th>Calorías Objetivo</th>
                <th>Proteínas (g)</th>
                <th>Carbohidratos (g)</th>
                <th>Grasas (g)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>{{ $nutritionPlan->total_calories ?? $nutritionPlan->target_calories }}</strong> kcal</td>
                <td><strong>{{ $nutritionPlan->total_protein }}</strong></td>
                <td><strong>{{ $nutritionPlan->total_carbs }}</strong></td>
                <td><strong>{{ $nutritionPlan->total_fat }}</strong></td>
            </tr>
        </tbody>
    </table>

    @foreach($nutritionPlan->meals as $meal)
        <div class="meal-block">
            <div class="meal-title">
                {{ $meal->name }} 
                @if($meal->time) 
                    <span style="font-weight: normal; font-size: 12px; color: #666;">
                        ({{ \Carbon\Carbon::parse($meal->time)->format('h:i A') }})
                    </span>
                @endif
            </div>
            
            <table class="food-table">
                <thead>
                    <tr>
                        <th style="width: 40%; text-align: left;">Alimento</th>
                        <th style="width: 20%;">Porción</th>
                        <th style="width: 10%;">Calorías</th>
                        <th style="width: 10%;">Pro (g)</th>
                        <th style="width: 10%;">Carb (g)</th>
                        <th style="width: 10%;">Gra (g)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($meal->foods as $food)
                        <tr>
                            <td class="food-name">{{ $food->name }}</td>
                            <td>{{ $food->serving_size }} {{ $food->serving_unit }}</td>
                            <td>{{ $food->calories }}</td>
                            <td>{{ $food->protein }}</td>
                            <td>{{ $food->carbs }}</td>
                            <td>{{ $food->fat }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">Sin alimentos registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endforeach

    @if($nutritionPlan->description)
        <div style="margin-top: 30px; padding: 15px; border: 1px solid #ddd; background-color: #fafafa;">
            <strong style="font-size: 14px; display: block; margin-bottom: 5px;">Comentarios Adicionales:</strong>
            <p style="margin: 0; line-height: 1.5; color: #555;">{{ $nutritionPlan->description }}</p>
        </div>
    @endif

</body>
</html>
