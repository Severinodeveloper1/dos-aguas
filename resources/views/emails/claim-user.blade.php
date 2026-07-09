@php
    $company = \App\Models\CompanyInfo::first();
    $companyName = $company?->name ?: 'Dos Aguas S.A.C.';
    $companyAddress = $company?->address ?: 'Dirección registrada';
@endphp
<x-mail::message>
# Hoja de Reclamación Digital - Código {{ $claim->claim_code }}

Estimado(a) **{{ $claim->full_name }}**,

Confirmamos la recepción de tu disconformidad registrada en el **Libro de Reclamaciones Virtual** de **{{ $companyName }}** con fecha **{{ $claim->created_at->format('d/m/Y H:i') }}**. 

A continuación, te adjuntamos el resumen detallado de tu presentación conforme a lo establecido en el Código de Protección y Defensa del Consumidor del Perú:

### 1. Datos Identificativos del Consumidor
* **Documento:** {{ $claim->document_type }} - {{ $claim->document_number }}
* **Nombre completo:** {{ $claim->full_name }}
* **Teléfono:** {{ $claim->phone }}
* **Dirección física:** {{ $claim->address }}
@if($claim->is_minor)
* **Apoderado/Tutor:** {{ $claim->representative_name }} ({{ $claim->representative_document_type }} - {{ $claim->representative_document_number }})
@endif

### 2. Información del Bien o Servicio Adquirido
* **Tipo de Presentación:** {{ strtoupper($claim->type === 'reclamacion' ? 'Reclamación' : 'Queja') }}
* **Monto Reclamado:** {{ $claim->claimed_amount ? 'S/. ' . number_format($claim->claimed_amount, 2) : 'No especificado' }}
* **Descripción del Bien/Servicio:**
{{ $claim->product_service_description }}

### 3. Detalle del Reclamo y Pedido
* **Explicación del Suceso:**
{{ $claim->claim_details }}
* **Pedido o Solución Solicitada:**
{{ $claim->consumer_request }}

---

> [!IMPORTANT]
> **Plazo de Respuesta Legal:** De acuerdo con la normativa vigente de Indecopi, resolveremos tu queja o reclamo en un plazo máximo no prorrogable de **quince (15) días hábiles** a partir de la fecha de recepción.

Conservaremos una copia de esta hoja de reclamación digital en nuestros archivos para su debida auditoría.

Atentamente,<br>
**{{ $companyName }}**<br>
Dirección: {{ $companyAddress }}
</x-mail::message>
