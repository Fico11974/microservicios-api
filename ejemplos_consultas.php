<?php

/**
 * EJEMPLOS DE CONSULTAS AVANZADAS
 * Ejercicios 1, 2 y 3 - Sistema de Customers y Products
 *
 * Este archivo contiene ejemplos prácticos de todas las consultas
 * solicitadas en los ejercicios.
 *
 * Para ejecutar: php artisan tinker
 * Luego copiar y pegar las consultas
 */

// ============================================================================
// EJERCICIO 1: CONSULTAS CON CUSTOMERS
// ============================================================================

// 1. Obtener todos los clientes
$customers = \App\Models\Customer::all();

// 2. Clientes con dirección completa
$customersWithAddress = \App\Models\Customer::whereNotNull('address')
    ->whereNotNull('city')
    ->whereNotNull('postal_code')
    ->get();

// 3. Clientes por ciudad (Madrid)
$madridCustomers = \App\Models\Customer::where('city', 'Madrid')->get();
// O usando scope:
$madridCustomers = \App\Models\Customer::byCity('Madrid')->get();

// 4. Clientes por país
$spanishCustomers = \App\Models\Customer::byCountry('España')->get();

// 5. Clientes internacionales (no España)
$internationalCustomers = \App\Models\Customer::where('country', '!=', 'España')->get();

// 6. Buscar cliente por email
$customer = \App\Models\Customer::where('email', 'juan.perez@example.com')->first();

// 7. Obtener dirección completa formateada
$customer = \App\Models\Customer::find(1);
echo $customer->full_address; // Atributo calculado

// 8. Verificar si tiene dirección completa
$customer = \App\Models\Customer::find(1);
if ($customer->hasCompleteAddress()) {
    echo "Tiene dirección completa";
}

// 9. Contar clientes por ciudad
$customersByCity = \App\Models\Customer::selectRaw('city, COUNT(*) as total')
    ->groupBy('city')
    ->orderBy('total', 'desc')
    ->get();

// 10. Clientes sin dirección
$customersWithoutAddress = \App\Models\Customer::whereNull('address')->get();

// ============================================================================
// EJERCICIO 2: CONSULTAS DE STOCK AVANZADO
// ============================================================================

// 1. Productos con stock bajo (usando scope)
$lowStockProducts = \App\Models\Product::lowStock()->get();

// 2. Productos sin stock (usando scope)
$outOfStockProducts = \App\Models\Product::outOfStock()->get();

// 3. Productos descontinuados (usando scope)
$discontinuedProducts = \App\Models\Product::discontinued()->get();

// 4. Productos disponibles (usando scope)
$availableProducts = \App\Models\Product::available()->get();

// 5. Productos más vendidos (simulado con stock bajo)
$bestSellers = \App\Models\Product::bestSellers()->limit(10)->get();

// 6. Verificar estado individual de un producto
$product = \App\Models\Product::find(1);
if ($product->isLowStock()) {
    echo "⚠️ Stock bajo: {$product->stock} unidades";
}
if ($product->isOutOfStock()) {
    echo "❌ Sin stock";
}
if ($product->isDiscontinued()) {
    echo "🚫 Producto descontinuado";
}

// 7. Actualizar stock de un producto
$product = \App\Models\Product::find(1);
$product->updateStock(-5); // Vender 5 unidades
$product->updateStock(10); // Añadir 10 unidades

// O usar métodos específicos:
$product->decreaseStock(5); // Reducir 5 unidades
$product->increaseStock(10); // Aumentar 10 unidades

// 8. Productos que necesitan reposición urgente
$needRestock = \App\Models\Product::whereRaw('stock < min_stock')
    ->where('status', '!=', 'discontinued')
    ->orderBy('stock', 'asc')
    ->get();

// 9. Productos con stock crítico (menos del 50% del mínimo)
$criticalStock = \App\Models\Product::whereRaw('stock < (min_stock * 0.5)')
    ->where('stock', '>', 0)
    ->get();

// 10. Resumen de inventario por estado
$inventorySummary = \App\Models\Product::selectRaw('
        status,
        COUNT(*) as total_products,
        SUM(stock) as total_units,
        AVG(stock) as avg_stock
    ')
    ->groupBy('status')
    ->get();

// ============================================================================
// EJERCICIO 3: BÚSQUEDAS AVANZADAS
// ============================================================================

// 1. Buscar productos por texto en nombre o descripción
$search = 'laptop';
$products = \App\Models\Product::search($search)->get();

// 2. Filtrar por rango de precios
$products = \App\Models\Product::priceRange(50, 200)->get();

// 3. Búsqueda combinada: texto + rango de precio
$products = \App\Models\Product::search('mouse')
    ->priceRange(50, 150)
    ->available()
    ->get();

// 4. Búsqueda con múltiples condiciones
$products = \App\Models\Product::where('status', 'available')
    ->where('stock', '>', 10)
    ->whereBetween('price', [100, 500])
    ->orderBy('price', 'asc')
    ->get();

// 5. Productos disponibles ordenados por precio (menor a mayor)
$products = \App\Models\Product::available()
    ->orderBy('price', 'asc')
    ->get();

// 6. Productos disponibles ordenados por stock (mayor a menor)
$products = \App\Models\Product::available()
    ->orderByStock('desc')
    ->get();

// 7. Búsqueda compleja: texto, precio, stock mínimo
$products = \App\Models\Product::search('monitor')
    ->where('price', '<=', 500)
    ->where('stock', '>=', 5)
    ->orderBy('price', 'asc')
    ->get();

// 8. Productos baratos (menos de 50€) con stock
$cheapProducts = \App\Models\Product::where('price', '<', 50)
    ->where('stock', '>', 0)
    ->orderBy('price', 'asc')
    ->get();

// 9. Productos premium (más de 200€) disponibles
$premiumProducts = \App\Models\Product::where('price', '>', 200)
    ->available()
    ->orderBy('price', 'desc')
    ->get();

// 10. Búsqueda paginada con filtros
$products = \App\Models\Product::search('teclado')
    ->priceRange(50, 200)
    ->available()
    ->orderBy('created_at', 'desc')
    ->paginate(15);

// 11. Búsqueda con OR (productos caros O con stock bajo)
$products = \App\Models\Product::where(function($query) {
        $query->where('price', '>', 300)
              ->orWhereRaw('stock <= min_stock');
    })
    ->where('status', 'available')
    ->get();

// 12. Búsqueda por múltiples criterios ordenados
$products = \App\Models\Product::where('status', 'available')
    ->whereBetween('price', [100, 500])
    ->where('stock', '>', 5)
    ->orderBy('price', 'asc')
    ->orderBy('stock', 'desc')
    ->limit(20)
    ->get();

// 13. Contar productos por rango de precio
$priceRanges = [
    'Económico (< 50€)' => \App\Models\Product::where('price', '<', 50)->count(),
    'Medio (50-200€)' => \App\Models\Product::whereBetween('price', [50, 200])->count(),
    'Premium (> 200€)' => \App\Models\Product::where('price', '>', 200)->count(),
];

// 14. Productos similares (mismo rango de precio +/- 10%)
$product = \App\Models\Product::find(1);
$similarProducts = \App\Models\Product::whereBetween('price', [
        $product->price * 0.9,
        $product->price * 1.1
    ])
    ->where('id', '!=', $product->id)
    ->limit(5)
    ->get();

// 15. Top 10 productos con mejor disponibilidad
$topAvailable = \App\Models\Product::available()
    ->orderBy('stock', 'desc')
    ->limit(10)
    ->get();

// ============================================================================
// CONSULTAS COMBINADAS CUSTOMERS + PRODUCTS (BONUS)
// ============================================================================

// Si tuviéramos relación Order entre Customer y Product:

// Ejemplo conceptual (requeriría crear modelo Order):
/*
// 1. Clientes con más compras
$topCustomers = \App\Models\Customer::withCount('orders')
    ->orderBy('orders_count', 'desc')
    ->limit(10)
    ->get();

// 2. Productos más comprados por clientes de Madrid
$products = \App\Models\Product::whereHas('orders.customer', function($query) {
        $query->where('city', 'Madrid');
    })
    ->withCount('orders')
    ->orderBy('orders_count', 'desc')
    ->get();

// 3. Clientes que compraron productos premium
$customers = \App\Models\Customer::whereHas('orders.products', function($query) {
        $query->where('price', '>', 200);
    })
    ->get();
*/

// ============================================================================
// ESTADÍSTICAS Y REPORTES
// ============================================================================

// 1. Resumen general de productos
$stats = [
    'total_products' => \App\Models\Product::count(),
    'available' => \App\Models\Product::available()->count(),
    'low_stock' => \App\Models\Product::lowStock()->count(),
    'out_of_stock' => \App\Models\Product::outOfStock()->count(),
    'discontinued' => \App\Models\Product::discontinued()->count(),
    'total_value' => \App\Models\Product::selectRaw('SUM(price * stock) as total')->value('total'),
    'avg_price' => \App\Models\Product::avg('price'),
];

// 2. Resumen general de clientes
$customerStats = [
    'total_customers' => \App\Models\Customer::count(),
    'with_address' => \App\Models\Customer::whereNotNull('address')->count(),
    'without_address' => \App\Models\Customer::whereNull('address')->count(),
    'spanish' => \App\Models\Customer::where('country', 'España')->count(),
    'international' => \App\Models\Customer::where('country', '!=', 'España')->count(),
];

// 3. Top 5 ciudades con más clientes
$topCities = \App\Models\Customer::selectRaw('city, COUNT(*) as total')
    ->whereNotNull('city')
    ->groupBy('city')
    ->orderBy('total', 'desc')
    ->limit(5)
    ->get();

// ============================================================================
// EJEMPLOS DE USO EN CONTROLADORES
// ============================================================================

/**
 * Ejemplo de controlador para productos con búsqueda avanzada
 */
class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        // Filtro por búsqueda de texto
        if ($request->has('search')) {
            $query->search($request->search);
        }

        // Filtro por rango de precio
        if ($request->has('min_price') && $request->has('max_price')) {
            $query->priceRange($request->min_price, $request->max_price);
        }

        // Filtro por estado
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filtro solo disponibles
        if ($request->has('available') && $request->available) {
            $query->available();
        }

        // Ordenamiento
        $sortBy = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Paginación
        return $query->paginate(15);
    }

    public function lowStock()
    {
        return Product::lowStock()
            ->orderBy('stock', 'asc')
            ->get();
    }

    public function bestSellers()
    {
        return Product::bestSellers()
            ->limit(10)
            ->get();
    }
}

/**
 * Ejemplo de controlador para clientes
 */
class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::query();

        // Filtro por ciudad
        if ($request->has('city')) {
            $query->byCity($request->city);
        }

        // Filtro por país
        if ($request->has('country')) {
            $query->byCountry($request->country);
        }

        // Filtro solo con dirección completa
        if ($request->has('with_address') && $request->with_address) {
            $query->whereNotNull('address')
                  ->whereNotNull('city')
                  ->whereNotNull('postal_code');
        }

        return $query->paginate(15);
    }
}
