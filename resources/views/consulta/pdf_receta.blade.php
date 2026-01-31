<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Helvetica', sans-serif; color: #333; }
        .header { text-align: center; border-bottom: 2px solid #1a4a72; padding-bottom: 10px; }
        .clinic-name { color: #1a4a72; font-size: 24px; font-weight: bold; margin: 0; }
        .info-paciente { margin-top: 20px; font-size: 14px; }
        .prescripcion { margin-top: 30px; padding: 20px; border: 1px solid #ddd; min-height: 400px; font-size: 16px; line-height: 1.6; }
        .footer { margin-top: 50px; text-align: center; }
        .signature-line { border-top: 1px solid #000; width: 250px; margin: 0 auto; margin-top: 80px; }

        .watermark {
        position: fixed;
        top: 25%;
        left: 15%;
        opacity: 0.1; /* Muy transparente */
        z-index: -1000;
        width: 70%;
    }
    </style>
</head>
<body>
    <div class="watermark">
        <img src="{{ public_path('vendor/adminlte/dist/img/logopdf.png') }}" 
         alt="Logo" 
         style="width: 80px; height: 80px; margin-bottom: 10px;">
        <h1 class="clinic-name">CLÍNICA EL BUEN PASTOR</h1>
        <p style="margin: 5px 0;">Tel: +505 8792-2112</p>
        <p style="margin: 5px 0;">Dirección: Del Laboratorio Divino Niño, media cuadra al Oeste Diriá-Granada.</p>
    </div>
    
    <div class="info-paciente">
        <p><strong>PACIENTE:</strong> {{ $consulta->paciente->usuario->nombre }} {{ $consulta->paciente->usuario->apellido }}</p>
        <p><strong>FECHA:</strong> {{ date('d/m/Y') }}</p>
    </div>

    <div class="prescripcion">
        <h3 style="color: #1a4a72; border-bottom: 1px solid #eee;">INDICACIONES MÉDICAS:</h3>
        {!! nl2br(e($prescripcion_final)) !!}
    </div>

    <div class="footer">
        <div class="signature-line"></div>
        <p><strong>Dr. {{ $consulta->medico->usuario->nombre }}</strong><br>Sello y Firma del Médico</p>
    </div>
</body>
</html>

