#!/bin/bash

# Script de prueba para los ejercicios de Customers y Products
# Este script ejecuta consultas de ejemplo usando php artisan tinker

echo "================================================"
echo "🧪 PRUEBAS DE EJERCICIOS - CUSTOMERS & PRODUCTS"
echo "================================================"
echo ""

cd /workspaces/microservicios-api

echo "📊 1. ESTADÍSTICAS GENERALES"
echo "----------------------------"
php artisan tinker --execute="
echo '✅ Total Customers: ' . \App\Models\Customer::count() . PHP_EOL;
echo '✅ Total Products: ' . \App\Models\Product::count() . PHP_EOL;
echo '✅ Productos disponibles: ' . \App\Models\Product::where('status', 'available')->count() . PHP_EOL;
echo '✅ Clientes con dirección: ' . \App\Models\Customer::whereNotNull('address')->count() . PHP_EOL;
"

echo ""
echo "📍 2. CLIENTES POR CIUDAD"
echo "-------------------------"
php artisan tinker --execute="
\$cities = \App\Models\Customer::selectRaw('city, COUNT(*) as total')
    ->whereNotNull('city')
    ->groupBy('city')
    ->orderBy('total', 'desc')
    ->get();
foreach (\$cities as \$city) {
    echo \"📍 {\$city->city}: {\$city->total} clientes\" . PHP_EOL;
}
"

echo ""
echo "⚠️  3. PRODUCTOS CON STOCK BAJO"
echo "-------------------------------"
php artisan tinker --execute="
\$lowStock = \App\Models\Product::lowStock()->orderBy('stock')->limit(5)->get(['name', 'stock', 'min_stock']);
foreach (\$lowStock as \$product) {
    echo \"⚠️  {\$product->name}: {\$product->stock}/{\$product->min_stock}\" . PHP_EOL;
}
"

echo ""
echo "❌ 4. PRODUCTOS SIN STOCK"
echo "-------------------------"
php artisan tinker --execute="
\$outOfStock = \App\Models\Product::outOfStock()->limit(3)->get(['name', 'stock', 'status']);
foreach (\$outOfStock as \$product) {
    echo \"❌ {\$product->name}: {\$product->stock} unidades ({\$product->status})\" . PHP_EOL;
}
"

echo ""
echo "🔥 5. PRODUCTOS MÁS VENDIDOS (TOP 5)"
echo "------------------------------------"
php artisan tinker --execute="
\$bestSellers = \App\Models\Product::bestSellers()->limit(5)->get(['name', 'stock', 'price']);
foreach (\$bestSellers as \$product) {
    echo \"🔥 {\$product->name} - \${\$product->price} - Stock: {\$product->stock}\" . PHP_EOL;
}
"

echo ""
echo "💰 6. PRODUCTOS POR RANGO DE PRECIO (50-200€)"
echo "----------------------------------------------"
php artisan tinker --execute="
\$products = \App\Models\Product::priceRange(50, 200)->available()->limit(5)->get(['name', 'price']);
foreach (\$products as \$product) {
    echo \"💰 {\$product->name}: \${\$product->price}\" . PHP_EOL;
}
"

echo ""
echo "🔍 7. BÚSQUEDA DE PRODUCTOS (Laptop)"
echo "------------------------------------"
php artisan tinker --execute="
\$products = \App\Models\Product::search('laptop')->limit(3)->get(['name', 'price', 'stock']);
foreach (\$products as \$product) {
    echo \"🔍 {\$product->name} - \${\$product->price} - Stock: {\$product->stock}\" . PHP_EOL;
}
"

echo ""
echo "🌍 8. CLIENTES INTERNACIONALES"
echo "-------------------------------"
php artisan tinker --execute="
\$international = \App\Models\Customer::where('country', '!=', 'España')->get(['name', 'city', 'country']);
foreach (\$international as \$customer) {
    echo \"🌍 {\$customer->name} - {\$customer->city}, {\$customer->country}\" . PHP_EOL;
}
"

echo ""
echo "📈 9. RESUMEN DE INVENTARIO"
echo "---------------------------"
php artisan tinker --execute="
\$summary = \App\Models\Product::selectRaw('
    status,
    COUNT(*) as cantidad,
    SUM(stock) as total_unidades
')
->groupBy('status')
->get();
foreach (\$summary as \$item) {
    echo \"📈 {\$item->status}: {\$item->cantidad} productos ({\$item->total_unidades} unidades)\" . PHP_EOL;
}
"

echo ""
echo "✅ 10. PRUEBA DE MÉTODOS DEL MODELO"
echo "------------------------------------"
php artisan tinker --execute="
\$product = \App\Models\Product::where('stock', '>', 5)->first();
if (\$product) {
    echo \"Producto: {\$product->name}\" . PHP_EOL;
    echo \"Stock actual: {\$product->stock}\" . PHP_EOL;
    echo \"¿Stock bajo?: \" . (\$product->isLowStock() ? 'SÍ ⚠️' : 'NO ✅') . PHP_EOL;
    echo \"¿Sin stock?: \" . (\$product->isOutOfStock() ? 'SÍ ❌' : 'NO ✅') . PHP_EOL;
    echo \"¿Descontinuado?: \" . (\$product->isDiscontinued() ? 'SÍ 🚫' : 'NO ✅') . PHP_EOL;
}
"

echo ""
echo "✅ 11. ACTUALIZACIÓN DE STOCK"
echo "------------------------------"
php artisan tinker --execute="
\$product = \App\Models\Product::where('stock', '>', 10)->first();
if (\$product) {
    echo \"Producto: {\$product->name}\" . PHP_EOL;
    echo \"Stock inicial: {\$product->stock}\" . PHP_EOL;

    \$product->decreaseStock(5);
    echo \"Después de vender 5: {\$product->stock}\" . PHP_EOL;

    \$product->increaseStock(3);
    echo \"Después de añadir 3: {\$product->stock}\" . PHP_EOL;
}
"

echo ""
echo "================================================"
echo "✅ TODAS LAS PRUEBAS COMPLETADAS"
echo "================================================"
