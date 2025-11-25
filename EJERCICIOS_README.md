# 📚 EJERCICIOS PRÁCTICOS - LARAVEL ORM

## Resumen de Ejercicios Completados

### ✅ Ejercicio 1: Modelo Customer Ampliado
- ✅ Migración con campos adicionales (address, city, postal_code, country)
- ✅ Modelo Customer con scopes y métodos útiles
- ✅ Factory con estados (withoutAddress, fromMadrid, international)
- ✅ Seeder con 43 clientes de prueba

### ✅ Ejercicio 2: Sistema de Stock Avanzado
- ✅ Migración de productos con campos de stock (min_stock, max_stock, status)
- ✅ Métodos en modelo: isLowStock(), isOutOfStock(), updateStock()
- ✅ Scopes: lowStock(), outOfStock(), discontinued(), bestSellers()
- ✅ Factory con estados (lowStock, outOfStock, discontinued, bestSeller)
- ✅ Seeder con 70 productos de prueba

### ✅ Ejercicio 3: Búsquedas Avanzadas
- ✅ Scope search() para buscar por nombre o descripción
- ✅ Scope priceRange() para filtrar por rango de precios
- ✅ Múltiples scopes combinables
- ✅ Ejemplos de consultas complejas

---

## 🚀 Comandos Ejecutados

```bash
# 1. Crear modelos con migraciones
php artisan make:model Customer -m
php artisan make:model Product -m

# 2. Crear factories
php artisan make:factory CustomerFactory
php artisan make:factory ProductFactory

# 3. Crear seeders
php artisan make:seeder CustomerSeeder
php artisan make:seeder ProductSeeder

# 4. Ejecutar migraciones
php artisan migrate

# 5. Ejecutar seeders
php artisan db:seed --class=CustomerSeeder
php artisan db:seed --class=ProductSeeder
```

---

## 📊 Datos Creados

### Customers (43 total)
- 37 clientes con dirección completa
- 13 clientes de Madrid
- 5 clientes internacionales
- 6 clientes sin dirección

### Products (70 total)
- 60 productos disponibles
- 18 productos con stock bajo
- 10 productos sin stock
- 4 productos descontinuados

---

## 🧪 Pruebas en Tinker

Para probar las consultas, ejecuta:

```bash
php artisan tinker
```

### Ejemplos de Consultas para Customers

```php
// 1. Todos los clientes
$customers = Customer::all();

// 2. Clientes de Madrid
$madridCustomers = Customer::byCity('Madrid')->get();

// 3. Clientes con dirección completa
$customersWithAddress = Customer::whereNotNull('address')
    ->whereNotNull('city')
    ->whereNotNull('postal_code')
    ->get();

// 4. Obtener dirección formateada
$customer = Customer::find(1);
echo $customer->full_address;

// 5. Verificar dirección completa
$customer = Customer::find(1);
if ($customer->hasCompleteAddress()) {
    echo "✅ Tiene dirección completa";
}

// 6. Clientes internacionales
$international = Customer::where('country', '!=', 'España')->get();

// 7. Contar clientes por ciudad
Customer::selectRaw('city, COUNT(*) as total')
    ->groupBy('city')
    ->orderBy('total', 'desc')
    ->get();
```

### Ejemplos de Consultas para Products

```php
// 1. Productos con stock bajo
$lowStock = Product::lowStock()->get();

// 2. Productos sin stock
$outOfStock = Product::outOfStock()->get();

// 3. Productos descontinuados
$discontinued = Product::discontinued()->get();

// 4. Productos disponibles
$available = Product::available()->get();

// 5. Productos más vendidos (top 10)
$bestSellers = Product::bestSellers()->limit(10)->get();

// 6. Buscar por texto
$products = Product::search('laptop')->get();

// 7. Filtrar por rango de precio
$products = Product::priceRange(50, 200)->get();

// 8. Búsqueda combinada
$products = Product::search('mouse')
    ->priceRange(50, 150)
    ->available()
    ->orderBy('price', 'asc')
    ->get();

// 9. Verificar estado de un producto
$product = Product::find(1);
echo $product->isLowStock() ? '⚠️ Stock bajo' : '✅ Stock OK';
echo $product->isOutOfStock() ? '❌ Sin stock' : '✅ Con stock';

// 10. Actualizar stock
$product = Product::find(1);
$product->decreaseStock(5);  // Vender 5 unidades
$product->increaseStock(10); // Añadir 10 unidades

// 11. Productos baratos con stock
$cheap = Product::where('price', '<', 50)
    ->where('stock', '>', 0)
    ->orderBy('price', 'asc')
    ->get();

// 12. Productos premium disponibles
$premium = Product::where('price', '>', 200)
    ->available()
    ->orderBy('price', 'desc')
    ->get();

// 13. Busqueda compleja
$products = Product::search('monitor')
    ->where('price', '<=', 500)
    ->where('stock', '>=', 5)
    ->orderBy('price', 'asc')
    ->get();
```

### Estadísticas y Reportes

```php
// Resumen de productos
$stats = [
    'total' => Product::count(),
    'disponibles' => Product::available()->count(),
    'stock_bajo' => Product::lowStock()->count(),
    'sin_stock' => Product::outOfStock()->count(),
    'descontinuados' => Product::discontinued()->count(),
    'precio_promedio' => Product::avg('price'),
    'valor_total' => Product::selectRaw('SUM(price * stock) as total')->value('total'),
];

// Resumen de clientes
$customerStats = [
    'total' => Customer::count(),
    'con_direccion' => Customer::whereNotNull('address')->count(),
    'sin_direccion' => Customer::whereNull('address')->count(),
    'espanoles' => Customer::where('country', 'España')->count(),
    'internacionales' => Customer::where('country', '!=', 'España')->count(),
];

// Top 5 ciudades
$topCities = Customer::selectRaw('city, COUNT(*) as total')
    ->whereNotNull('city')
    ->groupBy('city')
    ->orderBy('total', 'desc')
    ->limit(5)
    ->get();
```

---

## 📁 Archivos Creados

### Migraciones
- `2025_11_17_142133_create_customers_table.php`
- `2025_11_17_142140_create_products_table.php`

### Modelos
- `app/Models/Customer.php` (con scopes y métodos)
- `app/Models/Product.php` (con scopes y métodos avanzados)

### Factories
- `database/factories/CustomerFactory.php` (con estados)
- `database/factories/ProductFactory.php` (con estados)

### Seeders
- `database/seeders/CustomerSeeder.php`
- `database/seeders/ProductSeeder.php`

### Documentación
- `ejemplos_consultas.php` (consultas completas y ejemplos)
- `EJERCICIOS_README.md` (este archivo)

---

## 🎯 Características Implementadas

### Modelo Customer
✅ Campos: name, email, phone, address, city, postal_code, country
✅ Scopes: byCity(), byCountry()
✅ Métodos: hasCompleteAddress()
✅ Atributos: full_address (calculado)
✅ Factory con 3 estados

### Modelo Product
✅ Campos: name, description, price, stock, min_stock, max_stock, status
✅ Métodos de stock:
  - isLowStock()
  - isOutOfStock()
  - isDiscontinued()
  - updateStock($quantity)
  - decreaseStock($quantity)
  - increaseStock($quantity)

✅ Scopes de filtrado:
  - lowStock()
  - outOfStock()
  - available()
  - discontinued()
  - bestSellers()
  - search($text)
  - priceRange($min, $max)
  - orderByStock($direction)

✅ Factory con 6 estados

---

## 🔍 Consultas Avanzadas Implementadas

### 1. Múltiples condiciones
```php
Product::where('status', 'available')
    ->where('stock', '>', 10)
    ->whereBetween('price', [100, 500])
    ->orderBy('price', 'asc')
    ->get();
```

### 2. Filtros por rango de precios
```php
Product::priceRange(50, 200)->get();
```

### 3. Búsqueda por texto
```php
Product::search('laptop')->get();
```

### 4. Ordenar por múltiples criterios
```php
Product::orderBy('price', 'asc')
    ->orderBy('stock', 'desc')
    ->get();
```

### 5. Consultas combinadas
```php
Product::search('monitor')
    ->priceRange(100, 500)
    ->available()
    ->orderBy('price', 'asc')
    ->get();
```

### 6. Consultas con OR
```php
Product::where(function($query) {
        $query->where('price', '>', 300)
              ->orWhereRaw('stock <= min_stock');
    })
    ->where('status', 'available')
    ->get();
```

---

## 📈 Casos de Uso Prácticos

### 1. Dashboard de Inventario
```php
// Productos que necesitan reposición
$needRestock = Product::lowStock()
    ->orderBy('stock', 'asc')
    ->get();

// Alertas de stock crítico
$critical = Product::whereRaw('stock < (min_stock * 0.5)')
    ->where('stock', '>', 0)
    ->get();
```

### 2. Catálogo de Productos
```php
// Búsqueda de usuario
$search = request('search');
$minPrice = request('min_price', 0);
$maxPrice = request('max_price', 9999);

$products = Product::search($search)
    ->priceRange($minPrice, $maxPrice)
    ->available()
    ->paginate(15);
```

### 3. Gestión de Clientes
```php
// Clientes de una ciudad específica
$customers = Customer::byCity('Madrid')
    ->whereNotNull('address')
    ->get();

// Envío internacional
$international = Customer::where('country', '!=', 'España')
    ->get();
```

---

## 🧰 Testing

Para ejecutar tests (si se crearan):

```bash
# Test de Customer
php artisan test --filter=CustomerTest

# Test de Product
php artisan test --filter=ProductTest
```

---

## 📚 Conceptos Aprendidos

1. **Migraciones avanzadas**: Campos con valores por defecto, enums, nullable
2. **Eloquent ORM**: Scopes, métodos personalizados, atributos calculados
3. **Factories con estados**: Crear datos de prueba variados
4. **Seeders**: Poblar base de datos con datos realistas
5. **Consultas complejas**: Combinación de scopes, where, orderBy
6. **Búsquedas full-text**: Buscar en múltiples campos
7. **Filtros por rango**: whereBetween para precios, fechas, etc.
8. **Lógica de negocio en modelos**: Encapsular comportamiento

---

## 🎓 Resumen para el Parcial

### Comandos clave:
```bash
php artisan make:model Nombre -m          # Modelo + Migración
php artisan make:factory NombreFactory    # Factory
php artisan make:seeder NombreSeeder      # Seeder
php artisan migrate                       # Ejecutar migraciones
php artisan db:seed --class=NombreSeeder  # Ejecutar seeder
```

### Conceptos clave:
- **Scopes**: Consultas reutilizables (`scopeNombre()`)
- **Métodos personalizados**: Lógica de negocio en modelos
- **Factory states**: Variaciones de datos de prueba
- **Consultas encadenadas**: `Model::scope1()->scope2()->get()`
- **whereBetween**: Filtrar por rango de valores
- **like**: Búsqueda de texto parcial (`where('campo', 'like', "%texto%")`)

---

## ✅ Checklist de Ejercicios

- [x] Ejercicio 1: Ampliar modelo Customer
  - [x] Migración con campos adicionales
  - [x] Actualizar modelo Customer
  - [x] Crear seeder para clientes con direcciones
  - [x] Practicar consultas con los nuevos campos

- [x] Ejercicio 2: Sistema de Stock Avanzado
  - [x] Agregar campos a productos
  - [x] Crear métodos isLowStock(), isOutOfStock(), updateStock()
  - [x] Crear consultas para stock bajo, productos más vendidos, descontinuados

- [x] Ejercicio 3: Búsquedas Avanzadas
  - [x] Consultas con múltiples condiciones
  - [x] Filtros por rango de precios
  - [x] Buscar productos por texto
  - [x] Ordenar resultados por diferentes criterios

---

**¡Ejercicios completados exitosamente! 🎉**
