@php
    $tipoLabel = $claim->type === 'reclamacion' ? 'RECLAMACIÓN' : 'QUEJA';
@endphp
<x-emails.layout subject="Nuevo reclamo registrado">

    <span class="badge" style="background:#ffebee; color:#c62828;">⚠ Alerta Legal</span>
    <h1 class="email-title">Nuevo {{ $tipoLabel }} — {{ $claim->claim_code }}</h1>
    <div class="divider"></div>

    <h2 class="section-title">Datos del Consumidor</h2>
    <table class="info-table">
        <tr><td>Nombre</td><td><strong>{{ $claim->full_name }}</strong></td></tr>
        <tr><td>Documento</td><td>{{ $claim->document_type }} — {{ $claim->document_number }}</td></tr>
        <tr><td>Tipo</td><td><strong>{{ $tipoLabel }}</strong></td></tr>
        <tr><td>Monto Implicado</td><td>{{ $claim->claimed_amount ? 'S/. ' . number_format($claim->claimed_amount, 2) : 'No especificado' }}</td></tr>
    </table>

    <h2 class="section-title">Detalle del Suceso</h2>
    <div class="content-block">{{ $claim->claim_details }}</div>

    <h2 class="section-title">Pedido del Consumidor</h2>
    <div class="content-block">{{ $claim->consumer_request }}</div>

    <div class="alert warning">
        <p><strong>⏱ PLAZO LEGAL INDECOPI:</strong> Este caso debe ser respondido en un máximo de <strong>15 días hábiles</strong> según la Ley N° 29571 para evitar multas y procesos sancionatorios.</p>
    </div>

    <div class="btn-wrap">
        <a href="{{ url('/admin/claims') }}" class="btn">Ver y Responder Reclamo</a>
    </div>

</x-emails.layout>
