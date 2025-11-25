<?php

/**
 * TUTORIAL: COMANDOS PARA PRACTICAR EN TINKER
 *
 * Para ejecutar: php artisan tinker
 * Luego copia y pega estos comandos uno por uno
 */

// ============================================================================
// EJERCICIO 1: CONSULTAS BÁSICAS DE CUSTOMERS
// ============================================================================

// 1. Ver todos los clientes
Customer::all();

// 2. Contar clientes totales
Customer::count();

// 3. Obtener primer cliente
Customer::first();

// 4. Buscar cliente por ID
Customer::find(1);

// 5. Buscar por email
Customer::where('email', 'juan.perez@example.com')->first();

// 6. Clientes de Madrid (usando scope)
Customer::byCity('Madrid')->get();

// 7. Clientes de España
Customer::byCountry('España')->get();

// 8. Clientes con dirección completa
Customer::whereNotNull('address')
    ->whereNotNull('city')
    ->whereNotNull('postal_code')
    ->get();

// 9. Ver dirección formateada de un cliente
$customer = Customer::find(1);
echo $customer->full_address;

// 10. Verificar si tiene dirección completa
$customer = Customer::find(1);
$customer->hasCompleteAddress(); // true/false

// 11. Clientes sin dirección
Customer::whereNull('address')->get();

// 12. Contar clientes por ciudad
Customer::selectRaw('city, COUNT(*) as total')
    ->whereNotNull('city')
    ->groupBy('city')
    ->orderBy('total', 'desc')
    ->get();

// 13. Buscar por nombre (LIKE)
Customer::where('name', 'like', '%Juan%')->get();

// 14. Clientes ordenados por nombre
Customer::orderBy('name', 'asc')->get();

// 15. Paginación (15 por página)
Customer::paginate(15);

// ============================================================================
// EJERCICIO 2: CONSULTAS DE PRODUCTS - STOCK
// ============================================================================

// 1. Ver todos los productos
Product::all();

// 2. Productos disponibles
Product::available()->get();

// 3. Productos con stock bajo
Product::lowStock()->get();

// 4. Productos sin stock
Product::outOfStock()->get();

// 5. Productos descontinuados
Product::discontinued()->get();

// 6. Top 10 más vendidos
Product::bestSellers()->limit(10)->get();

// 7. Verificar estado de un producto
$product = Product::find(1);
echo "¿Stock bajo? " . ($product->isLowStock() ? 'SÍ' : 'NO') . "\n";
echo "¿Sin stock? " . ($product->isOutOfStock() ? 'SÍ' : 'NO') . "\n";
echo "¿Descontinuado? " . ($product->isDiscontinued() ? 'SÍ' : 'NO') . "\n";

// 8. Actualizar stock - VENDER 5 unidades
$product = Product::find(1);
$product->decreaseStock(5);
echo "Nuevo stock: " . $product->stock . "\n";

// 9. Actualizar stock - AÑADIR 10 unidades
$product = Product::find(1);
$product->increaseStock(10);
echo "Nuevo stock: " . $product->stock . "\n";

// 10. Productos que necesitan reposición URGENTE
Product::whereRaw('stock < min_stock')
    ->where('status', '!=', 'discontinued')
    ->orderBy('stock', 'asc')
    ->get();

// 11. Stock crítico (menos del 50% del mínimo)
Product::whereRaw('stock < (min_stock * 0.5)')
    ->where('stock', '>', 0)
    ->get();

// 12. Productos con stock entre 10 y 50
Product::whereBetween('stock', [10, 50])->get();

// 13. Resumen de inventario por estado
Product::selectRaw('
        status,
        COUNT(*) as cantidad,
        SUM(stock) as total_unidades,
        AVG(price) as precio_promedio
    ')
    ->groupBy('status')
    ->get();

// ============================================================================
// EJERCICIO 3: BÚSQUEDAS AVANZADAS
// ============================================================================

// 1. Buscar por texto en nombre o descripción
Product::search('laptop')->get();

// 2. Filtrar por rango de precio (50-200€)
Product::priceRange(50, 200)->get();

// 3. Búsqueda combinada: texto + precio
Product::search('mouse')
    ->priceRange(50, 150)
    ->get();

// 4. Búsqueda avanzada: texto + precio + disponibilidad
Product::search('monitor')
    ->priceRange(100, 500)
    ->available()
    ->orderBy('price', 'asc')
    ->get();

// 5. Productos baratos (menos de 50€) con stock
Product::where('price', '<', 50)
    ->where('stock', '>', 0)
    ->orderBy('price', 'asc')
    ->get();

// 6. Productos premium (más de 200€) disponibles
Product::where('price', '>', 200)
    ->available()
    ->orderBy('price', 'desc')
    ->get();

// 7. Buscar con múltiples palabras
Product::where(function($query) {
        $query->where('name', 'like', '%laptop%')
              ->orWhere('name', 'like', '%notebook%')
              ->orWhere('description', 'like', '%portátil%');
    })
    ->get();

// 8. Productos en un rango de precio EXCLUYENDO algunos
Product::whereBetween('price', [100, 500])
    ->whereNotIn('status', ['discontinued', 'out_of_stock'])
    ->get();

// 9. Top 10 productos más caros disponibles
Product::available()
    ->orderBy('price', 'desc')
    ->limit(10)
    ->get();

// 10. Top 10 productos más baratos disponibles
Product::available()
    ->orderBy('price', 'asc')
    ->limit(10)
    ->get();

// 11. Productos similares (mismo rango de precio ±10%)
$product = Product::find(1);
Product::whereBetween('price', [
        $product->price * 0.9,
        $product->price * 1.1
    ])
    ->where('id', '!=', $product->id)
    ->limit(5)
    ->get();

// 12. Búsqueda paginada con filtros
Product::search('teclado')
    ->priceRange(50, 200)
    ->available()
    ->orderBy('price', 'asc')
    ->paginate(10);

// 13. Contar productos por rango de precio
echo "Económico (< 50€): " . Product::where('price', '<', 50)->count() . "\n";
echo "Medio (50-200€): " . Product::whereBetween('price', [50, 200])->count() . "\n";
echo "Premium (> 200€): " . Product::where('price', '>', 200)->count() . "\n";

// 14. Estadísticas generales
$stats = [
    'total' => Product::count(),
    'disponibles' => Product::available()->count(),
    'stock_bajo' => Product::lowStock()->count(),
    'sin_stock' => Product::outOfStock()->count(),
    'valor_total_inventario' => Product::selectRaw('SUM(price * stock) as total')->value('total'),
    'precio_promedio' => round(Product::avg('price'), 2),
    'stock_promedio' => round(Product::avg('stock'), 0),
];
print_r($stats);

// ============================================================================
// EJERCICIOS PRÁCTICOS - SIMULACIONES REALES
// ============================================================================

// CASO 1: Proceso de venta
$product = Product::where('name', 'Laptop HP ProBook')->first();
if ($product && $product->stock >= 1) {
    echo "Vendiendo 1 unidad de: {$product->name}\n";
    echo "Stock antes: {$product->stock}\n";
    $product->decreaseStock(1);
    echo "Stock después: {$product->stock}\n";
    echo "Nuevo estado: {$product->status}\n";
}

// CASO 2: Recepción de inventario
$product = Product::where('status', 'out_of_stock')->first();
if ($product) {
    echo "Producto sin stock: {$product->name}\n";
    echo "Stock antes: {$product->stock}\n";
    $product->increaseStock(20);
    echo "Stock después: {$product->stock}\n";
    echo "Nuevo estado: {$product->status}\n";
}

// CASO 3: Reporte diario de inventario
echo "=== REPORTE DE INVENTARIO ===\n";
echo "Total productos: " . Product::count() . "\n";
echo "Disponibles: " . Product::available()->count() . "\n";
echo "Alertas de stock bajo: " . Product::lowStock()->count() . "\n";
echo "Sin stock: " . Product::outOfStock()->count() . "\n";
echo "Descontinuados: " . Product::discontinued()->count() . "\n";
echo "Valor total: $" . Product::selectRaw('SUM(price * stock) as total')->value('total') . "\n";

// CASO 4: Generar lista de compras (productos a reponer)
echo "\n=== LISTA DE COMPRAS ===\n";
$toRestock = Product::lowStock()->orderBy('stock', 'asc')->get();
foreach ($toRestock as $p) {
    $needed = $p->max_stock - $p->stock;
    echo "- {$p->name}: Comprar {$needed} unidades (Stock actual: {$p->stock})\n";
}

// CASO 5: Buscar productos para un cliente
$budget = 150;
echo "\n=== PRODUCTOS DISPONIBLES HASTA \${$budget} ===\n";
$affordable = Product::available()
    ->where('price', '<=', $budget)
    ->orderBy('price', 'asc')
    ->limit(10)
    ->get();
foreach ($affordable as $p) {
    echo "- {$p->name}: \${$p->price} (Stock: {$p->stock})\n";
}

// CASO 6: Reporte de clientes por región
echo "\n=== DISTRIBUCIÓN DE CLIENTES ===\n";
$distribution = Customer::selectRaw('country, COUNT(*) as total')
    ->groupBy('country')
    ->orderBy('total', 'desc')
    ->get();
foreach ($distribution as $d) {
    echo "{$d->country}: {$d->total} clientes\n";
}

// ============================================================================
// CONSULTAS COMPLEJAS - NIVEL AVANZADO
// ============================================================================

// 1. Productos con stock bajo Y precio alto (necesitan atención)
Product::lowStock()
    ->where('price', '>', 100)
    ->orderBy('price', 'desc')
    ->get();

// 2. Clientes de España SIN dirección completa
Customer::where('country', 'España')
    ->where(function($query) {
        $query->whereNull('address')
              ->orWhereNull('city')
              ->orWhereNull('postal_code');
    })
    ->get();

// 3. Productos disponibles con mejor relación stock/min_stock
Product::available()
    ->selectRaw('*, (stock / min_stock) as ratio')
    ->orderBy('ratio', 'desc')
    ->limit(10)
    ->get();

// 4. Buscar productos con condiciones complejas
Product::where(function($query) {
        // Productos baratos con buen stock
        $query->where(function($q) {
            $q->where('price', '<', 50)
              ->where('stock', '>', 20);
        })
        // O productos premium sin importar stock
        ->orWhere(function($q) {
            $q->where('price', '>', 300)
              ->where('status', 'available');
        });
    })
    ->orderBy('price', 'desc')
    ->get();

// 5. Clientes que necesitan actualizar su dirección
Customer::whereNotNull('email')
    ->where(function($query) {
        $query->whereNull('address')
              ->orWhereNull('city');
    })
    ->select('name', 'email', 'city')
    ->get();

// ============================================================================
// FIN DE LOS EJEMPLOS
// ============================================================================

echo "\n✅ Todos los ejemplos están listos para ejecutar en Tinker!\n";
echo "Ejecuta: php artisan tinker\n";
echo "Y copia/pega los comandos uno por uno.\n";
