# 🚀 PARCIAL - EJERCICIOS PRÁCTICOS (3 EJERCICIOS)

**Instrucciones:** Implementa cada ejercicio práctico siguiendo las especificaciones. Cada ejercicio vale 5 puntos (Total: 15 puntos).

---

## EJERCICIO PRÁCTICO 1: Sistema de Órdenes y Detalles (5 puntos)

### DESCRIPCIÓN
Implementa un sistema de órdenes de compra con detalles de productos. Una orden puede tener múltiples productos con cantidades.

### REQUISITOS

1. **Crear migración para tabla `orders`** con:
   - `id`: Primary key
   - `customer_id`: Foreign key a customers
   - `order_date`: Fecha de la orden
   - `total`: Decimal(10,2)
   - `status`: ENUM ['pending', 'completed', 'cancelled']
   - `timestamps`

2. **Crear migración para tabla `order_details`** (tabla pivote con datos extra):
   - `id`: Primary key
   - `order_id`: Foreign key a orders
   - `product_id`: Foreign key a products
   - `quantity`: Integer
   - `unit_price`: Decimal(10,2)
   - `subtotal`: Decimal(10,2)

3. **Crear modelo `Order`** con:
   - Relación con Customer (`belongsTo`)
   - Relación con OrderDetail (`hasMany`)
   - Relación con Products a través de order_details
   - Método `calculateTotal()` que suma todos los subtotales

4. **Crear modelo `OrderDetail`** con:
   - Relación con Order (`belongsTo`)
   - Relación con Product (`belongsTo`)

---

### SOLUCIÓN COMPLETA

#### 1. Migración: `create_orders_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->date('order_date');
            $table->decimal('total', 10, 2)->default(0);
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
```

#### 2. Migración: `create_order_details_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->integer('quantity');
            $table->decimal('unit_price', 10, 2);
            $table->decimal('subtotal', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_details');
    }
};
```

#### 3. Modelo: `Order.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'customer_id',
        'order_date',
        'total',
        'status',
    ];

    protected $casts = [
        'order_date' => 'date',
        'total' => 'decimal:2',
    ];

    // Relación con Customer
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    // Relación con OrderDetails
    public function details(): HasMany
    {
        return $this->hasMany(OrderDetail::class);
    }

    // Relación con Products a través de order_details
    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_details')
                    ->withPivot('quantity', 'unit_price', 'subtotal')
                    ->withTimestamps();
    }

    // Método para calcular total
    public function calculateTotal(): float
    {
        $total = $this->details()->sum('subtotal');
        $this->update(['total' => $total]);
        return $total;
    }
}
```

#### 4. Modelo: `OrderDetail.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderDetail extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'unit_price',
        'subtotal',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    // Relación con Order
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    // Relación con Product
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // Calcular subtotal automáticamente
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($detail) {
            $detail->subtotal = $detail->quantity * $detail->unit_price;
        });
    }
}
```

#### 5. Ejemplo de Uso

```php
// Crear una orden
$order = Order::create([
    'customer_id' => 1,
    'order_date' => now(),
    'status' => 'pending',
]);

// Agregar detalles (productos)
$order->details()->create([
    'product_id' => 1,
    'quantity' => 2,
    'unit_price' => 999.99,
]);

$order->details()->create([
    'product_id' => 2,
    'quantity' => 1,
    'unit_price' => 49.99,
]);

// Calcular total
$total = $order->calculateTotal();
echo "Total de la orden: $" . $total;

// Obtener orden con todos sus datos
$order = Order::with(['customer', 'details.product'])->find(1);
foreach ($order->details as $detail) {
    echo "{$detail->product->name} x {$detail->quantity} = {$detail->subtotal}\n";
}
```

### CRITERIOS DE EVALUACIÓN (5 puntos)
- ✅ Migraciones correctas con relaciones (2 pts)
- ✅ Modelos con relaciones definidas (2 pts)
- ✅ Método `calculateTotal()` funcional (1 pt)

---

## EJERCICIO PRÁCTICO 2: API REST para Gestión de Customers (5 puntos)

### DESCRIPCIÓN
Crea un controlador API completo para gestionar customers con validaciones y respuestas JSON apropiadas.

### REQUISITOS

1. **Crear controlador `CustomerController`** con métodos:
   - `index()`: Listar todos los customers con paginación
   - `show($id)`: Mostrar un customer específico
   - `store(Request)`: Crear nuevo customer con validación
   - `update(Request, $id)`: Actualizar customer con validación
   - `destroy($id)`: Eliminar customer

2. **Validaciones requeridas:**
   - `name`: requerido, string, máximo 100 caracteres
   - `email`: requerido, email válido, único
   - `phone`: opcional, formato numérico

3. **Definir rutas API** en `routes/api.php`

4. **Códigos de respuesta HTTP apropiados**

---

### SOLUCIÓN COMPLETA

#### 1. Controlador: `CustomerController.php`

```php
<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CustomerController extends Controller
{
    /**
     * Listar todos los customers con paginación
     */
    public function index(): JsonResponse
    {
        $customers = Customer::orderBy('created_at', 'desc')
                            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $customers,
        ]);
    }

    /**
     * Mostrar un customer específico
     */
    public function show($id): JsonResponse
    {
        $customer = Customer::find($id);

        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Customer no encontrado',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $customer,
        ]);
    }

    /**
     * Crear nuevo customer
     */
    public function store(Request $request): JsonResponse
    {
        // Validación
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:customers,email',
            'phone' => 'nullable|string|max:20',
        ]);

        // Crear customer
        $customer = Customer::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Customer creado exitosamente',
            'data' => $customer,
        ], 201);
    }

    /**
     * Actualizar customer existente
     */
    public function update(Request $request, $id): JsonResponse
    {
        $customer = Customer::find($id);

        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Customer no encontrado',
            ], 404);
        }

        // Validación (ignorar email del customer actual)
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:customers,email,' . $id,
            'phone' => 'nullable|string|max:20',
        ]);

        // Actualizar
        $customer->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Customer actualizado exitosamente',
            'data' => $customer,
        ]);
    }

    /**
     * Eliminar customer
     */
    public function destroy($id): JsonResponse
    {
        $customer = Customer::find($id);

        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Customer no encontrado',
            ], 404);
        }

        $customer->delete();

        return response()->json([
            'success' => true,
            'message' => 'Customer eliminado exitosamente',
        ], 204);
    }
}
```

#### 2. Rutas: `routes/api.php`

```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;

// Opción 1: Rutas individuales
Route::get('/customers', [CustomerController::class, 'index']);
Route::get('/customers/{id}', [CustomerController::class, 'show']);
Route::post('/customers', [CustomerController::class, 'store']);
Route::put('/customers/{id}', [CustomerController::class, 'update']);
Route::delete('/customers/{id}', [CustomerController::class, 'destroy']);

// Opción 2: Resource (más simple) - Comentar opción 1 si usas esta
// Route::apiResource('customers', CustomerController::class);
```

#### 3. Ejemplo de Peticiones (usando cURL o Postman)

```bash
# Listar customers
GET http://localhost:8000/api/customers

# Obtener customer específico
GET http://localhost:8000/api/customers/1

# Crear customer
POST http://localhost:8000/api/customers
Content-Type: application/json

{
    "name": "María Garc��a",
    "email": "maria@example.com",
    "phone": "555-1234"
}

# Actualizar customer
PUT http://localhost:8000/api/customers/1
Content-Type: application/json

{
    "name": "María García López",
    "email": "maria@example.com",
    "phone": "555-5678"
}

# Eliminar customer
DELETE http://localhost:8000/api/customers/1
```

#### 4. Respuestas Esperadas

```json
// GET /api/customers (200 OK)
{
    "success": true,
    "data": {
        "current_page": 1,
        "data": [
            {
                "id": 1,
                "name": "Juan Pérez",
                "email": "juan@example.com",
                "phone": "555-1234"
            }
        ],
        "per_page": 10,
        "total": 43
    }
}

// POST /api/customers (201 Created)
{
    "success": true,
    "message": "Customer creado exitosamente",
    "data": {
        "id": 44,
        "name": "María García",
        "email": "maria@example.com",
        "phone": "555-1234"
    }
}

// GET /api/customers/999 (404 Not Found)
{
    "success": false,
    "message": "Customer no encontrado"
}
```

### CRITERIOS DE EVALUACIÓN (5 puntos)
- ✅ Controlador con todos los métodos CRUD (2 pts)
- ✅ Validaciones correctas (1.5 pts)
- ✅ Rutas API definidas y códigos HTTP apropiados (1.5 pts)

---

## EJERCICIO PRÁCTICO 3: Seeder Completo con Factory y Relaciones (5 puntos)

### DESCRIPCIÓN
Crea un seeder completo que genere datos de prueba para un blog con Users, Posts, Categories y Tags.

### REQUISITOS

1. **Factory para `Post`** con:
   - `title`: Título aleatorio
   - `body`: Contenido largo
   - `published_at`: Fecha aleatoria (algunos null)
   - `user_id`: Usuario aleatorio
   - `category_id`: Categoría aleatoria

2. **Seeder `BlogSeeder`** que:
   - Cree 5 categorías predefinidas
   - Cree 10 tags predefinidos
   - Cree 3 usuarios
   - Cree 20 posts asignados aleatoriamente a usuarios
   - Asigne entre 1-3 tags aleatorios a cada post

3. **Ejecutar en orden correcto** respetando dependencias

---

### SOLUCIÓN COMPLETA

#### 1. Factory: `PostFactory.php`

```php
<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition(): array
    {
        return [
            'title' => fake()->sentence(),
            'body' => fake()->paragraphs(5, true),
            'published_at' => fake()->optional(0.7)->dateTimeBetween('-1 year', 'now'),
            'user_id' => User::inRandomOrder()->first()->id ?? User::factory(),
            'category_id' => Category::inRandomOrder()->first()->id ?? Category::factory(),
        ];
    }

    // Estado para posts no publicados
    public function draft()
    {
        return $this->state(fn (array $attributes) => [
            'published_at' => null,
        ]);
    }

    // Estado para posts publicados
    public function published()
    {
        return $this->state(fn (array $attributes) => [
            'published_at' => fake()->dateTimeBetween('-1 year', 'now'),
        ]);
    }
}
```

#### 2. Seeder: `BlogSeeder.php`

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Post;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        // Limpiar tablas
        Post::query()->delete();
        Tag::query()->delete();
        Category::query()->delete();
        User::query()->delete();

        echo "🧹 Tablas limpiadas\n";

        // 1. Crear categorías predefinidas
        $categories = [
            'Tecnología',
            'Programación',
            'Laravel',
            'JavaScript',
            'Base de Datos',
        ];

        foreach ($categories as $name) {
            Category::create(['name' => $name, 'slug' => strtolower($name)]);
        }
        echo "✅ Creadas " . Category::count() . " categorías\n";

        // 2. Crear tags predefinidos
        $tags = [
            'PHP',
            'Laravel',
            'Vue.js',
            'React',
            'API REST',
            'MySQL',
            'PostgreSQL',
            'Eloquent',
            'JavaScript',
            'TypeScript',
        ];

        foreach ($tags as $name) {
            Tag::create(['name' => $name, 'slug' => strtolower($name)]);
        }
        echo "✅ Creados " . Tag::count() . " tags\n";

        // 3. Crear usuarios
        $users = User::factory()->count(3)->create();
        echo "✅ Creados " . User::count() . " usuarios\n";

        // 4. Crear posts
        Post::factory()
            ->count(20)
            ->create()
            ->each(function ($post) {
                // Asignar entre 1 y 3 tags aleatorios a cada post
                $tagIds = Tag::inRandomOrder()->limit(rand(1, 3))->pluck('id');
                $post->tags()->attach($tagIds);
            });

        echo "✅ Creados " . Post::count() . " posts con tags asignados\n";

        // 5. Resumen
        echo "\n📊 RESUMEN:\n";
        echo "   - Usuarios: " . User::count() . "\n";
        echo "   - Categorías: " . Category::count() . "\n";
        echo "   - Tags: " . Tag::count() . "\n";
        echo "   - Posts: " . Post::count() . "\n";
        echo "   - Posts publicados: " . Post::whereNotNull('published_at')->count() . "\n";
        echo "   - Posts en borrador: " . Post::whereNull('published_at')->count() . "\n";
    }
}
```

#### 3. Actualizar `DatabaseSeeder.php`

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            BlogSeeder::class,
            // Otros seeders...
        ]);
    }
}
```

#### 4. Modelos necesarios (definiciones de relaciones)

```php
// Post.php
class Post extends Model
{
    protected $fillable = ['title', 'body', 'published_at', 'user_id', 'category_id'];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
}

// Tag.php
class Tag extends Model
{
    protected $fillable = ['name', 'slug'];

    public function posts()
    {
        return $this->belongsToMany(Post::class);
    }
}

// Category.php
class Category extends Model
{
    protected $fillable = ['name', 'slug'];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
```

#### 5. Comandos para ejecutar

```bash
# Ejecutar migraciones
php artisan migrate:fresh

# Ejecutar seeder específico
php artisan db:seed --class=BlogSeeder

# O ejecutar todos los seeders
php artisan migrate:fresh --seed
```

#### 6. Consultas de prueba

```php
// En Tinker: php artisan tinker

// Ver posts con sus relaciones
$posts = Post::with(['user', 'category', 'tags'])->get();

// Contar posts por usuario
User::withCount('posts')->get();

// Posts de una categoría específica
$category = Category::where('name', 'Laravel')->first();
$posts = $category->posts;

// Posts con un tag específico
$tag = Tag::where('name', 'PHP')->first();
$posts = $tag->posts;

// Posts publicados recientemente
$recentPosts = Post::whereNotNull('published_at')
    ->orderBy('published_at', 'desc')
    ->limit(5)
    ->get();
```

### CRITERIOS DE EVALUACIÓN (5 puntos)
- ✅ Factory configurado correctamente (1.5 pts)
- ✅ Seeder crea todos los datos requeridos (2 pts)
- ✅ Relaciones funcionan correctamente (1.5 pts)

---

## RESUMEN DE EJERCICIOS

| Ejercicio | Tema Principal | Puntos |
|-----------|---------------|--------|
| 1 | Sistema de Órdenes con relaciones y cálculos | 5 pts |
| 2 | API REST completa con validaciones | 5 pts |
| 3 | Seeder completo con Factory y relaciones N:M | 5 pts |

---

**Total: 3 ejercicios × 5 puntos = 15 puntos**

**Total del Parcial Completo: 40 + 10 + 20 + 15 + 15 = 100 puntos** 🎯
