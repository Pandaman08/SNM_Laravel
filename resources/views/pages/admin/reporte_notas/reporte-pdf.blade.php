<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Reporte de Calificaciones</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 9px;
            margin: 0;
            padding: 10px;
        }
        .header {
            text-align: center;
            margin-bottom: 5px;
        }
        .title {
            font-size: 14px;
            font-weight: bold;
        }
        .subtitle {
            font-size: 11px;
            margin-bottom: 8px;
        }
        .student-info {
            margin-bottom: 10px;
            border: 1px solid #ddd;
            padding: 5px;
            background-color: #f9f9f9;
        }
        .info-row {
            display: flex;
            margin-bottom: 3px;
        }
        .info-label {
            font-weight: bold;
            width: 100px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            table-layout: fixed;
        }
        th, td {
            border: 1px solid #000;
            padding: 4px;
            vertical-align: middle;
        }
        th {
            background-color: #f2f2f2;
            text-align: center;
            font-weight: bold;
        }
        .area-cell {
            font-weight: bold;
            background-color: #e6e6e6;
            width: 15%;
            word-wrap: break-word;
            vertical-align: middle;
        }
        .competencia-cell {
            width: 25%;
            word-wrap: break-word;
        }
        .nota-cell {
            width: 4%;
            text-align: center;
        }
        .conclusion-cell {
            width: 8%;
            word-wrap: break-word;
        }
        .final-cell {
            width: 5%;
            text-align: center;
        }
        .signature-section {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
        }
        .signature-box {
            width: 45%;
            text-align: center;
        }
        .signature-line {
            border-top: 1px solid #000;
            margin: 40px auto 5px;
            width: 80%;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <!-- Encabezado del documento -->
    <div class="header">
        <div class="title">INSTITUCIÓN EDUCATIVA</div>
        <div class="subtitle">REPORTE OFICIAL DE CALIFICACIONES</div>
        <div class="subtitle">Año Académico {{ date('Y') }}</div>
    </div>

    <!-- Información del estudiante -->
    <div class="student-info">
        <div class="info-row">
            <div class="info-label">Estudiante:</div>
            <div>{{ $matricula->estudiante->persona->name }} {{ $matricula->estudiante->persona->lastname }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Grado/Sección:</div>
            <div>{{ $matricula->seccion->grado->grado }} "{{ $matricula->seccion->seccion }}"</div>
        </div>
        <div class="info-row">
            <div class="info-label">Código de matrícula:</div>
            <div>{{ $matricula->codigo_matricula }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Fecha de emisión:</div>
            <div>{{ $fecha }}</div>
        </div>
    </div>

    <!-- Tabla de calificaciones -->
    <table>
        <thead>
            <tr>
                <th rowspan="2" class="area-cell">Área curricular</th>
                <th rowspan="2" class="competencia-cell">Competencias</th>
                <th colspan="2">PRIMER BIMESTRE</th>
                <th colspan="2">SEGUNDO BIMESTRE</th>
                <th colspan="2">TERCER BIMESTRE</th>
                <th colspan="2">CUARTO BIMESTRE</th>
                <th rowspan="2" class="final-cell">NL final</th>
            </tr>
            <tr>
                <th class="nota-cell">NL</th>
                <th class="conclusion-cell">Conclusión</th>
                <th class="nota-cell">NL</th>
                <th class="conclusion-cell">Conclusión</th>
                <th class="nota-cell">NL</th>
                <th class="conclusion-cell">Conclusión</th>
                <th class="nota-cell">NL</th>
                <th class="conclusion-cell">Conclusión</th>
            </tr>
        </thead>
        <tbody>
            @foreach($asignaturas as $asignatura)
            @php
                $competenciaCount = count($asignatura->competencias);
                $firstCompetencia = true;
            @endphp
            
            @foreach($asignatura->competencias as $competencia)
            <tr>
                @if($firstCompetencia)
                <td rowspan="{{ $competenciaCount }}" class="area-cell">{{ $asignatura->nombre }}</td>
                @php $firstCompetencia = false; @endphp
                @endif
                
                <td class="competencia-cell">{{ $competencia->descripcion }}</td>
                
                @foreach(range(3, 6) as $periodo)
                @php
                    $detalle = $competencia->detallesAsignatura->first();
                    $reporte = $detalle ? $detalle->reportesNotas->where('id_periodo', $periodo)->first() : null;
                    $nota = $reporte->tipoCalificacion->codigo ?? '';
                    $color = $nota === 'AD' ? 'color: #1e8449;' : 
                            ($nota === 'A' ? 'color: #2874a6;' : 
                            ($nota === 'B' ? 'color: #9a7d0a;' : 
                            ($nota === 'C' ? 'color: #ba4a00;' : '')));
                @endphp
                <td class="nota-cell" style="{{ $color }}">{{ $nota }}</td>
                <td class="conclusion-cell">{{ $reporte->observacion ?? '' }}</td>
                @endforeach
                
                @php
                    $promedio = $detalle->promedio ?? '';
                    $colorPromedio = $promedio === 'AD' ? 'color: #1e8449;' : 
                                   ($promedio === 'A' ? 'color: #2874a6;' : 
                                   ($promedio === 'B' ? 'color: #9a7d0a;' : 
                                   ($promedio === 'C' ? 'color: #ba4a00;' : '')));
                @endphp
                <td class="final-cell" style="{{ $colorPromedio }}">{{ $promedio }}</td>
            </tr>
            @endforeach
            @endforeach
        </tbody>
    </table>

    <!-- Sección de firmas -->
    <div class="signature-section">
        <div class="signature-box">
            <div class="signature-line"></div>
            <div>Firma del Docente/Tutor(a)</div>
            <div>Nombre: _________________________</div>
        </div>
        <div class="signature-box">
            <div class="signature-line"></div>
            <div>Firma y Sello del Director(a)</div>
            <div>Nombre: _________________________</div>
        </div>
    </div>
</body>
</html>