@php
    $tipoLabel = $claim->type === 'reclamacion' ? 'RECLAMACIÓN' : 'QUEJA';
@endphp
<x-emails.layout subject="Hoja de Reclamación Digital">

    <span class="badge">Código: {{ $claim->claim_code }}</span>
    <h1 class="email-title">Hoja de Reclamación Digital</h1>
    <div class="divider"></div>

    <p>Estimado(a) <strong>{{ $claim->full_name }}</strong>,</p>
    <p>Confirmamos la recepción de tu <strong>{{ $tipoLabel }}</strong> registrada el <strong>{{ $claim->created_at->format('d/m/Y H:i') }}</strong>.</p>

    <h2 class="section-title">1. Datos del Consumidor</h2>
    <table class="info-table">
        <tr><td>Documento</td><td>{{ $claim->document_type }} — {{ $claim->document_number }}</td></tr>
        <tr><td>Nombre</td><td><strong>{{ $claim->full_name }}</strong></td></tr>
        <tr><td>Teléfono</td><td>{{ $claim->phone }}</td></tr>
        <tr><td>Dirección</td><td>{{ $claim->address }}</td></tr>
        @if($claim->is_minor)
        <tr><td>Apoderado</td><td>{{ $claim->representative_name }} ({{ $claim->representative_document_type }} — {{ $claim->representative_document_number }})</td></tr>
        @endif
    </table>

    <h2 class="section-title">2. Bien o Servicio</h2>
    <table class="info-table">
        <tr><td>Tipo</td><td><strong>{{ $tipoLabel }}</strong></td></tr>
        <tr><td>Monto</td><td>{{ $claim->claimed_amount ? 'S/. ' . number_format($claim->claimed_amount, 2) : 'No especificado' }}</td></tr>
        <tr><td>Descripción</td><td>{{ $claim->product_service_description }}</td></tr>
    </table>

    <h2 class="section-title">3. Detalle del Reclamo</h2>
    <div class="content-block">{{ $claim->claim_details }}</div>

    <h2 class="section-title">Pedido o Solución Solicitada</h2>
    <div class="content-block">{{ $claim->consumer_request }}</div>

    <div class="alert">
        <p><strong>Plazo de Respuesta Legal:</strong> De acuerdo con la normativa Indecopi, resolveremos tu caso en un plazo máximo de <strong>15 días hábiles</strong> a partir de la fecha de recepción.</p>
    </div>

    <div class="divider"></div>
    <p style="font-size:12px;">Conservamos una copia de esta hoja en nuestros registros para su debida auditoría.</p>

</x-emails.layout>
