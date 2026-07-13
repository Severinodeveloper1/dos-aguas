@props([
    'companyName'    => 'Dos Aguas',
    'companyEmail'   => '',
    'companyAddress' => '',
    'subject'        => '',
])
@php
    $company = \App\Models\CompanyInfo::first();
    $companyName = $company?->name ?: $companyName;
    $companyEmail = $company?->email ?: $companyEmail;
    $companyAddress = $company?->address ?: $companyAddress;
    
    $logoUrl = null;
    // Si existe el logo en BD y el archivo físico está en storage, lo embebemos directamente en el correo
    if ($company && $company->logo_path) {
        $logoPath = storage_path('app/public/' . $company->logo_path);
        if (file_exists($logoPath)) {
            $logoUrl = isset($message) ? $message->embed($logoPath) : asset('storage/' . $company->logo_path);
        } else {
            $logoUrl = str_starts_with($company->logo_path, 'http') 
                ? $company->logo_path 
                : asset('storage/' . $company->logo_path);
        }
    } else {
        $fallbackPath = public_path('img/LOGO ORIGINAL HORIZONTAL .png');
        if (file_exists($fallbackPath)) {
            $logoUrl = isset($message) ? $message->embed($fallbackPath) : asset('img/LOGO ORIGINAL HORIZONTAL .png');
        } else {
            $logoUrl = asset('img/LOGO ORIGINAL HORIZONTAL .png');
        }
    }
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $subject ?: $companyName }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background-color: #f4f6f8; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #333333; line-height: 1.6; }
        .wrapper { max-width: 600px; margin: 0 auto; padding: 24px 16px; }
        .header { background-color: #ffffff; border-bottom: 3px solid #2e7d32; padding: 32px 24px; text-align: center; border-top-left-radius: 8px; border-top-right-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
        .header img { max-height: 60px; width: auto; display: inline-block; }
        .header-tagline { font-family: Arial, sans-serif; font-size: 10px; letter-spacing: 3px; text-transform: uppercase; color: #c59b27; margin-top: 12px; font-weight: bold; }
        .card { background-color: #ffffff; padding: 40px 32px; border-bottom-left-radius: 8px; border-bottom-right-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); border: 1px solid #e1e8ed; border-top: 0; }
        .badge { display: inline-block; background-color: #e8f5e9; color: #2e7d32; font-family: Arial, sans-serif; font-size: 9px; letter-spacing: 2px; text-transform: uppercase; padding: 6px 16px; margin-bottom: 24px; font-weight: bold; border-radius: 20px; }
        h1.email-title { font-size: 24px; font-weight: bold; color: #1b5e20; margin-bottom: 8px; line-height: 1.3; font-family: Georgia, serif; }
        .divider { height: 1px; background-color: #e1e8ed; margin: 24px 0; }
        p { font-size: 14px; line-height: 1.8; color: #555555; margin-bottom: 16px; }
        strong { color: #222222; }
        .info-table { width: 100%; border-collapse: collapse; margin: 20px 0; background-color: #f8f9fa; border-radius: 6px; overflow: hidden; border: 1px solid #e1e8ed; }
        .info-table tr:not(:last-child) td { border-bottom: 1px solid #e1e8ed; }
        .info-table td { padding: 12px 16px; font-size: 13px; line-height: 1.5; vertical-align: top; }
        .info-table td:first-child { color: #2e7d32; font-family: Arial, sans-serif; letter-spacing: 1px; font-size: 11px; text-transform: uppercase; width: 35%; white-space: nowrap; font-weight: bold; }
        .info-table td:last-child { color: #333333; }
        h2.section-title { font-size: 11px; font-family: Arial, sans-serif; letter-spacing: 2px; text-transform: uppercase; color: #c59b27; margin: 28px 0 12px; border-bottom: 2px solid #f0e6cc; padding-bottom: 6px; font-weight: bold; }
        .btn-wrap { text-align: center; margin: 32px 0 8px; }
        .btn { display: inline-block; background-color: #2e7d32; color: #ffffff !important; font-family: Arial, sans-serif; font-size: 11px; letter-spacing: 2px; text-transform: uppercase; text-decoration: none; padding: 14px 36px; font-weight: bold; border-radius: 4px; transition: background-color 0.2s ease; }
        .btn:hover { background-color: #1b5e20; }
        .alert { background-color: #fffde7; border-left: 4px solid #c59b27; padding: 16px 20px; margin: 24px 0; border-radius: 4px; }
        .alert p { font-size: 13px; color: #665522; margin: 0; line-height: 1.6; }
        .alert.warning { border-color: #d32f2f; background-color: #ffebee; }
        .alert.warning p { color: #c62828; }
        .content-block { background-color: #f1f8e9; border-left: 4px solid #81c784; padding: 16px 20px; margin: 18px 0; font-size: 13px; line-height: 1.8; color: #33691e; font-style: italic; border-radius: 4px; }
        .footer { padding: 32px 24px; text-align: center; }
        .footer p { font-family: Arial, sans-serif; font-size: 11px; color: #778899; margin: 0; line-height: 1.8; }
        .footer a { color: #2e7d32; text-decoration: none; font-weight: bold; }
        .footer-name { color: #1b5e20; font-size: 13px; letter-spacing: 2px; text-transform: uppercase; display: block; margin-bottom: 8px; font-weight: bold; }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="header">
        @if($logoUrl)
            <img src="{{ $logoUrl }}" alt="{{ $companyName }}" />
        @else
            <span style="font-family: Georgia, serif; font-size: 24px; color: #1b5e20; letter-spacing: 3px; font-weight: bold;">{{ strtoupper($companyName) }}</span>
        @endif
        <div class="header-tagline">Chocolate de Origen Peruano</div>
    </div>
    
    <div class="card">
        {{ $slot }}
    </div>

    <div class="footer">
        <span class="footer-name">{{ $companyName }}</span>
        @if($companyAddress)<p>{{ $companyAddress }}</p>@endif
        @if($companyEmail)<p><a href="mailto:{{ $companyEmail }}">{{ $companyEmail }}</a></p>@endif
        <p style="margin-top: 16px; font-size: 10px; color: #99aaaa;">Este correo fue generado automáticamente. Por favor no responda directamente.</p>
    </div>
</div>
</body>
</html>
