# 🚀 GUÍA RÁPIDA DE USO - EJERCICIOS LARAVEL

## 📋 Contenido del Proyecto

Este proyecto incluye la implementación completa de 3 ejercicios de Laravel sobre Eloquent ORM, migraciones, seeders y consultas avanzadas.

---

## 🎯 ¿Qué se ha creado?

### 1. Sistema de Clientes (Customers)
- Modelo con dirección completa (address, city, postal_code, country)
- 43 clientes de prueba
- Scopes y métodos útiles

### 2. Sistema de Productos (Products)
- Modelo con control de stock avanzado
- 70 productos de prueba
- Estados: available, out_of_stock, discontinued
- Métodos para gestión de inventario

### 3. Consultas Avanzadas
- Búsqueda por texto
- Filtros por rango de precio
- Combinación de múltiples condiciones
- Ordenamiento flexible

---

## 🚀 Inicio Rápido

### 1️⃣ Ver los datos creados
```bash
php artisan tinker
```

Luego ejecuta:
```php
// Ver clientes
Customer::all();

// Ver productos
Product::all();

// Ver productos con stock bajo
Product::lowStock()->get();
```

### 2️⃣ Ejecutar pruebas automáticas
```bash
./test-ejercicios.sh
```

Esto mostrará:
- Estadísticas generales
- Clientes por ciudad
- Productos con stock bajo
- Productos más vendidos
- Y más...

### 3️⃣ Probar consultas interactivas
Abre el archivo `tutorial_tinker_ejercicios.php` y copia/pega los comandos en Tinker.

---

## 📚 Archivos de Documentación

| Archivo | Descripción |
|---------|-------------|
| `EJERCICIOS_README.md` | 📖 Documentación completa con todos los detalles |
| `RESUMEN_EJERCICIOS.md` | 📊 Resumen ejecutivo de lo realizado |
| `ejemplos_consultas.php` | 💻 +300 líneas de consultas de ejemplo |
| `tutorial_tinker_ejercicios.php` | 🎓 Tutorial paso a paso para Tinker |
| `GUIA_USO.md` | 📝 Este archivo |

---

## 🧪 Ejemplos Prácticos

### Consultas de Customers

```php
// En Tinker (php artisan tinker):

// Todos los clientes
Customer::all();

// Clientes de Madrid
Customer::byCity('Madrid')->get();

// Clientes con dirección completa
Customer::whereNotNull('address')->whereNotNull('city')->get();

// Ver dirección formateada
$customer = Customer::find(1);
echo $customer->full_address;

// Verificar dirección completa
$customer->hasCompleteAddress(); // true o false
```

### Consultas de Products

```php
// Stock bajo
Product::lowStock()->get();

// Sin stock
Product::outOfStock()->get();

// Buscar por texto
Product::search('laptop')->get();

// Filtrar por precio
Product::priceRange(50, 200)->get();

// Búsqueda combinada
Product::search('mouse')->priceRange(50, 150)->available()->get();

// Actualizar stock
$product = Product::find(1);
$product->decreaseStock(5);  // Vender 5 unidades
$product->increaseStock(10); // Añadir 10 unidades

// Verificar estado
$product->isLowStock();    // true/false
$product->isOutOfStock();  // true/false
```

---

## 🔧 Comandos Útiles

### Reiniciar base de datos
```bash
# Borrar todo y volver a crear
php artisan migrate:fresh --seed
```

### Ver estado de migraciones
```bash
php artisan migrate:status
```

### Ver rutas disponibles
```bash
php artisan route:list
```

### Ejecutar seeders específicos
```bash
php artisan db:seed --class=CustomerSeeder
php artisan db:seed --class=ProductSeeder
```

---

## 📊 Estadísticas Actuales

```
CUSTOMERS
├─ Total: 43 clientes
├─ Con dirección: 37
├─ Sin dirección: 6
├─ Madrid: 13
├─ Barcelona: 3
├─ Internacionales: 5
└─ España: 38

PRODUCTS
├─ Total: 70 productos
├─ Disponibles: 60
├─ Stock bajo: 18
├─ Sin stock: 10
└─ Descontinuados: 4
```

---

## 🎓 Para Estudiar

### 1. Revisar los modelos
```
app/Models/Customer.php   - Ver scopes y métodos
app/Models/Product.php    - Ver lógica de stock
```

### 2. Revisar las migraciones
```
database/migrations/2025_11_17_142133_create_customers_table.php
database/migrations/2025_11_17_142140_create_products_table.php
```

### 3. Revisar los seeders
```
database/seeders/CustomerSeeder.php
database/seeders/ProductSeeder.php
```

### 4. Revisar las factories
```
database/factories/CustomerFactory.php  - Ver estados
database/factories/ProductFactory.php   - Ver estados
```

---

## 💡 Casos de Uso Prácticos

### 1. Gestión de Inventario

**Ver productos que necesitan reposición:**
```php
Product::lowStock()->orderBy('stock', 'asc')->get();
```

**Generar orden de compra:**
```php
$toRestock = Product::lowStock()->get();
foreach ($toRestock as $product) {
    $needed = $product->max_stock - $product->stock;
    echo "Comprar {$needed} unidades de {$product->name}\n";
}
```

### 2. Proceso de Venta

**Vender un producto:**
```php
$product = Product::find(1);

// Verificar stock disponible
if ($product->stock >= 5) {
    $product->decreaseStock(5);
    echo "Venta realizada. Nuevo stock: {$product->stock}\n";
} else {
    echo "Stock insuficiente\n";
}
```

### 3. Búsqueda de Productos

**Cliente busca "laptop" entre 500€ y 1000€:**
```php
$results = Product::search('laptop')
    ->priceRange(500, 1000)
    ->available()
    ->orderBy('price', 'asc')
    ->get();
```

### 4. Reportes

**Reporte diario de inventario:**
```php
echo "=== REPORTE DE INVENTARIO ===\n";
echo "Total: " . Product::count() . "\n";
echo "Disponibles: " . Product::available()->count() . "\n";
echo "Alertas: " . Product::lowStock()->count() . "\n";
echo "Sin stock: " . Product::outOfStock()->count() . "\n";
```

---

## 🔍 Troubleshooting

### ❌ Error: "Class 'Customer' not found"
**Solución:**
```php
// En Tinker, usa la ruta completa:
\App\Models\Customer::all();
```

### ❌ Error: "Base table or view not found"
**Solución:**
```bash
# Ejecutar migraciones
php artisan migrate
```

### ❌ Error: "No data found"
**Solución:**
```bash
# Ejecutar seeders
php artisan db:seed --class=CustomerSeeder
php artisan db:seed --class=ProductSeeder
```

### ❌ Quiero empezar desde cero
**Solución:**
```bash
# Borrar todo y recrear
php artisan migrate:fresh --seed
```

---

## 📞 Recursos Adicionales

- **Documentación Laravel**: https://laravel.com/docs
- **Eloquent ORM**: https://laravel.com/docs/eloquent
- **Query Builder**: https://laravel.com/docs/queries
- **Migraciones**: https://laravel.com/docs/migrations
- **Seeders**: https://laravel.com/docs/seeding

---

## ✅ Checklist de Verificación

Antes del parcial, asegúrate de entender:

- [x] Cómo crear migraciones (`php artisan make:migration`)
- [x] Cómo crear modelos (`php artisan make:model`)
- [x] Qué son los scopes y cómo usarlos
- [x] Cómo hacer consultas con `where`, `whereBetween`, `like`
- [x] Cómo ordenar resultados con `orderBy`
- [x] Cómo crear seeders y factories
- [x] Cómo usar Tinker para probar consultas
- [x] Métodos básicos de Eloquent: `all()`, `find()`, `where()`, `get()`
- [x] Cómo actualizar registros
- [x] Cómo hacer consultas combinadas

---

## 🎯 Próximos Pasos

1. ✅ Revisar `EJERCICIOS_README.md` para teoría completa
2. ✅ Ejecutar `./test-ejercicios.sh` para ver ejemplos
3. ✅ Abrir Tinker y probar consultas del `tutorial_tinker_ejercicios.php`
4. ✅ Revisar el código de los modelos para entender la lógica
5. ✅ Crear tus propias consultas personalizadas

---

## 🏆 Resultado

```
✅ Sistema completamente funcional
✅ 113 registros de prueba
✅ 50+ consultas documentadas
✅ 4 archivos de documentación
✅ Script de pruebas automáticas
✅ 100% listo para el parcial
```

**¡Éxito en tu parcial! 🚀**

---

## 📝 Notas Finales

- Todos los archivos están documentados con comentarios
- Los ejemplos son funcionales y se pueden ejecutar directamente
- Los seeders son idempotentes (se pueden ejecutar múltiples veces)
- Las factories usan Faker para datos realistas
- Los scopes son reutilizables y combinables
- La lógica de negocio está en los modelos (buena práctica)

**Cualquier duda, revisa los archivos de documentación o abre Tinker para experimentar.**
