@php
    $paymentLabel = $order->payment_method === 'transfer' ? 'Transferencia Bancaria' : 'Tarjeta de Crédito/Débito';
@endphp
<x-emails.layout subject="Nuevo pedido recibido">

    <span class="badge" style="background:#e8f5e9; color:#2e7d32;">🛒 Nuevo Pedido</span>
    <h1 class="email-title">Pedido #{{ $order->order_number }}</h1>
    <div class="divider"></div>

    <h2 class="section-title">Datos del Pedido</h2>
    <table class="info-table">
        <tr><td>N° Pedido</td><td><strong>{{ $order->order_number }}</strong></td></tr>
        <tr><td>Fecha</td><td>{{ $order->created_at->format('d/m/Y H:i') }}</td></tr>
        <tr><td>Estado</td><td>Pendiente</td></tr>
        <tr><td>Método de Pago</td><td><strong>{{ $paymentLabel }}</strong></td></tr>
    </table>

    <h2 class="section-title">Datos del Cliente</h2>
    <table class="info-table">
        <tr><td>Nombre</td><td><strong>{{ $order->customer_name }}</strong></td></tr>
        <tr><td>Email</td><td><a href="mailto:{{ $order->customer_email }}" style="color:#2e7d32; text-decoration:none; font-weight:bold;">{{ $order->customer_email }}</a></td></tr>
        <tr><td>Teléfono</td><td>{{ $order->customer_phone }}</td></tr>
        <tr><td>Dirección de Envío</td><td>{{ $order->shipping_address }}</td></tr>
        @if($order->notes)
        <tr><td>Notas</td><td><em>{{ $order->notes }}</em></td></tr>
        @endif
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

    <h2 class="section-title">Resumen Económico</h2>
    <table class="info-table">
        <tr><td>Subtotal</td><td style="text-align:right;">S/ {{ number_format($order->subtotal, 2) }}</td></tr>
        <tr>
            <td>Envío</td>
            <td style="text-align:right;">
                @if(str_contains($order->shipping_address, 'Internacional'))
                    <span style="color:#c59b27;font-weight:bold;">A cotizar por correo</span>
                @else
                    S/ {{ number_format($order->shipping_cost, 2) }}
                @endif
            </td>
        </tr>
        <tr><td><strong>Total</strong></td><td style="text-align:right;"><strong style="color:#1b5e20;font-size:16px;">S/ {{ number_format($order->total, 2) }}</strong></td></tr>
    </table>

    @if(str_contains($order->shipping_address, 'Internacional'))
    <div class="alert" style="background-color: #fffde7; border-left: 4px solid #c59b27; padding: 12px 16px; margin: 16px 0;">
        <p style="color: #665522; font-size: 13px; margin: 0;"><strong>🌐 ENVÍO INTERNACIONAL:</strong> El cliente ha seleccionado envío al extranjero. Debes cotizar el envío con tu proveedor y responder al correo del cliente (<strong>{{ $order->customer_email }}</strong>) indicando el costo final del envío.</p>
    </div>
    @endif

    <div class="btn-wrap">
        <a href="{{ url('/admin/orders') }}" class="btn">Ver Pedido en el Panel</a>
    </div>

</x-emails.layout>
