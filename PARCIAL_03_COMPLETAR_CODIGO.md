# 💻 PARCIAL - COMPLETAR CÓDIGO (10 EJERCICIOS)

**Instrucciones:** Completa el código faltante en cada ejercicio. Cada ejercicio vale 2 puntos (Total: 20 puntos).

---

## EJERCICIO 1: Crear Migración para Tabla Products

Completa la migración para crear una tabla `products` con los campos especificados:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->____________('name');                        // Campo string para nombre
            $table->____________('description')->nullable();     // Campo texto para descripción
            $table->____________('price', 10, 2);               // Campo decimal con 2 decimales
            $table->____________('stock')->default(0);          // Campo entero para stock
            $table->____________('status', ['active', 'inactive'])->default('active');  // ENUM
            $table->____________();                              // Timestamps automáticos
        });
    }

    public function down(): void
    {
        Schema::____________('products');
    }
};
```

### RESPUESTA:
```php
$table->string('name');
$table->text('description')->nullable();
$table->decimal('price', 10, 2);
$table->integer('stock')->default(0);
$table->enum('status', ['active', 'inactive'])->default('active');
$table->timestamps();

Schema::dropIfExists('products');
```

---

## EJERCICIO 2: Modelo Product con Fillable

Completa el modelo `Product` definiendo los campos permitidos para asignación masiva:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $____________ = [
        'name',
        'description',
        'price',
        'stock',
        'status',
    ];

    protected $____________ = [
        'price' => '____________:2',
        'stock' => '____________',
    ];
}
```

### RESPUESTA:
```php
protected $fillable = [
    'name',
    'description',
    'price',
    'stock',
    'status',
];

protected $casts = [
    'price' => 'decimal:2',
    'stock' => 'integer',
];
```

---

## EJERCICIO 3: Seeder para Products

Completa el seeder para crear 10 productos usando factory:

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function ____________(): void
    {
        // Limpiar tabla
        Product::____________();

        // Crear 10 productos usando factory
        Product::____________()->____________(10)->____________();

        echo "✅ Se crearon " . Product::____________() . " productos\n";
    }
}
```

### RESPUESTA:
```php
public function run(): void
{
    Product::truncate();
    
    Product::factory()->count(10)->create();
    
    echo "✅ Se crearon " . Product::count() . " productos\n";
}
```

---

## EJERCICIO 4: Relación 1:N (User hasMany Posts)

Completa las relaciones entre User y Post:

```php
// Modelo User
class User extends Model
{
    public function posts()
    {
        return $this->____________(Post::class);
    }
}

// Modelo Post
class Post extends Model
{
    public function user()
    {
        return $this->____________(User::class);
    }
}

// Uso: Obtener posts de un usuario con eager loading
$user = User::____________('posts')->find(1);
foreach ($user->posts as $post) {
    echo $post->title;
}
```

### RESPUESTA:
```php
// User
return $this->hasMany(Post::class);

// Post
return $this->belongsTo(User::class);

// Uso
$user = User::with('posts')->find(1);
```

---

## EJERCICIO 5: Relación N:M (Posts y Tags)

Completa la relación muchos a muchos entre Post y Tag:

```php
// Modelo Post
class Post extends Model
{
    public function tags()
    {
        return $this->____________(Tag::class);
    }
}

// Modelo Tag
class Tag extends Model
{
    public function posts()
    {
        return $this->____________(Post::class);
    }
}

// Uso: Agregar tags a un post
$post = Post::find(1);
$post->tags()->____________([1, 2, 3]);  // Adjuntar tags con IDs 1, 2, 3

// Obtener posts con sus tags
$posts = Post::____________('tags')->get();
```

### RESPUESTA:
```php
// Post
return $this->belongsToMany(Tag::class);

// Tag
return $this->belongsToMany(Post::class);

// Uso
$post->tags()->attach([1, 2, 3]);

$posts = Post::with('tags')->get();
```

---

## EJERCICIO 6: Controlador API Resource

Completa los métodos del controlador para una API REST:

```php
<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Listar todos los productos
    public function index()
    {
        $products = Product::____________();
        return ____________()->json($products);
    }

    // Mostrar un producto específico
    public function show($id)
    {
        $product = Product::____________($id);
        return response()->json($product);
    }

    // Crear nuevo producto
    public function store(Request $request)
    {
        $product = Product::____________($request->all());
        return response()->json($product, ____________);  // Código HTTP 201
    }

    // Actualizar producto
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $product->____________($request->all());
        return response()->json($product);
    }

    // Eliminar producto
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->____________();
        return response()->json(null, ____________);  // Código HTTP 204
    }
}
```

### RESPUESTA:
```php
public function index()
{
    $products = Product::all();
    return response()->json($products);
}

public function show($id)
{
    $product = Product::findOrFail($id);
    return response()->json($product);
}

public function store(Request $request)
{
    $product = Product::create($request->all());
    return response()->json($product, 201);
}

public function update(Request $request, $id)
{
    $product = Product::findOrFail($id);
    $product->update($request->all());
    return response()->json($product);
}

public function destroy($id)
{
    $product = Product::findOrFail($id);
    $product->delete();
    return response()->json(null, 204);
}
```

---

## EJERCICIO 7: Definir Rutas API

Completa las definiciones de rutas para la API de productos:

```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

// Forma 1: Definir rutas manualmente
Route::____________('/products', [ProductController::class, 'index']);
Route::____________('/products', [ProductController::class, 'store']);
Route::____________('/products/{id}', [ProductController::class, 'show']);
Route::____________('/products/{id}', [ProductController::class, 'update']);
Route::____________('/products/{id}', [ProductController::class, 'destroy']);

// Forma 2: Usar resource (más simple)
Route::____________('products', ProductController::class);
```

### RESPUESTA:
```php
// Forma 1
Route::get('/products', [ProductController::class, 'index']);
Route::post('/products', [ProductController::class, 'store']);
Route::get('/products/{id}', [ProductController::class, 'show']);
Route::put('/products/{id}', [ProductController::class, 'update']);
Route::delete('/products/{id}', [ProductController::class, 'destroy']);

// Forma 2
Route::apiResource('products', ProductController::class);
```

---

## EJERCICIO 8: Consultas Eloquent Avanzadas

Completa las consultas Eloquent según lo solicitado:

```php
// 1. Obtener productos con precio mayor a 100
$products = Product::____________('price', '>', 100)->get();

// 2. Obtener productos ordenados por precio descendente
$products = Product::____________('price', '____________')->get();

// 3. Obtener productos con stock entre 10 y 50
$products = Product::____________('stock', [10, 50])->get();

// 4. Contar productos activos
$count = Product::where('status', 'active')->____________();

// 5. Buscar productos cuyo nombre contenga "laptop"
$products = Product::where('name', '____________', '%laptop%')->get();

// 6. Obtener solo 5 productos
$products = Product::____________(5)->get();

// 7. Actualizar stock de todos los productos inactivos
Product::where('status', 'inactive')->____________(['stock' => 0]);
```

### RESPUESTA:
```php
// 1
$products = Product::where('price', '>', 100)->get();

// 2
$products = Product::orderBy('price', 'desc')->get();

// 3
$products = Product::whereBetween('stock', [10, 50])->get();

// 4
$count = Product::where('status', 'active')->count();

// 5
$products = Product::where('name', 'like', '%laptop%')->get();

// 6
$products = Product::limit(5)->get();

// 7
Product::where('status', 'inactive')->update(['stock' => 0]);
```

---

## EJERCICIO 9: Enum en PHP 8.1+

Completa la definición y uso de un Enum para estados de producto:

```php
<?php

namespace App\Enums;

____________ ProductStatus: string
{
    case ACTIVE = '____________';
    case INACTIVE = '____________';
    case DISCONTINUED = '____________';

    public function label(): string
    {
        return ____________ ($this) {
            self::ACTIVE => 'Activo',
            self::INACTIVE => 'Inactivo',
            self::DISCONTINUED => 'Descontinuado',
        };
    }
}

// Uso en modelo
class Product extends Model
{
    protected $casts = [
        'status' => ProductStatus::____________,
    ];
}

// Uso en código
$product->status = ProductStatus::____________;
$product->save();

echo $product->status->____________();  // "Activo"
```

### RESPUESTA:
```php
enum ProductStatus: string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case DISCONTINUED = 'discontinued';

    public function label(): string
    {
        return match ($this) {
            self::ACTIVE => 'Activo',
            self::INACTIVE => 'Inactivo',
            self::DISCONTINUED => 'Descontinuado',
        };
    }
}

// Uso en modelo
protected $casts = [
    'status' => ProductStatus::class,
];

// Uso en código
$product->status = ProductStatus::ACTIVE;
$product->save();

echo $product->status->label();  // "Activo"
```

---

## EJERCICIO 10: firstOrCreate y updateOrCreate

Completa el uso de métodos Eloquent especiales:

```php
// 1. Buscar usuario por email, si no existe crearlo
$user = User::____________(
    ['email' => 'juan@example.com'],           // Criterio de búsqueda
    ['name' => 'Juan', 'password' => 'hash']   // Datos si se crea
);

// 2. Buscar producto por SKU, actualizar o crear
$product = Product::____________(
    ['sku' => 'LAP001'],                       // Criterio de búsqueda
    ['name' => 'Laptop', 'price' => 999.99]    // Datos a actualizar/crear
);

// 3. Incrementar stock de un producto
$product = Product::find(1);
$product->____________('stock', 10);  // Incrementar en 10

// 4. Decrementar vistas de un post
$post = Post::find(1);
$post->____________('views');  // Decrementar en 1
```

### RESPUESTA:
```php
// 1
$user = User::firstOrCreate(
    ['email' => 'juan@example.com'],
    ['name' => 'Juan', 'password' => 'hash']
);

// 2
$product = Product::updateOrCreate(
    ['sku' => 'LAP001'],
    ['name' => 'Laptop', 'price' => 999.99]
);

// 3
$product->increment('stock', 10);

// 4
$post->decrement('views');
```

---

## RESUMEN DE RESPUESTAS CLAVE

| Ejercicio | Conceptos Clave |
|-----------|-----------------|
| 1 | `string`, `text`, `decimal`, `integer`, `enum`, `timestamps`, `dropIfExists` |
| 2 | `$fillable`, `$casts`, `decimal:2`, `integer` |
| 3 | `run()`, `truncate()`, `factory()`, `count()`, `create()`, `count()` |
| 4 | `hasMany`, `belongsTo`, `with` |
| 5 | `belongsToMany`, `attach`, `with` |
| 6 | `all()`, `response()`, `findOrFail`, `create`, `201`, `update`, `delete`, `204` |
| 7 | `get`, `post`, `put`, `delete`, `apiResource` |
| 8 | `where`, `orderBy`, `desc`, `whereBetween`, `count()`, `like`, `limit`, `update` |
| 9 | `enum`, `active/inactive/discontinued`, `match`, `class`, `ACTIVE`, `label()` |
| 10 | `firstOrCreate`, `updateOrCreate`, `increment`, `decrement` |

---

**Total: 10 ejercicios × 2 puntos = 20 puntos**
