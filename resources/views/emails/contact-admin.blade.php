@php
    $company = \App\Models\CompanyInfo::first();
    $companyName = $company?->name ?: 'Dos Aguas';
@endphp
<x-mail::message>
# Alerta: Nuevo Mensaje de Contacto

Se ha recibido una nueva consulta desde el portal público de la web:

* **Nombre completo:** {{ $submission->name }}
* **Correo Electrónico:** {{ $submission->email }}
* **Teléfono:** {{ $submission->phone ?? 'No especificado' }}
* **Asunto:** {{ $submission->subject }}

### Mensaje:
{{ $submission->message }}

Puedes gestionar este contacto y registrar notas de seguimiento desde el Panel de Administración.

<x-mail::button :url="url('/admin/contact-submissions')">
Ver en Panel de Administración
</x-mail::button>

Atentamente,<br>
Sistema de Alertas **{{ $companyName }}**
</x-mail::message>
