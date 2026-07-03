<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\ProductVariant;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalSales = Order::where('payment_status', 'paid')->sum('total');
        $pendingOrders = Order::where('status', 'pending')->count();
        $lowStockVariants = ProductVariant::where('stock', '<=', 5)->count();

        return [
            Stat::make('Ventas Consolidadas', 'S/. ' . number_format($totalSales, 2))
                ->description('Ventas totales pagadas')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('success'),
            Stat::make('Pedidos Pendientes', $pendingOrders)
                ->description('Por procesar en el tablero')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),
            Stat::make('Productos con Stock Bajo', $lowStockVariants)
                ->description('5 o menos unidades en inventario')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color($lowStockVariants > 0 ? 'danger' : 'success'),
        ];
    }
}
