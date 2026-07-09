@php
    $company = \App\Models\CompanyInfo::first();
    $companyName = $company?->name ?: 'Dos Aguas';
    $companyPhone = $company?->phone ?: '';
    $companyEmail = $company?->email ?: '';
@endphp
<x-mail::message>
# ¡Hola {{ $submission->name }}!

Hemos recibido tu mensaje de contacto en **{{ $companyName }}**. Nos alegra saber de ti y que compartas nuestra pasión por el cacao fino de aroma.

Un miembro de nuestro equipo revisará tu mensaje y se pondrá en contacto contigo a la brevedad posible.

### Resumen de tu mensaje:
* **Asunto:** {{ $submission->subject }}
* **Mensaje:**
{{ $submission->message }}

Si tienes alguna consulta urgente adicional, no dudes en responder a este correo electrónico o escribirnos a **{{ $companyEmail }}**@if($companyPhone) o llamarnos al **{{ $companyPhone }}**@endif.

Atentamente,<br>
El equipo de **{{ $companyName }}**
</x-mail::message>
