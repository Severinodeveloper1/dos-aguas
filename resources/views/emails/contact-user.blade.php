<x-emails.layout subject="Recibimos tu mensaje">

    <span class="badge">Mensaje Recibido</span>
    <h1 class="email-title">Hola, {{ $submission->name }}</h1>
    <div class="divider"></div>

    <p>
        Hemos recibido tu mensaje y nos alegra saber de ti.<br>
        Un miembro de nuestro equipo te responderá a la brevedad posible.
    </p>

    <h2 class="section-title">Resumen de tu mensaje</h2>
    <table class="info-table">
        <tr><td>Asunto</td><td><strong>{{ $submission->subject }}</strong></td></tr>
    </table>
    <div class="content-block">{{ $submission->message }}</div>

</x-emails.layout>
