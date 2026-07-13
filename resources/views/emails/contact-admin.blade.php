<x-emails.layout subject="Nuevo mensaje de contacto">

    <span class="badge">Nuevo Contacto</span>
    <h1 class="email-title">Mensaje de Contacto Recibido</h1>
    <div class="divider"></div>

    <table class="info-table">
        <tr><td>Nombre</td><td><strong>{{ $submission->name }}</strong></td></tr>
        <tr><td>Email</td><td><a href="mailto:{{ $submission->email }}" style="color:#2e7d32; text-decoration:none; font-weight:bold;">{{ $submission->email }}</a></td></tr>
        <tr><td>Teléfono</td><td>{{ $submission->phone ?? 'No especificado' }}</td></tr>
        <tr><td>Asunto</td><td><strong>{{ $submission->subject }}</strong></td></tr>
    </table>

    <h2 class="section-title">Mensaje</h2>
    <div class="content-block">{{ $submission->message }}</div>

    <div class="divider"></div>

    <div class="btn-wrap">
        <a href="{{ url('/admin/contact-submissions') }}" class="btn">Ver en Panel de Administración</a>
    </div>

</x-emails.layout>
