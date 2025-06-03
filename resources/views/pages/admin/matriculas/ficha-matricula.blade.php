<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Ficha de Matrícula - {{ $matricula->codigo_matricula }}</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .header { text-align: center; margin-bottom: 20px; }
        .logo { width: 150px; height: auto; }
        .title { font-size: 24px; font-weight: bold; margin: 10px 0; }
        .subtitle { font-size: 18px; margin-bottom: 20px; }
        .section { margin-bottom: 15px; }
        .section-title { font-size: 16px; font-weight: bold; border-bottom: 1px solid #000; margin-bottom: 5px; }
        .info-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        .info-table td { padding: 5px; border: 1px solid #ddd; }
        .info-table .label { font-weight: bold; width: 30%; background-color: #f5f5f5; }
        .footer { margin-top: 30px; text-align: center; font-size: 12px; color: #666; }
        .signature { margin-top: 50px; }
        .signature-line { width: 300px; border-top: 1px solid #000; margin: 0 auto; }
        .signature-text { text-align: center; margin-top: 5px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">INSTITUCIÓN EDUCATIVA</div>
        <div class="subtitle">FICHA DE MATRÍCULA</div>
    </div>

    <div class="section">
        <div class="section-title">DATOS DEL ESTUDIANTE</div>
        <table class="info-table">
            <tr>
                <td class="label">Código de Matrícula:</td>
                <td>{{ $matricula->codigo_matricula }}</td>
            </tr>
            <tr>
                <td class="label">Estudiante:</td>
                <td>{{ $matricula->estudiante->persona->name }} {{ $matricula->estudiante->persona->lastname }}</td>
            </tr>
            <tr>
                <td class="label">DNI:</td>
                <td>{{ $matricula->estudiante->persona->dni }}</td>
            </tr>
            <tr>
                <td class="label">Fecha de Nacimiento:</td>
                <td>{{ $matricula->estudiante->persona->fecha_nacimiento }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">DATOS ACADÉMICOS</div>
        <table class="info-table">
            <tr>
                <td class="label">Año Escolar:</td>
                <td>{{ $matricula->anioEscolar->anio }}</td>
            </tr>
            <tr>
                <td class="label">Nivel Educativo:</td>
                <td>{{ $matricula->seccion->grado->nivelEducativo->nombre }}</td>
            </tr>
            <tr>
                <td class="label">Grado/Sección:</td>
                <td>{{ $matricula->seccion->grado->grado }} - {{ $matricula->seccion->seccion }}</td>
            </tr>
            <tr>
                <td class="label">Tipo de Matrícula:</td>
                <td>{{ $matricula->tipoMatricula->nombre }}</td>
            </tr>
            <tr>
                <td class="label">Fecha de Matrícula:</td>
                <td>{{ $matricula->fecha }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">ASIGNATURAS MATRICULADAS</div>
        <table class="info-table">
            <tr>
                <th>Asignatura</th>
                <th>Docente</th>
            </tr>
            @foreach($matricula->detallesAsignatura as $detalle)
            <tr>
                <td>{{ $detalle->asignatura->nombre }}</td>
                <td>{{ $detalle->docente->persona->name ?? 'Por asignar' }}</td>
            </tr>
            @endforeach
        </table>
    </div>

    <div class="section">
        <div class="section-title">INFORMACIÓN DE PAGO</div>
        <table class="info-table">
            <tr>
                <td class="label">Concepto:</td>
                <td>{{ $matricula->pagos->first()->concepto }}</td>
            </tr>
            <tr>
                <td class="label">Monto:</td>
                <td>S/ {{ number_format($matricula->pagos->first()->monto, 2) }}</td>
            </tr>
            <tr>
                <td class="label">Fecha de Pago:</td>
                <td>{{ $matricula->pagos->first()->fecha_pago }}</td>
            </tr>
            <tr>
                <td class="label">Estado:</td>
                <td>{{ $matricula->pagos->first()->estado }}</td>
            </tr>
        </table>
    </div>

    <div class="signature">
        <div class="signature-line"></div>
        <div class="signature-text">Firma del Responsable</div>
    </div>

    <div class="footer">
        Fecha de emisión: {{ now() }} | Sistema de Gestión Académica
    </div>
</body>
</html>