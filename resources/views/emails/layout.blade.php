<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>{{ $subject ?? 'Dos Aguas' }}</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { background-color:#0e0e0e; font-family: Georgia, 'Times New Roman', serif; color:#d4c5b8; }
        .wrapper { max-width:620px; margin:0 auto; padding:40px 16px; }

        /* Header */
        .header { background-color:#111111; border-bottom:2px solid #3b2a24; padding:32px 40px; text-align:center; }
        .header img { max-height:52px; width:auto; }
        .header-tagline { font-family: Arial, sans-serif; font-size:9px; letter-spacing:4px; text-transform:uppercase; color:#eabcb8; margin-top:10px; }

        /* Accent bar */
        .accent-bar { height:3px; background: linear-gradient(90deg, #3b2a24 0%, #eabcb8 50%, #3b2a24 100%); }

        /* Body card */
        .card { background-color:#161616; border:1px solid #2a1e1a; padding:40px; }

        /* Subject badge */
        .badge { display:inline-block; background-color:#3b2a24; color:#eabcb8; font-family:Arial,sans-serif; font-size:9px; letter-spacing:3px; text-transform:uppercase; padding:5px 14px; margin-bottom:24px; }

        /* Title */
        h1.email-title { font-size:22px; font-weight:normal; color:#f0e6e0; margin-bottom:8px; line-height:1.4; }
        .divider { height:1px; background-color:#2a1e1a; margin:24px 0; }

        /* Body text */
        p { font-size:14px; line-height:1.8; color:#c4b5a8; margin-bottom:14px; }
        strong { color:#f0e6e0; }

        /* Info table */
        .info-table { width:100%; border-collapse:collapse; margin:20px 0; }
        .info-table tr:not(:last-child) td { border-bottom:1px solid #2a1e1a; }
        .info-table td { padding:10px 12px; font-size:13px; line-height:1.5; vertical-align:top; }
        .info-table td:first-child { color:#eabcb8; font-family:Arial,sans-serif; letter-spacing:1px; font-size:11px; text-transform:uppercase; width:36%; white-space:nowrap; }
        .info-table td:last-child  { color:#d4c5b8; }

        /* Section heading */
        h2.section-title { font-size:11px; font-family:Arial,sans-serif; letter-spacing:3px; text-transform:uppercase; color:#eabcb8; margin:28px 0 12px; border-bottom:1px solid #2a1e1a; padding-bottom:8px; }

        /* CTA button */
        .btn-wrap { text-align:center; margin:30px 0 10px; }
        .btn { display:inline-block; background-color:#eabcb8; color:#1a0f0d; font-family:Arial,sans-serif; font-size:10px; letter-spacing:3px; text-transform:uppercase; text-decoration:none; padding:14px 34px; font-weight:bold; }
        .btn:hover { background-color:#f5cec9; }

        /* Alert box */
        .alert { background-color:#1e1210; border-left:3px solid #eabcb8; padding:14px 18px; margin:20px 0; }
        .alert p { font-size:13px; color:#c4b5a8; margin:0; }
        .alert.warning { border-color:#c97c5c; background-color:#1f1410; }
        .alert.warning p { color:#c4b5a8; }

        /* Blockquote / message content */
        .content-block { background-color:#1a1210; border-left:3px solid #3b2a24; padding:14px 18px; margin:16px 0; font-size:13px; line-height:1.8; color:#c4b5a8; font-style:italic; }

        /* Footer */
        .footer { background-color:#0a0a0a; border-top:1px solid #2a1e1a; padding:24px 40px; text-align:center; }
        .footer p { font-family:Arial,sans-serif; font-size:11px; color:#5a4a42; margin:0; line-height:1.7; }
        .footer a { color:#eabcb8; text-decoration:none; }
        .footer-name { color:#eabcb8; font-size:12px; letter-spacing:2px; text-transform:uppercase; display:block; margin-bottom:6px; }
    </style>
</head>
<body>
<div class="wrapper">
    <!-- Header -->
    <div class="header">
        <img src="{{ $logoUrl }}" alt="Dos Aguas" />
        <div class="header-tagline">Chocolate de Origen Peruano</div>
    </div>
    <div class="accent-bar"></div>

    <!-- Card Content -->
    <div class="card">
        {{ $slot }}
    </div>

    <!-- Footer -->
    <div class="footer">
        <span class="footer-name">{{ $companyName ?? 'Dos Aguas' }}</span>
        @if(!empty($companyAddress))
            <p>{{ $companyAddress }}</p>
        @endif
        @if(!empty($companyEmail))
            <p><a href="mailto:{{ $companyEmail }}">{{ $companyEmail }}</a></p>
        @endif
        <p style="margin-top:12px;">Este correo fue generado automáticamente. Por favor no responda directamente.</p>
    </div>
</div>
</body>
</html>
