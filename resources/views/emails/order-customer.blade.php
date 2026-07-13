@php
    $paymentLabel = $order->payment_method === 'transfer' ? 'Transferencia Bancaria' : 'Tarjeta de Crédito/Débito';
    $firstName    = explode(' ', $order->customer_name)[0];
@endphp
<x-emails.layout subject="Confirmación de tu pedido">

    <span class="badge">Pedido Confirmado ✓</span>
    <h1 class="email-title">¡Gracias, {{ $firstName }}!</h1>
    <div class="divider"></div>

    <p>Hemos recibido tu pedido correctamente. Nos pondremos en contacto contigo para coordinar la entrega.</p>

    <h2 class="section-title">Datos del Pedido</h2>
    <table class="info-table">
        <tr><td>N° Pedido</td><td><strong>{{ $order->order_number }}</strong></td></tr>
        <tr><td>Fecha</td><td>{{ $order->created_at->format('d/m/Y H:i') }}</td></tr>
        <tr><td>Método de Pago</td><td>{{ $paymentLabel }}</td></tr>
        <tr><td>Dirección de Envío</td><td>{{ $order->shipping_address }}</td></tr>
    </table>

    <h2 class="section-title">Productos</h2>
    <table class="info-table">
        @foreach($order->items as $item)
        <tr>
            <td>{{ $item->variant->product->name ?? 'Producto' }}<br><span style="font-size:11px;color:#7a6a60;">{{ $item->variant->name ?? '' }} × {{ $item->quantity }}</span></td>
            <td style="text-align:right;"><strong>S/ {{ number_format($item->total, 2) }}</strong></td>
        </tr>
        @endforeach
    </table>

    <h2 class="section-title">Totales</h2>
    <table class="info-table">
        <tr><td>Subtotal</td><td style="text-align:right;">S/ {{ number_format($order->subtotal, 2) }}</td></tr>
        <tr>
            <td>Envío</td>
            <td style="text-align:right;">
                @if(str_contains($order->shipping_address, 'Internacional'))
                    <span style="color:#c59b27;font-weight:bold;">
                        @if(app()->getLocale() == 'es') Por cotizar @elseif(app()->getLocale() == 'de') Wird berechnet @else To quote @endif
                    </span>
                @else
                    S/ {{ number_format($order->shipping_cost, 2) }}
                @endif
            </td>
        </tr>
        <tr><td><strong>Total a Pagar</strong></td><td style="text-align:right;"><strong style="color:#1b5e20;font-size:16px;">S/ {{ number_format($order->total, 2) }}</strong></td></tr>
    </table>

    @if(str_contains($order->shipping_address, 'Internacional'))
    <div class="alert" style="background-color: #fffde7; border-left: 4px solid #c59b27; padding: 12px 16px; margin: 16px 0;">
        <p style="color: #665522; font-size: 13px; margin: 0;">
            @if(app()->getLocale() == 'es')
                <strong>🌐 Envío Internacional:</strong> Para envíos internacionales (al extranjero), el costo de envío final será calculado por nuestro equipo y te enviaremos una cotización detallada por correo electrónico para proceder con el envío de tu pedido.
            @elseif(app()->getLocale() == 'de')
                <strong>🌐 Internationaler Versand:</strong> Für internationale Sendungen (Ausland) werden die endgültigen Versandkosten von unserem Team berechnet. Wir senden Ihnen ein detailliertes Angebot per E-Mail zu, um mit dem Versand Ihrer Bestellung fortzufahren.
            @else
                <strong>🌐 International Shipping:</strong> For international shipments (abroad), the final shipping cost will be calculated by our team and we will send you a detailed quote via email to proceed with the shipment of your order.
            @endif
        </p>
    </div>
    @endif

    @if($order->payment_method === 'transfer')
    <div class="alert">
        <p><strong>Instrucciones de Pago:</strong> Por favor realiza la transferencia bancaria y envíanos el comprobante por WhatsApp o email para confirmar tu pedido.</p>
    </div>
    @endif

    <div class="btn-wrap">
        <a href="{{ url('/colecciones') }}" class="btn">Seguir Explorando</a>
    </div>

</x-emails.layout>
