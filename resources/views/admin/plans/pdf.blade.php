<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Plan de Entrenamiento</title>
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
        .day-block {
            margin-bottom: 30px;
            page-break-inside: avoid;
        }
        .day-title {
            background-color: #f0f0f0;
            padding: 8px;
            font-size: 16px;
            font-weight: bold;
            border: 1px solid #ddd;
        }
        .exercise-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            page-break-inside: auto;
        }
        .exercise-table tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }
        .exercise-table th, .exercise-table td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: center;
        }
        .exercise-name {
            text-align: left !important;
            font-weight: bold;
            background-color: #fafafa;
        }
        .set-row {
            height: 30px; /* Espacio cómodo para escribir */
        }
    </style>
</head>
<body>

    <div class="header">
        @if($plan->user && $plan->user->logo_path)
            <img src="{{ storage_path('app/public/' . $plan->user->logo_path) }}" alt="Logo Coach" style="max-height: 80px; margin-bottom: 10px;">
        @endif
        <h1>Plan de Entrenamiento</h1>
        @if($plan->user)
            <p style="margin: 5px 0 0 0; font-size: 14px; color: #555;">Coach: {{ $plan->user->name }}</p>
        @endif
    </div>

    <div class="info">
        <div class="info-col">
            <strong>Cliente:</strong> {{ $plan->assignedClient->name ?? 'N/A' }}<br>
            <strong>Mes:</strong> {{ $plan->month }} {{ $plan->year }}<br>
        </div>
        <div class="info-col">
            <strong>Tipo de Rutina:</strong> {{ $plan->split_type }}<br>
            <strong>Días por semana:</strong> {{ $plan->days_per_week }}<br>
        </div>
    </div>

    @foreach($plan->trainingDays as $day)
        <div class="day-block">
            <div class="day-title">Día {{ $day->day_number }}: {{ $day->label }}</div>
            
            <table class="exercise-table">
                <thead>
                    <tr>
                        <th style="width: 40%; text-align: left;">Ejercicio</th>
                        <th style="width: 15%;">Series x Reps (Obj.)</th>
                        <th style="width: 15%;">N° Serie</th>
                        <th style="width: 15%;">Reps Hechas</th>
                        <th style="width: 15%;">Peso (kg/lbs)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($day->plannedExercises as $planned)
                        @php $sets = $planned->sets > 0 ? $planned->sets : 3; @endphp
                        @for($i = 1; $i <= $sets; $i++)
                            <tr class="set-row">
                                @if($i === 1)
                                    <td rowspan="{{ $sets }}" class="exercise-name">
                                        {{ $planned->exercise->name ?? 'Ejercicio Eliminado' }}
                                        <br>
                                        <span style="font-weight: normal; font-size: 10px; color: #666;">
                                            ({{ $planned->exercise->muscleGroup->name ?? 'N/A' }})
                                        </span>
                                    </td>
                                    <td rowspan="{{ $sets }}">
                                        {{ $planned->sets }} x {{ $planned->min_reps }} - {{ $planned->max_reps }}
                                    </td>
                                @endif
                                <td>{{ $i }}</td>
                                <td></td>
                                <td></td>
                            </tr>
                        @endfor
                    @endforeach
                </tbody>
            </table>
        </div>
    @endforeach

</body>
</html>
