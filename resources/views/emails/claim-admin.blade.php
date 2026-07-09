@php
    $company = \App\Models\CompanyInfo::first();
    $companyName = $company?->name ?: 'Dos Aguas';
@endphp
<x-mail::message>
# Alerta: Nuevo Reclamo Registrado - {{ $claim->claim_code }}

Se ha presentado una nueva disconformidad en el **Libro de Reclamaciones Virtual**:

* **Código de Reclamo:** {{ $claim->claim_code }}
* **Cliente:** {{ $claim->full_name }} ({{ $claim->document_type }} - {{ $claim->document_number }})
* **Tipo de disconformidad:** {{ strtoupper($claim->type === 'reclamacion' ? 'Reclamación' : 'Queja') }}
* **Monto implicado:** {{ $claim->claimed_amount ? 'S/. ' . number_format($claim->claimed_amount, 2) : 'No especificado' }}

### Detalle del Suceso:
{{ $claim->claim_details }}

### Pedido del Consumidor:
{{ $claim->consumer_request }}

---

> [!WARNING]
> **ATENCIÓN - PLAZO LEGAL:** Por mandato de la Ley N° 29571 (Indecopi), este caso debe ser respondido y resuelto formalmente en un plazo máximo de **quince (15) días hábiles** a fin de evitar multas y procesos sancionatorios.

Puedes registrar la resolución oficial y enviar la respuesta al cliente desde el Panel de Administración.

<x-mail::button :url="url('/admin/claims')">
Ver y Responder Reclamo
</x-mail::button>

Atentamente,<br>
Sistema de Cumplimiento Legal **{{ $companyName }}**
</x-mail::message>
