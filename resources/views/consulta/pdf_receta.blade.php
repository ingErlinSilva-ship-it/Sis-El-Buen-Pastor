<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ficha Médica - {{ $consulta->paciente->usuario->apellido }}</title>
    <style>
        @page { size: letter; margin: 1.5cm; }
        body { font-family: 'Helvetica', sans-serif; color: #333; line-height: 1.4; }
        .header { border-bottom: 3px solid #1a4a72; padding-bottom: 10px; margin-bottom: 20px; text-align: center; }
        .clinic-name { color: #1a4a72; font-size: 22px; font-weight: bold; margin: 0; text-transform: uppercase; }
        .document-title { font-size: 16px; font-weight: bold; color: #666; margin-top: 5px; }
        
        .section-title { background: #f4f4f4; padding: 8px; font-weight: bold; color: #1a4a72; margin-top: 20px; border-left: 5px solid #1a4a72; font-size: 14px; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { text-align: left; padding: 10px; border: 1px solid #eee; font-size: 12px; }
        .label { font-weight: bold; color: #555; background-color: #fafafa; width: 30%; }
        
        .box { border: 1px solid #eee; padding: 15px; margin-top: 10px; min-height: 80px; font-size: 13px; }
        .footer { margin-top: 60px; text-align: center; font-size: 12px; }
        .signature-line { border-top: 1px solid #000; width: 220px; margin: 0 auto 10px auto; }
        
        /* Marca de agua sutil */
        .watermark {
            position: fixed; top: 30%; left: 20%; opacity: 0.05; z-index: -1000; width: 60%;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1 class="clinic-name">Consultorio El Buen Pastor</h1>
        <div class="document-title">EXPEDIENTE DE ATENCIÓN MÉDICA</div>
        <p style="font-size: 11px; margin: 5px 0;">Fecha: {{ \Carbon\Carbon::parse($consulta->fecha)->format('d/m/Y') }}</p>
    </div>

    <div class="section-title">I. INFORMACIÓN DEL PACIENTE</div>
    <table>
        <tr>
            <td class="label">Paciente:</td>
            <td>{{ $consulta->paciente->usuario->nombre }} {{ $consulta->paciente->usuario->apellido }}</td>
            <td class="label">Cédula:</td>
            <td>{{ $consulta->paciente->cedula ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">Médico:</td>
            <td colspan="3">Dr. {{ $consulta->medico->usuario->nombre }} {{ $consulta->medico->usuario->apellido }} ({{ $consulta->medico->especialidade->nombre }})</td>
        </tr>
    </table>

    <div class="section-title">II. CONSTANTES VITALES</div>
    <table>
        <tr style="text-align: center; background-color: #fafafa;">
            <th>Peso (kg)</th><th>Estatura (m)</th><th>P. Arterial</th><th>Temperatura</th>
        </tr>
        <tr style="text-align: center;">
            <td>{{ $consulta->peso ?? '---' }}</td>
            <td>{{ $consulta->estatura ?? '---' }}</td>
            <td>{{ $consulta->presion_arterial ?? '---' }}</td>
            <td>{{ $consulta->temperatura ?? '---' }} °C</td>
        </tr>
    </table>

    <div class="section-title">III. DIAGNÓSTICO MÉDICO</div>
    <div class="box" style="background-color: #fcfcfc">
        <strong>Síntomas:</strong> {{ $consulta->sintomas }}<br><br>
        <strong>Diagnóstico:</strong> <span style="font-size: 14px;">{{ $consulta->diagnostico }}</span>
    </div>

    <div class="section-title">IV. INDICACIONES Y PRESCRIPCIÓN</div>
    <div class="box" style="background-color: #fcfcfc;">
        {!! nl2br(e($consulta->prescripcion)) !!}
    </div>

    <div class="footer">
        <div class="signature-line"></div>
        <strong>Dr. {{ $consulta->medico->usuario->nombre }} {{ $consulta->medico->usuario->apellido }}</strong><br>
        Sello y Firma del Médico
    </div>
</body>
</html>