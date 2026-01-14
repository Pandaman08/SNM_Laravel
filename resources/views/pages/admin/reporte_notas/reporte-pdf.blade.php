<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Reporte de Calificaciones</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 8px;
            margin: 0;
            padding: 5px;
        }
        .header {
            text-align: center;
            margin-bottom: 3px;
        }
        .title {
            font-size: 12px;
            font-weight: bold;
        }
        .subtitle {
            font-size: 10px;
            margin-bottom: 5px;
        }
        .student-info {
            margin-bottom: 8px;
            border: 1px solid #ddd;
            padding: 3px;
            background-color: #f9f9f9;
        }
        .info-row {
            display: flex;
            margin-bottom: 2px;
        }
        .info-label {
            font-weight: bold;
            width: 90px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            table-layout: fixed;
            font-size: 7.5px;
        }
        th, td {
            border: 1px solid #000;
            padding: 2px 1px;
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
            width: 30%;
            word-wrap: break-word;
        }
        .nota-cell {
            width: 6%;
            text-align: center;
            padding: 2px;
        }
        .promedio-cell {
            width: 6%;
            text-align: center;
            font-weight: bold;
            background-color: #f0f0f0;
        }
        .final-cell {
            width: 6%;
            text-align: center;
            font-weight: bold;
            background-color: #e6e6e6;
        }
        .signature-section {
            margin-top: 15px;
            display: flex;
            justify-content: space-between;
        }
        .signature-box {
            width: 45%;
            text-align: center;
            font-size: 7px;
        }
        .signature-line {
            border-top: 1px solid #000;
            margin: 25px auto 3px;
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
                @foreach($periodos as $periodo)
                <th class="nota-cell">PERÍODO {{ $loop->iteration }}</th>
                @endforeach
                <th rowspan="2" class="promedio-cell">Promedio</th>
                <th rowspan="2" class="final-cell">NL Final</th>
            </tr>
            <tr>
                @foreach($periodos as $periodo)
                <th class="nota-cell">NL</th>
                @endforeach
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
                
                @php $detalle = $competencia->detallesAsignatura->first(); @endphp
                @foreach($periodos as $periodo)
                @php
                    $reporte = $detalle ? $detalle->reportesNotas->where('id_periodo', $periodo->id_periodo)->first() : null;
                    $nota = $reporte->calificacion ?? '';
                    $color = $nota === 'AD' ? 'color: #1e8449;' : 
                            ($nota === 'A' ? 'color: #2874a6;' : 
                            ($nota === 'B' ? 'color: #9a7d0a;' : 
                            ($nota === 'C' ? 'color: #ba4a00;' : '')));
                @endphp
                <td class="nota-cell" style="{{ $color }}">{{ $nota }}</td>
                @endforeach
                
                @php
                    $promedio = $detalle->promedio ?? '';
                    $colorPromedio = $promedio === 'AD' ? 'color: #1e8449;' : 
                                   ($promedio === 'A' ? 'color: #2874a6;' : 
                                   ($promedio === 'B' ? 'color: #9a7d0a;' : 
                                   ($promedio === 'C' ? 'color: #ba4a00;' : '')));
                    $finalAsig = $asignatura->calificacion_final ?? '';
                    $colorFinalAsignatura = $finalAsig === 'AD' ? 'color: #1e8449;' : 
                                           ($finalAsig === 'A' ? 'color: #2874a6;' : 
                                           ($finalAsig === 'B' ? 'color: #9a7d0a;' : 
                                           ($finalAsig === 'C' ? 'color: #ba4a00;' : '')));
                @endphp
                <td class="promedio-cell" style="{{ $colorPromedio }}">{{ $promedio }}</td>

                @if($loop->first)
                    <td rowspan="{{ $competenciaCount }}" class="final-cell" style="{{ $colorFinalAsignatura }}">{{ $finalAsig }}</td>
                @endif
            </tr>
            @endforeach
            @endforeach
        </tbody>
    </table>

    <!-- Tabla de escala de calificación -->
    <div style="margin-top: 10px; margin-bottom: 10px;">
        <div style="font-weight: bold; font-size: 9px; margin-bottom: 5px; text-align: center;">
            ESCALA DE CALIFICACIÓN
        </div>
        <table style="width: 100%; border-collapse: collapse; font-size: 7.5px;">
            <thead>
                <tr>
                    <th style="border: 1px solid #000; padding: 3px; text-align: center; background-color: #f2f2f2; width: 8%;">Calificación</th>
                    <th style="border: 1px solid #000; padding: 3px; text-align: center; background-color: #f2f2f2;">Descripción</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="border: 1px solid #000; padding: 3px; text-align: center; font-weight: bold;">AD</td>
                    <td style="border: 1px solid #000; padding: 3px;">
                        <strong>LOGRO DESTACADO</strong><br>
                        Cuando el estudiante evidencia un nivel superior a lo esperado respecto a la competencia. Esto quiere decir que demuestra aprendizajes que van más allá del nivel esperado.
                    </td>
                </tr>
                <tr>
                    <td style="border: 1px solid #000; padding: 3px; text-align: center; font-weight: bold;">A</td>
                    <td style="border: 1px solid #000; padding: 3px;">
                        <strong>LOGRO ESPERADO</strong><br>
                        Cuando el estudiante evidencia el nivel esperado respecto a la competencia, demostrando manejo satisfactorio en todas las tareas propuestas y en el tiempo programado.
                    </td>
                </tr>
                <tr>
                    <td style="border: 1px solid #000; padding: 3px; text-align: center; font-weight: bold;">B</td>
                    <td style="border: 1px solid #000; padding: 3px;">
                        <strong>EN PROCESO</strong><br>
                        Cuando el estudiante está próximo o cerca al nivel esperado respecto a la competencia, para lo cual requiere acompañamiento durante un tiempo razonable para lograrlo.
                    </td>
                </tr>
                <tr>
                    <td style="border: 1px solid #000; padding: 3px; text-align: center; font-weight: bold;">C</td>
                    <td style="border: 1px solid #000; padding: 3px;">
                        <strong>EN INICIO</strong><br>
                        Cuando el estudiante muestra progreso mínimo en una competencia de acuerdo al nivel esperado. Evidencia con frecuencia dificultades en el desarrollo de las tareas, por lo que necesita mayor tiempo de acompañamiento e intervención del docente.
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

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