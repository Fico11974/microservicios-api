# 📚 GUÍA DE ESTUDIO PARA PARCIAL - LARAVEL

## 📋 Índice de Contenidos

1. [Comandos Laravel Esenciales](#comandos-laravel-esenciales)
2. [Migraciones](#migraciones)
3. [Modelos Eloquent](#modelos-eloquent)
4. [Seeders](#seeders)
5. [Enums](#enums)
6. [Relaciones entre Modelos](#relaciones-entre-modelos)
7. [Controladores y Rutas](#controladores-y-rutas)
8. [Scripts de Automatización del Proyecto](#scripts-de-automatización-del-proyecto)
9. [Storage y Archivos](#storage-y-archivos)
10. [Preguntas Frecuentes de Parcial](#preguntas-frecuentes-de-parcial)
11. [Comando PHP Artisan Serve](#comando-php-artisan-serve-detallado)
12. [Endpoints y APIs RESTful](#endpoints-y-apis-restful)
13. [Testing con PHP Artisan Test](#testing-con-php-artisan-test)
14. [ORM Eloquent - Mapeo Objeto-Relacional](#orm-eloquent-mapeo-objeto-relacional)
15. [Promesas en JavaScript](#promesas-en-javascript)
16. [DOM - Document Object Model](#dom-document-object-model)

---

## 1. COMANDOS LARAVEL ESENCIALES

### Comandos de Artisan Fundamentales

```bash
# MIGRACIONES
php artisan make:migration create_posts_table          # Crear migración
php artisan migrate                                     # Ejecutar migraciones pendientes
php artisan migrate:status                              # Ver estado de migraciones
php artisan migrate:rollback                            # Revertir última migración
php artisan migrate:refresh                             # Revertir todo y re-migrar (¡CUIDADO!)
php artisan migrate:fresh                               # Eliminar tablas y re-migrar

# MODELOS
php artisan make:model Post                             # Crear modelo
php artisan make:model Post -m                          # Crear modelo + migración
php artisan make:model Post -mf                         # Modelo + migración + factory
php artisan make:model Post -a                          # Modelo + todo (all)

# SEEDERS
php artisan make:seeder PostSeeder                      # Crear seeder
php artisan db:seed                                     # Ejecutar DatabaseSeeder
php artisan db:seed --class=PostSeeder                  # Ejecutar seeder específico
php artisan migrate:fresh --seed                        # Migrar todo y ejecutar seeders

# CONTROLADORES
php artisan make:controller PostController              # Crear controlador vacío
php artisan make:controller PostController --resource   # Controlador con métodos REST
php artisan make:controller PostController --api        # Controlador API (sin create/edit)

# OTROS ÚTILES
php artisan route:list                                  # Listar todas las rutas
php artisan tinker                                      # Consola interactiva
php artisan storage:link                                # Crear symlink de storage
php artisan cache:clear                                 # Limpiar caché
php artisan config:clear                                # Limpiar caché de configuración
```

---

## 2. MIGRACIONES

### ¿Qué es una Migración?

Una **migración** es un archivo PHP que contiene instrucciones para modificar la estructura de la base de datos (crear, modificar o eliminar tablas y columnas). Es como un "control de versiones" para tu base de datos.

#### Conceptos Clave de las Migraciones:

**¿Por qué usar migraciones?**
- ✅ **Versionado**: Cada cambio queda registrado y documentado
- ✅ **Reproducibilidad**: Cualquier miembro del equipo puede replicar la estructura
- ✅ **Reversibilidad**: Puedes deshacer cambios con `rollback`
- ✅ **Sincronización**: Todo el equipo trabaja con la misma estructura
- ✅ **Automatización**: Se ejecutan en orden cronológico automáticamente
- ✅ **Portabilidad**: Funcionan en cualquier entorno (desarrollo, staging, producción)

**Anatomía de una migración:**
```
database/migrations/
└── 2024_11_17_153045_create_posts_table.php
    ├── Timestamp: 2024_11_17_153045 (define el orden de ejecución)
    ├── Acción: create (create, update, add, drop)
    ├── Tabla: posts_table
    └── Métodos:
        ├── up()   → Se ejecuta al hacer "migrate" (crear/modificar)
        └── down() → Se ejecuta al hacer "rollback" (revertir)
```

**Ciclo de vida de una migración:**
1. **Creación**: `php artisan make:migration create_posts_table`
2. **Definición**: Escribes código en `up()` y `down()`
3. **Ejecución**: `php artisan migrate` (ejecuta todas las pendientes)
4. **Registro**: Laravel guarda en tabla `migrations` cuáles ya se ejecutaron
5. **Reversión** (opcional): `php artisan migrate:rollback`

**Estados de una migración:**
- ⏳ **Pendiente**: Existe el archivo pero no se ha ejecutado
- ✅ **Ejecutada**: Ya se aplicó a la base de datos (registrada en tabla `migrations`)
- ↩️ **Revertida**: Se deshizo con rollback

**Tipos de migraciones:**

| Tipo | Comando | Propósito |
|------|---------|-----------|
| **Create** | `create_posts_table` | Crear una tabla nueva |
| **Add** | `add_status_to_posts_table` | Agregar columna(s) a tabla existente |
| **Modify** | `modify_price_in_products_table` | Modificar columna existente |
| **Drop** | `drop_posts_table` | Eliminar tabla completa |
| **Rename** | `rename_posts_to_articles_table` | Renombrar tabla |

### Estructura Básica de una Migración

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta la migración - Crea la tabla
     */
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            // Definición de columnas aquí
        });
    }

    /**
     * Revierte la migración - Elimina la tabla
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
```

### Tipos de Columnas Más Usados

```php
// NÚMEROS
$table->id();                              // BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
$table->bigInteger('views');               // BIGINT
$table->integer('stock');                  // INT
$table->tinyInteger('age');                // TINYINT (0-255)
$table->decimal('price', 8, 2);           // DECIMAL(8,2) - 8 dígitos, 2 decimales
$table->float('rating', 3, 1);            // FLOAT(3,1)
$table->unsignedBigInteger('size');       // BIGINT UNSIGNED

// TEXTO
$table->string('name');                    // VARCHAR(255)
$table->string('title', 100);             // VARCHAR(100)
$table->text('content');                   // TEXT
$table->longText('description');          // LONGTEXT

// FECHAS
$table->date('birth_date');               // DATE
$table->time('start_time');               // TIME
$table->dateTime('published_at');         // DATETIME
$table->timestamp('created_at');          // TIMESTAMP
$table->timestamps();                     // created_at + updated_at (TIMESTAMP)

// BOOLEANOS
$table->boolean('is_active');             // TINYINT(1)
$table->boolean('is_active')->default(true);

// ENUMS
$table->enum('status', ['draft', 'published', 'archived']);

// JSON
$table->json('metadata');                 // JSON

// LLAVES FORÁNEAS
$table->foreignId('user_id')              // BIGINT UNSIGNED
      ->constrained()                     // FK a tabla 'users'
      ->onDelete('cascade');              // Eliminar en cascada
```

### Modificadores de Columnas

```php
$table->string('email')->nullable();           // Permite NULL
$table->string('username')->unique();          // Índice UNIQUE
$table->integer('order')->default(0);          // Valor por defecto
$table->timestamp('created_at')->useCurrent(); // Usa timestamp actual
$table->text('description')->comment('User bio'); // Comentario SQL
```

### Índices y Restricciones

```php
// ÍNDICES
$table->index('email');                        // Índice simple
$table->index(['user_id', 'post_id']);        // Índice compuesto
$table->unique('email');                       // Índice único
$table->unique(['user_id', 'channel_id']);    // Índice único compuesto

// LLAVES FORÁNEAS - Forma corta
$table->foreignId('user_id')
      ->constrained()
      ->onDelete('cascade');

// LLAVES FORÁNEAS - Forma larga
$table->foreignId('author_id')
      ->constrained('users')                   // Especifica tabla
      ->onDelete('cascade');                   // cascade, restrict, set null

// Opciones de onDelete y onUpdate
->onDelete('cascade')    // Eliminar registros relacionados
->onDelete('set null')   // Establecer NULL
->onDelete('restrict')   // Prevenir eliminación
->onUpdate('cascade')    // Actualizar en cascada
```

### Ejemplo Completo: Migración de Posts

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\PostType;
use App\Enums\PostStatus;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            // Llave primaria
            $table->id();
            
            // Relación con usuario (FK)
            $table->foreignId('user_id')
                  ->constrained()
                  ->onDelete('cascade');
            
            // Campos de texto
            $table->string('name', 255);
            $table->text('content');
            
            // Enums
            $table->enum('type', PostType::values());
            $table->enum('status', PostStatus::values());
            
            // Campos opcionales
            $table->string('moderator_comments', 100)->nullable();
            
            // Fechas especiales
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamp('deadline')->nullable();
            $table->timestamp('timeout')->nullable();
            
            // Timestamps automáticos
            $table->timestamps();
            
            // Índices
            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
```

### Migración de Tabla Pivot (Many-to-Many)

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('post_channels', function (Blueprint $table) {
            $table->id();
            
            // Llaves foráneas
            $table->foreignId('post_id')
                  ->constrained()
                  ->onDelete('cascade');
                  
            $table->foreignId('channel_id')
                  ->constrained()
                  ->onDelete('cascade');
            
            // Timestamps
            $table->timestamps();
            
            // Índice único compuesto (un post solo una vez por canal)
            $table->unique(['post_id', 'channel_id']);
            
            // Índice para búsquedas
            $table->index('channel_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('post_channels');
    }
};
```

---

## 3. MODELOS ELOQUENT

### ¿Qué es un Modelo Eloquent?

Un **modelo Eloquent** es una clase PHP que representa una tabla de la base de datos y proporciona una forma elegante y expresiva de interactuar con los datos mediante el patrón **ORM (Object-Relational Mapping)**.

#### Conceptos Fundamentales:

**ORM (Object-Relational Mapping):**
- Traduce objetos PHP a registros de base de datos y viceversa
- Permite trabajar con datos como si fueran objetos PHP en lugar de escribir SQL
- Abstrae las operaciones CRUD (Create, Read, Update, Delete)

**¿Por qué usar Eloquent?**
- ✅ **Sintaxis Expresiva**: `User::where('active', true)->get()` vs SQL complejo
- ✅ **Type Safety**: PHP verifica tipos en tiempo de desarrollo
- ✅ **Relaciones Fáciles**: Define relaciones con métodos simples
- ✅ **Protección**: Previene inyección SQL automáticamente
- ✅ **Mantenibilidad**: Código más legible y fácil de mantener
- ✅ **Eventos**: Hooks para ejecutar código antes/después de acciones
- ✅ **Casting Automático**: Convierte tipos de datos automáticamente

**Mapeo Tabla ↔ Modelo:**
```
Base de Datos          Laravel (Eloquent)
┌─────────────┐       ┌──────────────┐
│  posts      │ ←───→ │  Post.php    │
│  ├─ id      │       │  class Post  │
│  ├─ title   │       │  {            │
│  ├─ content │       │    $fillable │
│  └─ user_id │       │    methods   │
└─────────────┘       │  }           │
                      └──────────────┘

Registro (Row)         Instancia (Object)
┌─────────────┐       ┌──────────────┐
│ id: 1       │ ←───→ │ $post        │
│ title: "Hi" │       │ $post->title │
│ content:... │       │ $post->save()│
└─────────────┘       └──────────────┘
```

**Convenciones de Eloquent:**

| Elemento | Convención | Ejemplo |
|----------|------------|---------|
| **Nombre del Modelo** | Singular, PascalCase | `Post`, `User`, `OrderItem` |
| **Nombre de la Tabla** | Plural, snake_case | `posts`, `users`, `order_items` |
| **Llave Primaria** | `id` | Si usas otra, define `$primaryKey` |
| **Timestamps** | `created_at`, `updated_at` | Auto-gestionados si `$timestamps = true` |
| **Soft Deletes** | `deleted_at` | Si usas trait `SoftDeletes` |

**Ciclo de Vida de un Modelo:**
```php
// 1. INSTANCIA (objeto en memoria, no en BD)
$post = new Post();
$post->title = "Mi título";

// 2. PERSISTENCIA (guardar en BD)
$post->save();  // INSERT INTO posts...

// 3. RECUPERACIÓN (leer desde BD)
$post = Post::find(1);  // SELECT * FROM posts WHERE id = 1

// 4. MODIFICACIÓN (cambiar en memoria)
$post->title = "Nuevo título";

// 5. ACTUALIZACIÓN (guardar cambios en BD)
$post->save();  // UPDATE posts SET title = ...

// 6. ELIMINACIÓN (borrar de BD)
$post->delete();  // DELETE FROM posts WHERE id = 1
```

**Estados de un Modelo:**
- 🆕 **Nuevo (New)**: Instancia creada, no existe en BD (`exists = false`)
- 💾 **Persistido (Persisted)**: Existe en BD (`exists = true`)
- 🔄 **Modificado (Dirty)**: Tiene cambios no guardados (`isDirty() = true`)
- ✅ **Limpio (Clean)**: Sin cambios pendientes (`isDirty() = false`)
- 🗑️ **Eliminado (Deleted)**: Ya no existe en BD

**Eloquent vs SQL Raw:**
```php
// SQL Raw (antiguo, propenso a errores)
DB::select('SELECT * FROM posts WHERE user_id = ? AND status = ?', [1, 'published']);

// Eloquent (moderno, seguro, expresivo)
Post::where('user_id', 1)
    ->where('status', 'published')
    ->get();
```

### Estructura Básica de un Modelo

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Enums\PostType;
use App\Enums\PostStatus;

class Post extends Model
{
    use HasFactory;

    /**
     * Campos que se pueden asignar masivamente
     */
    protected $fillable = [
        'user_id',
        'name',
        'content',
        'type',
        'status',
        'moderator_comments',
        'scheduled_at',
        'published_at',
        'deadline',
        'timeout',
    ];

    /**
     * Campos ocultos en JSON
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Conversión automática de tipos
     */
    protected $casts = [
        'type' => PostType::class,
        'status' => PostStatus::class,
        'scheduled_at' => 'datetime',
        'published_at' => 'datetime',
        'deadline' => 'datetime',
        'timeout' => 'datetime',
        'is_active' => 'boolean',
        'metadata' => 'array',        // JSON a array
    ];

    /**
     * Valores por defecto
     */
    protected $attributes = [
        'status' => 'draft',
        'is_active' => true,
    ];
}
```

### Propiedades Importantes del Modelo

```php
// Nombre de la tabla (por defecto es plural del modelo)
protected $table = 'posts';

// Llave primaria (por defecto es 'id')
protected $primaryKey = 'id';

// Tipo de llave primaria
protected $keyType = 'int';

// ¿La llave es auto-incremental?
public $incrementing = true;

// ¿Usar timestamps automáticos?
public $timestamps = true;

// Nombre personalizado de timestamps
const CREATED_AT = 'creation_date';
const UPDATED_AT = 'updated_date';

// Conexión a base de datos (si tienes múltiples)
protected $connection = 'mysql';
```

### Operaciones CRUD con Eloquent

```php
// CREATE - Crear registro
$post = Post::create([
    'user_id' => 1,
    'name' => 'Mi primer post',
    'content' => 'Contenido del post',
    'type' => PostType::TEXT,
    'status' => PostStatus::DRAFT,
]);

// O usando save()
$post = new Post();
$post->user_id = 1;
$post->name = 'Mi primer post';
$post->save();

// READ - Leer registros
$post = Post::find(1);                          // Por ID
$post = Post::findOrFail(1);                    // O lanza excepción 404
$posts = Post::all();                           // Todos los registros
$posts = Post::where('status', 'draft')->get(); // Con condición
$post = Post::where('name', 'Test')->first();   // Primer resultado

// UPDATE - Actualizar
$post = Post::find(1);
$post->name = 'Nombre actualizado';
$post->save();

// O actualización masiva
Post::where('status', 'draft')
    ->update(['status' => 'published']);

// DELETE - Eliminar
$post = Post::find(1);
$post->delete();

// O eliminar por condición
Post::where('status', 'archived')->delete();
```

### Consultas Avanzadas

```php
// WHERE
Post::where('status', 'published')->get();
Post::where('views', '>', 100)->get();
Post::where('status', 'published')
    ->where('user_id', 1)
    ->get();

// OR WHERE
Post::where('status', 'published')
    ->orWhere('status', 'draft')
    ->get();

// WHERE IN
Post::whereIn('status', ['published', 'draft'])->get();

// WHERE BETWEEN
Post::whereBetween('created_at', ['2024-01-01', '2024-12-31'])->get();

// WHERE NULL / NOT NULL
Post::whereNull('deleted_at')->get();
Post::whereNotNull('published_at')->get();

// ORDER BY
Post::orderBy('created_at', 'desc')->get();
Post::orderBy('views', 'asc')->get();
Post::latest()->get();                          // Ordena por created_at desc
Post::oldest()->get();                          // Ordena por created_at asc

// LIMIT y OFFSET
Post::limit(10)->get();                         // LIMIT 10
Post::take(10)->get();                          // Igual que limit
Post::skip(10)->take(10)->get();               // OFFSET 10 LIMIT 10

// PAGINACIÓN
Post::paginate(15);                            // 15 por página
Post::simplePaginate(15);                      // Sin conteo total

// SELECCIÓN DE CAMPOS
Post::select('id', 'name', 'status')->get();

// COUNT, SUM, AVG, MIN, MAX
Post::count();
Post::where('status', 'published')->count();
Post::sum('views');
Post::avg('rating');
Post::min('price');
Post::max('price');

// EXISTEN REGISTROS
Post::where('status', 'draft')->exists();      // true/false
```

---

## 4. SEEDERS

### ¿Qué es un Seeder?

Un **seeder** es una clase PHP especializada que permite **poblar (llenar) la base de datos** con datos iniciales o de prueba de manera automática, reproducible y consistente. Es como un "script de instalación" para tus datos.

#### Conceptos Fundamentales:

**¿Por qué usar Seeders?**
- ✅ **Desarrollo Rápido**: No insertar datos manualmente cada vez
- ✅ **Testing Consistente**: Mismos datos de prueba en cada ejecución
- ✅ **Demos**: Datos realistas para presentaciones o clientes
- ✅ **Onboarding**: Nuevos desarrolladores tienen datos inmediatamente
- ✅ **CI/CD**: Poblar bases de datos de testing automáticamente
- ✅ **Datos Maestros**: Roles, permisos, configuraciones iniciales

**Tipos de Datos para Seeders:**

| Tipo | Ejemplo | Cuándo usar |
|------|---------|-------------|
| **Maestros** | Roles, permisos, países | Datos necesarios para que el sistema funcione |
| **Configuración** | Settings, opciones | Valores por defecto del sistema |
| **Demo** | Productos, posts de ejemplo | Mostrar funcionalidades |
| **Testing** | Usuarios de prueba | Datos para pruebas automatizadas |
| **Desarrollo** | 100 posts aleatorios | Probar performance con volumen |

**Anatomía de un Seeder:**
```php
database/seeders/
├── DatabaseSeeder.php      ← Orquestador principal
├── UserSeeder.php          ← Seeder específico
├── PostSeeder.php
└── data/                   ← Archivos de datos (opcional)
    ├── users.json
    └── posts.json
```

**Flujo de Ejecución:**
```
1. php artisan db:seed
   ↓
2. DatabaseSeeder::run()
   ↓
3. $this->call([UserSeeder::class, PostSeeder::class])
   ↓
4. UserSeeder::run() → Crea usuarios
   ↓
5. PostSeeder::run() → Crea posts (usa usuarios creados)
```

**Orden de Ejecución (CRÍTICO):**
```php
// ❌ MALO - PostSeeder falla porque no hay usuarios
$this->call([
    PostSeeder::class,   // Necesita users
    UserSeeder::class,   // Se ejecuta después
]);

// ✅ BUENO - Respeta dependencias
$this->call([
    UserSeeder::class,   // Primero: sin dependencias
    ChannelSeeder::class,
    MediaSeeder::class,
    PostSeeder::class,   // Último: depende de todos los anteriores
]);
```

**Idempotencia (concepto clave):**

Un seeder **idempotente** produce el mismo resultado sin importar cuántas veces se ejecute:

```php
// ❌ NO IDEMPOTENTE - Crea duplicados
Post::create(['title' => 'Mi Post']);  // Error si ejecutas 2 veces

// ✅ IDEMPOTENTE - No crea duplicados
Post::firstOrCreate(
    ['title' => 'Mi Post'],  // Busca por este criterio
    ['content' => '...']      // Crea solo si no existe
);
```

**Métodos de Inserción:**

| Método | Comportamiento | Idempotente | Uso |
|--------|----------------|-------------|-----|
| `create()` | Siempre crea nuevo | ❌ No | Factories, datos únicos |
| `firstOrCreate()` | Busca, crea solo si no existe | ✅ Sí | **Recomendado** para seeders |
| `updateOrCreate()` | Busca, actualiza o crea | ✅ Sí | Datos que pueden cambiar |
| `insert()` | Inserción masiva rápida | ❌ No | Gran volumen de datos |

**Estrategias de Población:**

1. **Arrays Hardcodeados** (datos fijos):
```php
$roles = [
    ['name' => 'admin'],
    ['name' => 'editor'],
];
foreach ($roles as $role) {
    Role::firstOrCreate($role);
}
```

2. **Archivos JSON** (datos estructurados):
```php
$json = file_get_contents(database_path('seeders/users.json'));
$users = json_decode($json, true);
foreach ($users as $user) {
    User::firstOrCreate(['email' => $user['email']], $user);
}
```

3. **Factories** (datos aleatorios):
```php
User::factory()->count(50)->create();  // 50 usuarios aleatorios
```

4. **APIs Externas** (datos reales):
```php
$response = Http::get('https://api.example.com/products');
foreach ($response->json() as $product) {
    Product::firstOrCreate(['sku' => $product['sku']], $product);
}
```

**Validación de Dependencias:**
```php
public function run(): void
{
    // Verificar que existan usuarios antes de crear posts
    if (User::count() === 0) {
        $this->command->warn('⚠️  No hay usuarios. Ejecuta UserSeeder primero.');
        return;  // Detener ejecución
    }
    
    // Continuar si todo está bien
    $user = User::first();
    Post::create(['user_id' => $user->id, ...]);
}
```

### Estructura Básica de un Seeder

```php
<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        // Lógica para crear datos
    }
}
```

### Método 1: create() - Básico

```php
public function run(): void
{
    Post::create([
        'user_id' => 1,
        'name' => 'Post de ejemplo',
        'content' => 'Contenido',
        'type' => 'text',
        'status' => 'draft',
    ]);
}
```

**Problema:** Si ejecutas dos veces, crea duplicados ❌

### Método 2: firstOrCreate() - Recomendado

```php
public function run(): void
{
    Post::firstOrCreate(
        ['name' => 'Post de ejemplo'],    // Busca por este campo
        [                                  // Campos adicionales si crea
            'user_id' => 1,
            'content' => 'Contenido',
            'type' => 'text',
            'status' => 'draft',
        ]
    );
}
```

**Ventaja:** No crea duplicados, es idempotente ✅

### Método 3: updateOrCreate() - Actualiza si existe

```php
public function run(): void
{
    Post::updateOrCreate(
        ['name' => 'Post de ejemplo'],    // Busca por este campo
        [                                  // Actualiza/Crea con estos datos
            'user_id' => 1,
            'content' => 'Contenido actualizado',
            'type' => 'text',
            'status' => 'published',
        ]
    );
}
```

### Ejemplo Completo: Seeder con Array de Datos

```php
<?php

namespace Database\Seeders;

use App\Models\Channel;
use App\Enums\ChannelType;
use Illuminate\Database\Seeder;

class ChannelSeeder extends Seeder
{
    public function run(): void
    {
        $channels = [
            [
                'name' => 'Departamento de Comunicación',
                'description' => 'Comunicación institucional',
                'type' => ChannelType::DEPARTMENT->value,
                'semantic_context' => 'Comunicación corporativa, eventos',
            ],
            [
                'name' => 'Instituto de Investigación',
                'description' => 'Investigación científica',
                'type' => ChannelType::INSTITUTE->value,
                'semantic_context' => 'Ciencia, investigación, papers',
            ],
        ];

        foreach ($channels as $channelData) {
            Channel::firstOrCreate(
                ['name' => $channelData['name']],
                $channelData
            );
        }
    }
}
```

### Seeder con Relaciones (Many-to-Many)

```php
<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use App\Models\Channel;
use App\Models\Media;
use App\Enums\PostType;
use App\Enums\PostStatus;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Validar que existan las dependencias
        if (User::count() === 0) {
            $this->command->warn('⚠️  No hay usuarios. Ejecuta UserSeeder primero.');
            return;
        }

        if (Channel::count() === 0) {
            $this->command->warn('⚠️  No hay canales. Ejecuta ChannelSeeder primero.');
            return;
        }

        if (Media::count() === 0) {
            $this->command->warn('⚠️  No hay medios. Ejecuta MediaSeeder primero.');
            return;
        }

        // 2. Obtener registros relacionados
        $user = User::first();
        $channels = Channel::take(2)->get()->pluck('id');
        $medias = Media::take(2)->get()->pluck('id');

        // 3. Crear el post
        $post = Post::firstOrCreate(
            ['name' => 'Post de Ejemplo'],
            [
                'user_id' => $user->id,
                'content' => 'Contenido del post',
                'type' => PostType::TEXT->value,
                'status' => PostStatus::DRAFT->value,
                'scheduled_at' => Carbon::now()->addDays(1),
            ]
        );

        // 4. Asociar relaciones N:M (solo si se creó ahora)
        if ($post->wasRecentlyCreated) {
            $post->channels()->sync($channels);  // Asociar canales
            $post->medias()->sync($medias);      // Asociar medios
        }
    }
}
```

### Seeder que Lee desde JSON

```php
<?php

namespace Database\Seeders;

use App\Models\Media;
use Illuminate\Database\Seeder;

class MediaSeeder extends Seeder
{
    public function run(): void
    {
        // Leer JSON desde archivo
        $json = file_get_contents(database_path('seeders/medias.json'));
        $medias = json_decode($json, true);

        foreach ($medias as $mediaData) {
            Media::firstOrCreate(
                ['name' => $mediaData['name']],
                $mediaData
            );
        }
    }
}
```

### DatabaseSeeder - Orquestador Principal

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Orden de ejecución IMPORTANTE
        // Primero las tablas sin dependencias
        $this->call([
            UserSeeder::class,
            ChannelSeeder::class,
            MediaSeeder::class,
            // Luego las que tienen dependencias
            PostSeeder::class,
            AttachmentSeeder::class,
        ]);
    }
}
```

---

## 5. ENUMS

### ¿Qué es un Enum?

Un **Enum** (enumeración) es un tipo de dato especial de PHP 8.1+ que define un **conjunto cerrado y finito de valores posibles**. Es como una lista predefinida de opciones válidas que no puede cambiar durante la ejecución.

#### Conceptos Fundamentales:

**¿Por qué usar Enums?**
- ✅ **Type Safety**: El IDE y PHP validan que uses valores correctos
- ✅ **Autocompletado**: Tu editor sugiere valores válidos
- ✅ **Sin Magic Strings**: `PostStatus::DRAFT` vs `"draft"` (propenso a typos)
- ✅ **Documentación Viva**: El código documenta qué valores son válidos
- ✅ **Refactoring Seguro**: Cambiar un valor actualiza todo automáticamente
- ✅ **Centralización**: Un solo lugar para definir valores posibles

**Problema sin Enums:**
```php
// ❌ MALO - Magic strings, propenso a errores
$post->status = 'draft';     // ✓ Funciona
$post->status = 'Draft';     // ✗ No funciona (case sensitive)
$post->status = 'darft';     // ✗ Typo, sin error hasta runtime
$post->status = 'publicado'; // ✗ Valor inválido, sin advertencia

// Sin forma de saber qué valores son válidos sin ver la BD
```

**Solución con Enums:**
```php
// ✅ BUENO - Type safe, autocompletado, sin errores
$post->status = PostStatus::DRAFT;        // ✓ IDE autocompleta
$post->status = PostStatus::PUBLISHED;    // ✓ Solo valores válidos
$post->status = PostStatus::DARFT;        // ✗ Error inmediato en IDE
$post->status = "draft";                  // ✗ Error de tipo
```

**Anatomía de un Enum:**
```php
<?php
namespace App\Enums;

enum PostStatus: string  // ← Tipo base (string, int)
{
    // Casos (valores posibles)
    case DRAFT = 'draft';              // ← Nombre = Valor
    case PUBLISHED = 'published';
    case ARCHIVED = 'archived';
    
    // Método estático para migraciones
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
        // Retorna: ['draft', 'published', 'archived']
    }
    
    // Método de instancia para UI
    public function label(): string
    {
        return match($this) {
            self::DRAFT => 'Borrador',
            self::PUBLISHED => 'Publicado',
            self::ARCHIVED => 'Archivado',
        };
    }
}
```

**Tipos de Enums:**

1. **Pure Enum** (sin valor):
```php
enum Color {
    case RED;
    case GREEN;
    case BLUE;
}
// Uso: Color::RED
```

2. **Backed Enum** (con valor string/int):
```php
enum Status: string {
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
}
// Uso: Status::ACTIVE->value  // 'active'
```

**Métodos Útiles de Enums:**

```php
// Obtener todos los casos
PostStatus::cases();  
// Retorna: [PostStatus::DRAFT, PostStatus::PUBLISHED, PostStatus::ARCHIVED]

// Obtener valores de backed enums
array_column(PostStatus::cases(), 'value');
// Retorna: ['draft', 'published', 'archived']

// Crear enum desde valor (backed enums)
PostStatus::from('draft');      // PostStatus::DRAFT
PostStatus::tryFrom('invalid'); // null (no lanza excepción)

// Comparar enums
$status === PostStatus::DRAFT;  // true/false
$status->value === 'draft';     // Comparar el valor
```

**Ventajas sobre Constantes:**

| Constantes | Enums |
|------------|-------|
| `const DRAFT = 'draft';` | `case DRAFT = 'draft';` |
| ❌ Solo valores, no tipos | ✅ Tipo propio |
| ❌ Sin validación | ✅ Type safety |
| ❌ Sin métodos | ✅ Puede tener métodos |
| ❌ Sin autocompletado | ✅ Autocompletado en IDE |
| ❌ Múltiples clases | ✅ Una clase por grupo |

### Estructura de un Enum

```php
<?php

namespace App\Enums;

enum PostStatus: string
{
    case DRAFT = 'draft';
    case APPROVED_BY_MODERATOR = 'approved_by_moderator';
    case SCHEDULED = 'scheduled';
    case ARCHIVED = 'archived';

    /**
     * Obtiene todos los valores como array (para migraciones)
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Etiquetas legibles para humanos (para vistas)
     */
    public function label(): string
    {
        return match($this) {
            self::DRAFT => 'Borrador',
            self::APPROVED_BY_MODERATOR => 'Aprobado',
            self::SCHEDULED => 'Programado',
            self::ARCHIVED => 'Archivado',
        };
    }
}
```

### Cómo Usar Enums en Migraciones (Detallado)

**1. Importar el Enum:**
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\PostStatus;  // ← Importar el enum
use App\Enums\PostType;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            
            // ✅ CORRECTO - Usar el método values()
            $table->enum('status', PostStatus::values());
            $table->enum('type', PostType::values());
            
            // ❌ INCORRECTO - Hardcodear valores
            // $table->enum('status', ['draft', 'published']); // Difícil de mantener
            
            $table->timestamps();
        });
    }
};
```

**¿Qué hace `PostStatus::values()`?**
```php
// Definición en el Enum
public static function values(): array
{
    return array_column(self::cases(), 'value');
}

// Cuando se ejecuta
PostStatus::values()  
// Retorna: ['draft', 'approved_by_moderator', 'scheduled', 'archived']

// Laravel lo usa para generar SQL
$table->enum('status', PostStatus::values());
// Genera: status ENUM('draft', 'approved_by_moderator', 'scheduled', 'archived')
```

**2. Ventajas de usar `values()` en migraciones:**
- ✅ **Single Source of Truth**: Un solo lugar define los valores
- ✅ **Auto-actualización**: Si agregas un caso al enum, la migración lo incluye
- ✅ **Consistencia**: Imposible que migración y modelo tengan valores diferentes
- ✅ **Refactoring Seguro**: Cambiar un valor actualiza todo

**3. Ejemplo con múltiples enums:**
```php
Schema::create('posts', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->enum('type', PostType::values());      // text, video, audio, image
    $table->enum('status', PostStatus::values());  // draft, published, archived
    $table->enum('priority', Priority::values());  // low, medium, high
    $table->timestamps();
});
```

### Cómo Usar Enums en Modelos (Detallado)

**1. Definir el cast en el modelo:**
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\PostStatus;
use App\Enums\PostType;

class Post extends Model
{
    protected $fillable = [
        'title',
        'content',
        'type',
        'status',
    ];

    /**
     * Casting automático de tipos
     * Laravel convierte automáticamente entre string (BD) y Enum (PHP)
     */
    protected $casts = [
        'type' => PostType::class,      // ← Convierte 'text' a PostType::TEXT
        'status' => PostStatus::class,  // ← Convierte 'draft' a PostStatus::DRAFT
        'published_at' => 'datetime',
    ];
}
```

**2. ¿Qué hace el cast automáticamente?**

```php
// Cuando RECUPERAS desde BD:
$post = Post::find(1);
// BD tiene: status = 'draft' (string)
// Laravel convierte a: $post->status = PostStatus::DRAFT (Enum)

echo $post->status;              // PostStatus::DRAFT (objeto)
echo $post->status->value;       // 'draft' (string)
echo $post->status->label();     // 'Borrador' (método custom)

// Cuando GUARDAS en BD:
$post->status = PostStatus::PUBLISHED;  // Enum
$post->save();
// Laravel convierte a: 'published' (string en BD)
```

**3. Beneficios del casting:**
```php
// ✅ Type safety en asignación
$post->status = PostStatus::DRAFT;        // ✓ Correcto
$post->status = 'draft';                  // ✗ Error de tipo
$post->status = PostStatus::INVALID;      // ✗ Error, no existe

// ✅ Métodos disponibles
echo $post->status->label();              // 'Borrador'
echo $post->status->icon();               // '📝'

// ✅ Comparación type-safe
if ($post->status === PostStatus::DRAFT) {
    // Es un borrador
}

// ❌ Sin casting (todo sería string)
if ($post->status === 'draft') {  // Propenso a typos
    // 'Draft', 'DRAFT', 'darft' fallarían silenciosamente
}
```

**4. Uso en queries con Enums:**
```php
// Buscar por enum
$drafts = Post::where('status', PostStatus::DRAFT->value)->get();

// O con whereEnum (si usas paquete adicional)
$drafts = Post::whereEnum('status', PostStatus::DRAFT)->get();

// Filtrar múltiples estados
$posts = Post::whereIn('status', [
    PostStatus::DRAFT->value,
    PostStatus::PUBLISHED->value,
])->get();
```

### Uso en Seeders (Detallado)

**1. Usar el valor del enum con `->value`:**
```php
<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Enums\PostStatus;
use App\Enums\PostType;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        Post::create([
            'title' => 'Mi primer post',
            'content' => 'Contenido',
            'type' => PostType::TEXT->value,        // ← ->value convierte a string
            'status' => PostStatus::DRAFT->value,   // ← 'draft'
        ]);
    }
}
```

**¿Por qué usar `->value`?**
```php
// El método create() espera un array con strings
Post::create([
    'status' => PostStatus::DRAFT,         // ❌ Error: espera string, recibe Enum
    'status' => PostStatus::DRAFT->value,  // ✅ Correcto: convierte a 'draft'
]);

// Una vez guardado y recuperado, automáticamente es Enum
$post = Post::find(1);
$post->status;  // PostStatus::DRAFT (Enum, no string)
```

**2. Ejemplo completo con múltiples estados:**
```php
public function run(): void
{
    $posts = [
        [
            'title' => 'Post en borrador',
            'type' => PostType::TEXT->value,
            'status' => PostStatus::DRAFT->value,
        ],
        [
            'title' => 'Post publicado',
            'type' => PostType::VIDEO->value,
            'status' => PostStatus::PUBLISHED->value,
        ],
        [
            'title' => 'Post archivado',
            'type' => PostType::IMAGE->value,
            'status' => PostStatus::ARCHIVED->value,
        ],
    ];

    foreach ($posts as $postData) {
        Post::firstOrCreate(
            ['title' => $postData['title']],
            $postData
        );
    }
}
```

**3. Validar que un valor de enum es válido:**
```php
public function run(): void
{
    // Validar que el valor existe en el enum
    $status = 'draft';
    
    // Opción 1: tryFrom (retorna null si inválido)
    if (PostStatus::tryFrom($status)) {
        Post::create(['status' => $status]);
    }
    
    // Opción 2: from (lanza excepción si inválido)
    try {
        $enumStatus = PostStatus::from($status);
        Post::create(['status' => $enumStatus->value]);
    } catch (\ValueError $e) {
        echo "Estado inválido: {$status}";
    }
}
```

### Ejemplo Completo: PostType Enum

```php
<?php

namespace App\Enums;

enum PostType: string
{
    case TEXT = 'text';
    case VIDEO = 'video';
    case AUDIO = 'audio';
    case IMAGE = 'image';
    case MULTIMEDIA = 'multimedia';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function label(): string
    {
        return match($this) {
            self::TEXT => 'Texto',
            self::VIDEO => 'Video',
            self::AUDIO => 'Audio',
            self::IMAGE => 'Imagen',
            self::MULTIMEDIA => 'Multimedia',
        };
    }

    /**
     * Método adicional: ícono para cada tipo
     */
    public function icon(): string
    {
        return match($this) {
            self::TEXT => '📝',
            self::VIDEO => '🎬',
            self::AUDIO => '🎵',
            self::IMAGE => '🖼️',
            self::MULTIMEDIA => '🎭',
        };
    }
}
```

---

## 6. RELACIONES ENTRE MODELOS

### Tipos de Relaciones en Eloquent

Laravel Eloquent soporta **7 tipos de relaciones** que modelan cómo se conectan las tablas en la base de datos:

1. **One to One (1:1)** - `hasOne()` / `belongsTo()`
2. **One to Many (1:N)** - `hasMany()` / `belongsTo()`  
3. **Many to Many (N:M)** - `belongsToMany()`
4. **Has One Through (1:1 a través de)**
5. **Has Many Through (1:N a través de)**
6. **One to One Polymorphic** (1:1 polimórfica)
7. **One to Many Polymorphic** (1:N polimórfica)

### Explicación Detallada de Cada Relación

#### 1. ONE TO ONE (1:1)

**Concepto:** Un registro de la tabla A se relaciona con **exactamente un** registro de la tabla B.

**Ejemplo Real:** Un usuario tiene un perfil (y un perfil pertenece a un usuario)

```
users                    profiles
┌────┬──────┐           ┌────┬─────────┬──────────┐
│ id │ name │           │ id │ user_id │ bio      │
├────┼──────┤           ├────┼─────────┼──────────┤
│ 1  │ Juan │───────────│ 1  │ 1       │ Bio...   │
│ 2  │ Ana  │───────────│ 2  │ 2       │ Bio...   │
└────┴──────┘           └────┴─────────┴──────────┘
```

**Implementación:**
```php
// Migración de profiles
Schema::create('profiles', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->unique()->constrained()->onDelete('cascade');
    $table->text('bio');
    $table->timestamps();
});

// Modelo User
class User extends Model
{
    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class);
    }
}

// Modelo Profile
class Profile extends Model
{
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

// Uso
$user = User::find(1);
$bio = $user->profile->bio;  // Acceder al perfil

$profile = Profile::find(1);
$name = $profile->user->name;  // Acceder al usuario
```

**Cuándo usar:** Cuando quieres dividir información de una entidad en tablas separadas (normalización).

---

#### 2. ONE TO MANY (1:N)

**Concepto:** Un registro de la tabla A se relaciona con **muchos** registros de la tabla B.

**Ejemplo Real:** Un usuario tiene muchos posts (pero cada post pertenece a un solo usuario)

```
users                    posts
┌────┬──────┐           ┌────┬─────────┬────────┐
│ id │ name │           │ id │ user_id │ title  │
├────┼──────┤           ├────┼─────────┼────────┤
│ 1  │ Juan │───────┬───│ 1  │ 1       │ Post A │
│ 2  │ Ana  │──┐    ├───│ 2  │ 1       │ Post B │
└────┴──────┘  │    └───│ 3  │ 1       │ Post C │
               └────────│ 4  │ 2       │ Post D │
                        └────┴─────────┴────────┘
```

**Implementación:**
```php
// Migración de posts
Schema::create('posts', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->string('title');
    $table->timestamps();
});

// Modelo User
class User extends Model
{
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }
}

// Modelo Post
class Post extends Model
{
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

// Uso
$user = User::find(1);
$posts = $user->posts;  // Collection de todos los posts del usuario

foreach ($user->posts as $post) {
    echo $post->title;
}

$post = Post::find(1);
$authorName = $post->user->name;  // Acceder al autor
```

**Cuándo usar:** Relación padre-hijo, maestro-detalle (usuarios-posts, categorías-productos, etc.)

---

#### 3. MANY TO MANY (N:M)

**Concepto:** Muchos registros de A se relacionan con muchos registros de B (y viceversa).

**Ejemplo Real:** Posts y Tags (un post tiene muchas tags, una tag está en muchos posts)

```
posts                post_tag (pivot)         tags
┌────┬────────┐     ┌─────────┬────────┐     ┌────┬───────┐
│ id │ title  │     │ post_id │ tag_id │     │ id │ name  │
├────┼────────┤     ├─────────┼────────┤     ├────┼───────┤
│ 1  │ Post A │──┬──│ 1       │ 1      │──┬──│ 1  │ PHP   │
│ 2  │ Post B │─┐│  │ 1       │ 2      │─┐│  │ 2  │ Laravel│
└────┴────────┘ ││  │ 2       │ 1      │ ││  │ 3  │ Vue   │
                │└──│ 2       │ 3      │ │└──└────┴───────┘
                └───│ 3       │ 2      │ │
                    └─────────┴────────┘ └──┐
                                            │
Post 1 tiene: PHP, Laravel                 │
Post 2 tiene: PHP, Vue                     │
PHP está en: Post 1, Post 2  ───────────────┘
```

**Implementación:**
```php
// Migración de posts
Schema::create('posts', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->timestamps();
});

// Migración de tags
Schema::create('tags', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->timestamps();
});

// Migración de tabla pivot
Schema::create('post_tag', function (Blueprint $table) {
    $table->id();
    $table->foreignId('post_id')->constrained()->onDelete('cascade');
    $table->foreignId('tag_id')->constrained()->onDelete('cascade');
    $table->timestamps();
    $table->unique(['post_id', 'tag_id']);  // Evitar duplicados
});

// Modelo Post
class Post extends Model
{
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }
}

// Modelo Tag
class Tag extends Model
{
    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class);
    }
}

// Uso
$post = Post::find(1);
$tags = $post->tags;  // Todas las tags del post

$tag = Tag::find(1);
$posts = $tag->posts;  // Todos los posts con esta tag
```

**Cuándo usar:** Relaciones de muchos a muchos (productos-categorías, usuarios-roles, posts-tags)

---

#### 4. HAS MANY THROUGH (1:N a través de)

**Concepto:** Acceder a registros distantes a través de una relación intermedia.

**Ejemplo Real:** Un país tiene muchos posts a través de sus usuarios

```
countries          users              posts
┌────┬──────┐     ┌────┬────────────┐  ┌────┬─────────┐
│ id │ name │     │ id │ country_id │  │ id │ user_id │
├────┼──────┤     ├────┼────────────┤  ├────┼─────────┤
│ 1  │ ARG  │──┬──│ 1  │ 1          │──│ 1  │ 1       │
│ 2  │ BRA  │─┐│  │ 2  │ 1          │──│ 2  │ 1       │
└────┴──────┘ ││  │ 3  │ 2          │──│ 3  │ 2       │
              │└──│ 4  │ 2          │──│ 4  │ 3       │
              └───└────┴────────────┘  └────┴─────────┘

Argentina tiene posts: 1, 2, 3 (a través de usuarios 1 y 2)
```

**Implementación:**
```php
// Modelo Country
class Country extends Model
{
    public function posts(): HasManyThrough
    {
        return $this->hasManyThrough(
            Post::class,      // Modelo final
            User::class,      // Modelo intermedio
            'country_id',     // FK en users
            'user_id',        // FK en posts
            'id',             // PK en countries
            'id'              // PK en users
        );
    }
}

// Uso
$country = Country::find(1);
$posts = $country->posts;  // Todos los posts de usuarios de ese país
```

---

#### 5. POLYMORPHIC RELATIONS (Polimórficas)

**Concepto:** Un modelo puede pertenecer a múltiples tipos de modelos usando una sola relación.

**Ejemplo Real:** Comentarios que pueden ser de posts o videos

```
posts              comments              videos
┌────┬────────┐   ┌────┬──────────────┬──────────────┬────────┐   ┌────┬────────┐
│ id │ title  │   │ id │commentable_id│commentable   │ text   │   │ id │ title  │
├────┼────────┤   │    │              │    _type     │        │   ├────┼────────┤
│ 1  │ Post A │◄──│ 1  │ 1            │ App\Post     │ Nice!  │   │ 1  │ Video A│
│ 2  │ Post B │   │ 2  │ 1            │ App\Video    │ Cool!  │◄──│ 2  │ Video B│
└────┴────────┘   │ 3  │ 2            │ App\Post     │ Great! │   └────┴────────┘
                  └────┴──────────────┴──────────────┴────────┘
```

**Implementación:**
```php
// Migración de comments
Schema::create('comments', function (Blueprint $table) {
    $table->id();
    $table->text('text');
    $table->morphs('commentable');  // Crea commentable_id y commentable_type
    $table->timestamps();
});

// Modelo Comment
class Comment extends Model
{
    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }
}

// Modelo Post
class Post extends Model
{
    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}

// Modelo Video
class Video extends Model
{
    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}

// Uso
$post = Post::find(1);
$comments = $post->comments;  // Comentarios del post

$comment = Comment::find(1);
$commentable = $comment->commentable;  // Puede ser Post o Video
```

### 1. One to Many (1:N)

**Ejemplo:** Un usuario tiene muchos posts

```php
// Modelo User
class User extends Model
{
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }
}

// Modelo Post
class Post extends Model
{
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

// Uso
$user = User::find(1);
$posts = $user->posts;  // Todos los posts del usuario

$post = Post::find(1);
$author = $post->user;  // El usuario autor del post
```

### 2. Many to Many (N:M)

**Ejemplo:** Posts y Channels (muchos a muchos)

```php
// Modelo Post
class Post extends Model
{
    public function channels(): BelongsToMany
    {
        return $this->belongsToMany(Channel::class, 'post_channels');
    }
}

// Modelo Channel
class Channel extends Model
{
    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'post_channels');
    }
}

// Uso
$post = Post::find(1);
$channels = $post->channels;  // Todos los canales del post

$channel = Channel::find(1);
$posts = $channel->posts;     // Todos los posts del canal

// Asociar (agregar relación)
$post->channels()->attach([1, 2, 3]);

// Disociar (eliminar relación)
$post->channels()->detach([1]);

// Sincronizar (reemplazar todas las relaciones)
$post->channels()->sync([1, 2, 3]);

// Sincronizar sin eliminar existentes
$post->channels()->syncWithoutDetaching([4, 5]);
```

### 3. Many to Many con Pivot Extra

**Ejemplo:** user_channels con campos adicionales

```php
// Migración
Schema::create('user_channels', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->foreignId('channel_id')->constrained()->onDelete('cascade');
    $table->boolean('is_approved')->default(false);
    $table->timestamp('approved_at')->nullable();
    $table->timestamps();
    $table->unique(['user_id', 'channel_id']);
});

// Modelo User
public function channels(): BelongsToMany
{
    return $this->belongsToMany(Channel::class, 'user_channels')
                ->withPivot('is_approved', 'approved_at')
                ->withTimestamps();
}

// Uso
$user = User::find(1);
foreach ($user->channels as $channel) {
    echo $channel->name;
    echo $channel->pivot->is_approved;  // Acceder a campo pivot
    echo $channel->pivot->approved_at;
}

// Asociar con datos pivot
$user->channels()->attach(1, [
    'is_approved' => true,
    'approved_at' => now(),
]);
```

### Consultas con Relaciones (Eager Loading)

```php
// N+1 Problem (MALO - hace muchas queries)
$posts = Post::all();
foreach ($posts as $post) {
    echo $post->user->name;  // Query por cada post
}

// Eager Loading (BUENO - una sola query)
$posts = Post::with('user')->get();
foreach ($posts as $post) {
    echo $post->user->name;  // Sin queries adicionales
}

// Múltiples relaciones
$posts = Post::with(['user', 'channels', 'medias'])->get();

// Relaciones anidadas
$posts = Post::with('user.profile')->get();

// Eager Loading condicional
$posts = Post::with(['channels' => function ($query) {
    $query->where('is_active', true);
}])->get();
```

---

## 7. CONTROLADORES Y RUTAS

### ¿Qué son los Controladores?

Un **controlador** es una clase PHP que **organiza la lógica de negocio** relacionada con un recurso específico (como posts, usuarios, productos). Actúa como intermediario entre las rutas y los modelos.

**Analogía del mundo real:**
```
Cliente (navegador)  →  Ruta (dirección)  →  Controlador (empleado)  →  Modelo (base de datos)
"Dame los posts"     →  GET /posts         →  PostController::index()  →  Post::all()
```

**¿Por qué usar controladores?**
- ✅ **Organización**: Separa lógica de rutas
- ✅ **Reutilización**: Métodos pueden usarse en múltiples lugares
- ✅ **Testing**: Más fácil probar lógica aislada
- ✅ **Mantenibilidad**: Código más limpio y estructurado
- ✅ **Escalabilidad**: Fácil agregar funcionalidad

**Estructura de un Controlador:**
```php
<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    // Cada método maneja una acción específica
    public function index()   { /* Listar todos */ }
    public function store()   { /* Crear nuevo */ }
    public function show($id) { /* Ver uno */ }
    public function update()  { /* Actualizar */ }
    public function destroy() { /* Eliminar */ }
}
```

**Patrón RESTful (importante para APIs):**

| Método HTTP | Ruta | Método del Controlador | Acción |
|-------------|------|------------------------|--------|
| GET | `/posts` | `index()` | Listar todos los posts |
| GET | `/posts/{id}` | `show($id)` | Ver un post específico |
| POST | `/posts` | `store(Request)` | Crear nuevo post |
| PUT/PATCH | `/posts/{id}` | `update(Request, $id)` | Actualizar post |
| DELETE | `/posts/{id}` | `destroy($id)` | Eliminar post |

---

### ¿Qué son las Rutas?

Una **ruta** es una regla que **mapea una URL y método HTTP a una acción específica** (función o método de controlador).

**Componentes de una ruta:**
```php
Route::get('/posts/{id}', [PostController::class, 'show']);
  │     │      │              │                      │
  │     │      │              │                      └─ Método del controlador
  │     │      │              └─ Clase del controlador
  │     │      └─ URI con parámetro dinámico
  │     └─ Método HTTP (GET, POST, PUT, DELETE)
  └─ Facade Route
```

**Archivos de rutas en Laravel:**

| Archivo | Propósito | Prefijo | Middleware |
|---------|-----------|---------|------------|
| `routes/web.php` | Páginas web (HTML) | Ninguno | `web` (sesiones, CSRF) |
| `routes/api.php` | APIs (JSON) | `/api` | `api` (stateless) |
| `routes/console.php` | Comandos CLI | - | - |
| `routes/channels.php` | Broadcasting | - | - |

**¿Por qué separar web y api?**
- **Web**: Necesita sesiones, cookies, protección CSRF
- **API**: Stateless, autenticación por tokens, respuestas JSON

---

### Estructura Básica de Rutas

```php
// routes/web.php o routes/api.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;

// Ruta GET simple
Route::get('/', function () {
    return view('welcome');
});

// Ruta con parámetro
Route::get('/posts/{id}', function ($id) {
    return "Post ID: {$id}";
});

// Ruta a controlador
Route::get('/posts', [PostController::class, 'index']);
Route::post('/posts', [PostController::class, 'store']);
Route::get('/posts/{id}', [PostController::class, 'show']);
Route::put('/posts/{id}', [PostController::class, 'update']);
Route::delete('/posts/{id}', [PostController::class, 'destroy']);
```

### Resource Routes (RESTful)

```php
// Crea automáticamente todas las rutas REST
Route::resource('posts', PostController::class);

// Genera:
// GET     /posts           -> index()
// GET     /posts/create    -> create()
// POST    /posts           -> store()
// GET     /posts/{id}      -> show()
// GET     /posts/{id}/edit -> edit()
// PUT     /posts/{id}      -> update()
// DELETE  /posts/{id}      -> destroy()

// Solo para API (sin create ni edit)
Route::apiResource('posts', PostController::class);
```

### Controlador Básico

```php
<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    // GET /posts - Listar todos
    public function index()
    {
        $posts = Post::with('user')->paginate(15);
        return response()->json($posts);
    }

    // POST /posts - Crear nuevo
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:text,video,audio,image',
            'status' => 'required|in:draft,published',
        ]);

        $post = Post::create($validated);
        return response()->json($post, 201);
    }

    // GET /posts/{id} - Ver uno
    public function show($id)
    {
        $post = Post::with(['user', 'channels'])->findOrFail($id);
        return response()->json($post);
    }

    // PUT /posts/{id} - Actualizar
    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'content' => 'sometimes|string',
        ]);

        $post->update($validated);
        return response()->json($post);
    }

    // DELETE /posts/{id} - Eliminar
    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();
        return response()->json(null, 204);
    }
}
```

### Rutas con Prefijos y Grupos

```php
// Prefijo /api
Route::prefix('api')->group(function () {
    Route::get('/posts', [PostController::class, 'index']);
    Route::get('/users', [UserController::class, 'index']);
});

// Middleware de autenticación
Route::middleware(['auth:sanctum'])->group(function () {
    Route::apiResource('posts', PostController::class);
});

// Combinar prefijo + middleware
Route::prefix('api')
     ->middleware(['auth:sanctum'])
     ->group(function () {
         Route::apiResource('posts', PostController::class);
     });
```

---

### VALIDACIÓN DE REQUESTS EN CONTROLADORES (DETALLADO)

La **validación** asegura que los datos recibidos cumplan con reglas específicas antes de procesarlos.

#### ¿Por qué Validar?

- ✅ **Seguridad**: Prevenir inyección SQL, XSS, y otros ataques
- ✅ **Integridad**: Asegurar datos consistentes en BD
- ✅ **UX**: Mostrar errores claros al usuario
- ✅ **Mantenibilidad**: Reglas centralizadas y documentadas
- ✅ **Prevención**: Evitar errores antes de guardar en BD

#### Método 1: Validación Inline (en el controlador)

**Estructura básica:**
```php
public function store(Request $request)
{
    // 1. Validar datos
    $validated = $request->validate([
        'campo' => 'regla1|regla2|regla3',
    ]);
    
    // 2. Si pasa validación, continuar
    $post = Post::create($validated);
    
    return response()->json($post, 201);
}
```

**Ejemplo completo:**
```php
<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function store(Request $request)
    {
        // Validación inline
        $validated = $request->validate([
            // Campo requerido, string, máximo 255 caracteres
            'title' => 'required|string|max:255',
            
            // Campo requerido, string, mínimo 10 caracteres
            'content' => 'required|string|min:10',
            
            // Campo opcional, debe ser una URL válida
            'image_url' => 'nullable|url',
            
            // Campo requerido, debe existir en tabla users
            'user_id' => 'required|exists:users,id',
            
            // Campo requerido, uno de los valores especificados
            'status' => 'required|in:draft,published,archived',
            
            // Campo opcional, debe ser fecha válida
            'published_at' => 'nullable|date',
        ]);

        $post = Post::create($validated);
        
        return response()->json([
            'message' => 'Post creado exitosamente',
            'data' => $post
        ], 201);
    }
}
```

**Si la validación falla:**
```php
// Laravel automáticamente:
// 1. Detiene la ejecución
// 2. Retorna respuesta HTTP 422 (Unprocessable Entity)
// 3. Devuelve JSON con errores:
{
    "message": "The title field is required. (and 2 more errors)",
    "errors": {
        "title": ["The title field is required."],
        "content": ["The content field is required."],
        "status": ["The selected status is invalid."]
    }
}
```

#### Reglas de Validación Más Usadas

| Regla | Descripción | Ejemplo |
|-------|-------------|---------|
| `required` | Campo obligatorio | `'email' => 'required'` |
| `nullable` | Campo opcional (puede ser null) | `'phone' => 'nullable'` |
| `string` | Debe ser texto | `'name' => 'string'` |
| `numeric` | Debe ser número | `'age' => 'numeric'` |
| `integer` | Debe ser entero | `'quantity' => 'integer'` |
| `email` | Debe ser email válido | `'email' => 'email'` |
| `url` | Debe ser URL válida | `'website' => 'url'` |
| `date` | Debe ser fecha válida | `'birth_date' => 'date'` |
| `boolean` | Debe ser booleano | `'is_active' => 'boolean'` |
| `array` | Debe ser array | `'tags' => 'array'` |
| `json` | Debe ser JSON válido | `'metadata' => 'json'` |
| `min:n` | Valor mínimo (string, número, array) | `'password' => 'min:8'` |
| `max:n` | Valor máximo | `'username' => 'max:20'` |
| `between:min,max` | Entre dos valores | `'age' => 'between:18,65'` |
| `size:n` | Tamaño exacto | `'code' => 'size:6'` |
| `in:foo,bar` | Uno de los valores | `'role' => 'in:admin,user'` |
| `unique:tabla,columna` | Debe ser único en BD | `'email' => 'unique:users,email'` |
| `exists:tabla,columna` | Debe existir en BD | `'user_id' => 'exists:users,id'` |
| `confirmed` | Campo de confirmación | `'password' => 'confirmed'` |
| `regex:/patrón/` | Expresión regular | `'phone' => 'regex:/^[0-9]{10}$/'` |

#### Validación con Array de Reglas

```php
$request->validate([
    'title' => ['required', 'string', 'max:255'],
    'email' => ['required', 'email', 'unique:users,email'],
    'password' => ['required', 'min:8', 'confirmed'],
]);
```

#### Validación de Arrays y Campos Anidados

```php
$request->validate([
    // Array de tags (mínimo 1, máximo 5)
    'tags' => 'required|array|min:1|max:5',
    
    // Cada tag debe ser string de máximo 50 caracteres
    'tags.*' => 'required|string|max:50',
    
    // Validar objeto anidado
    'author.name' => 'required|string',
    'author.email' => 'required|email',
    
    // Array de objetos
    'products' => 'required|array',
    'products.*.name' => 'required|string',
    'products.*.price' => 'required|numeric|min:0',
]);
```

#### Validación Condicional

```php
$request->validate([
    'payment_method' => 'required|in:credit_card,paypal',
    
    // Solo requerido si payment_method es credit_card
    'card_number' => 'required_if:payment_method,credit_card',
    'cvv' => 'required_if:payment_method,credit_card|size:3',
    
    // Solo requerido si otro campo tiene valor
    'other_reason' => 'required_if:reason,other',
]);
```

#### Validación con Unique (ignorando registro actual)

```php
// Al CREAR (email debe ser único)
$request->validate([
    'email' => 'required|email|unique:users,email',
]);

// Al ACTUALIZAR (ignorar el email del usuario actual)
$request->validate([
    'email' => 'required|email|unique:users,email,' . $user->id,
]);

// Forma alternativa con Rule
use Illuminate\Validation\Rule;

$request->validate([
    'email' => [
        'required',
        'email',
        Rule::unique('users')->ignore($user->id),
    ],
]);
```

#### Mensajes de Error Personalizados

```php
$request->validate([
    'title' => 'required|max:255',
    'content' => 'required',
], [
    // Mensajes personalizados
    'title.required' => 'El título es obligatorio',
    'title.max' => 'El título no puede tener más de 255 caracteres',
    'content.required' => 'Debes escribir algo de contenido',
]);
```

#### Método 2: Form Request (Validación en Clase Separada)

**Crear Form Request:**
```bash
php artisan make:request StorePostRequest
```

**Archivo `app/Http/Requests/StorePostRequest.php`:**
```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    /**
     * Determinar si el usuario está autorizado
     */
    public function authorize(): bool
    {
        return true;  // o lógica de autorización
    }

    /**
     * Reglas de validación
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'content' => 'required|string|min:10',
            'status' => 'required|in:draft,published',
            'user_id' => 'required|exists:users,id',
        ];
    }

    /**
     * Mensajes personalizados
     */
    public function messages(): array
    {
        return [
            'title.required' => 'El título es obligatorio',
            'content.min' => 'El contenido debe tener al menos 10 caracteres',
        ];
    }

    /**
     * Nombres personalizados de atributos
     */
    public function attributes(): array
    {
        return [
            'title' => 'título del post',
            'content' => 'contenido',
        ];
    }
}
```

**Uso en el Controlador:**
```php
use App\Http\Requests\StorePostRequest;

class PostController extends Controller
{
    // Laravel inyecta y valida automáticamente
    public function store(StorePostRequest $request)
    {
        // Si llega aquí, ya pasó validación
        $validated = $request->validated();
        
        $post = Post::create($validated);
        
        return response()->json($post, 201);
    }
}
```

**Ventajas de Form Request:**
- ✅ Código más limpio en el controlador
- ✅ Reutilizable en múltiples lugares
- ✅ Lógica de autorización incluida
- ✅ Más fácil de testear
- ✅ Mejor organización

#### Validación con Enums

```php
use App\Enums\PostStatus;

$request->validate([
    // Validar que el valor esté en el enum
    'status' => ['required', 'in:' . implode(',', PostStatus::values())],
    
    // O usando Rule::in()
    'status' => ['required', Rule::in(PostStatus::values())],
]);
```

#### Manejo Manual de Errores de Validación

```php
use Illuminate\Support\Facades\Validator;

public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'title' => 'required|max:255',
        'content' => 'required',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'message' => 'Errores de validación',
            'errors' => $validator->errors()
        ], 422);
    }

    $validated = $validator->validated();
    $post = Post::create($validated);
    
    return response()->json($post, 201);
}
```

#### Validación de Archivos

```php
$request->validate([
    // Archivo requerido, imagen, máximo 2MB
    'image' => 'required|image|mimes:jpg,png,jpeg,gif|max:2048',
    
    // PDF opcional, máximo 5MB
    'document' => 'nullable|file|mimes:pdf|max:5120',
    
    // Dimensiones de imagen
    'avatar' => 'required|image|dimensions:min_width=100,min_height=100,max_width=500,max_height=500',
]);
```

#### Ejemplo Completo de CRUD con Validación

```php
<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PostController extends Controller
{
    // Listar (sin validación)
    public function index()
    {
        $posts = Post::with('user')->paginate(15);
        return response()->json($posts);
    }

    // Crear (con validación)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255|unique:posts,title',
            'content' => 'required|string|min:10',
            'status' => 'required|in:draft,published,archived',
            'user_id' => 'required|exists:users,id',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50',
        ]);

        $post = Post::create($validated);
        
        if (isset($validated['tags'])) {
            $post->tags()->attach($validated['tags']);
        }

        return response()->json($post, 201);
    }

    // Ver uno (sin validación, pero verifica existencia)
    public function show($id)
    {
        $post = Post::with(['user', 'tags'])->findOrFail($id);
        return response()->json($post);
    }

    // Actualizar (con validación)
    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        $validated = $request->validate([
            'title' => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('posts')->ignore($post->id),
            ],
            'content' => 'sometimes|string|min:10',
            'status' => 'sometimes|in:draft,published,archived',
        ]);

        $post->update($validated);

        return response()->json($post);
    }

    // Eliminar (sin validación)
    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();

        return response()->json(null, 204);
    }
}
```

---

## 8. SCRIPTS DE AUTOMATIZACIÓN DEL PROYECTO

### Script: migrate.sh

**Ubicación:** `/migrate.sh` (raíz del proyecto)

#### ¿Qué hace este script?

Es un script **bash automatizado** que ejecuta el ciclo completo de **reseteo y población de la base de datos**. Es extremadamente útil durante el desarrollo cuando necesitas empezar desde cero con datos frescos.

**Código del script:**
```bash
#!/bin/bash
# Script para eliminar, migrar y seedear la base de datos en Laravel

DB_PATH="database/database.sqlite"

if [ -f "$DB_PATH" ]; then
  rm "$DB_PATH"
fi

php artisan migrate --force --seed

status=$?
if [ $status -ne 0 ]; then
  echo "Error durante la migración o el seed. Código de salida: $status"
  exit $status
fi

echo "Migración y seed completados con éxito."
```

#### Explicación Paso a Paso:

**1. Shebang (`#!/bin/bash`):**
```bash
#!/bin/bash
```
- Define que el script se ejecuta con bash
- Debe ser la primera línea de cualquier script bash

**2. Definir la ruta de la base de datos:**
```bash
DB_PATH="database/database.sqlite"
```
- Variable que almacena la ruta del archivo SQLite
- Facilita cambiar la ruta si es necesario

**3. Eliminar la base de datos existente:**
```bash
if [ -f "$DB_PATH" ]; then
  rm "$DB_PATH"
fi
```
- `[ -f "$DB_PATH" ]`: Verifica si el archivo existe
- `rm "$DB_PATH"`: Elimina el archivo si existe
- **Efecto**: Elimina TODA la base de datos y sus datos

**4. Ejecutar migraciones y seeders:**
```bash
php artisan migrate --force --seed
```
- `migrate`: Ejecuta todas las migraciones pendientes
- `--force`: Ejecuta sin pedir confirmación (útil en producción)
- `--seed`: Ejecuta los seeders automáticamente después de migrar

**5. Capturar código de salida:**
```bash
status=$?
if [ $status -ne 0 ]; then
  echo "Error durante la migración o el seed. Código de salida: $status"
  exit $status
fi
```
- `$?`: Variable especial que contiene el código de salida del último comando
- Si `status` ≠ 0 → Hubo un error
- `exit $status`: Termina el script con el mismo código de error

**6. Mensaje de éxito:**
```bash
echo "Migración y seed completados con éxito."
```

#### ¿Cuándo usar `migrate.sh`?

| Situación | ¿Usar este script? | Razón |
|-----------|-------------------|-------|
| Empezar proyecto nuevo | ✅ SÍ | Crea BD desde cero |
| Cambios en migraciones | ✅ SÍ | Recrea estructura |
| Necesitas datos frescos | ✅ SÍ | Seeders desde cero |
| Producción con datos reales | ❌ NO | Borraría datos reales |
| Solo agregar nuevas migraciones | ❌ NO | Usa `php artisan migrate` |

#### Cómo ejecutarlo:

```bash
# Dar permisos de ejecución (solo primera vez)
chmod +x migrate.sh

# Ejecutar el script
./migrate.sh

# O con bash explícitamente
bash migrate.sh
```

#### Equivalente sin el script:

```bash
rm database/database.sqlite
php artisan migrate --force --seed
```

**Ventaja del script:** Todo en un solo comando, manejo de errores incluido.

---

### Script: mainsync.sh

**Ubicación:** `/mainsync.sh` (raíz del proyecto)

#### ¿Qué hace este script?

Es un script **avanzado de sincronización Git** que copia archivos desde el branch `main` hacia tu branch actual, respetando reglas específicas y protegiendo archivos importantes.

**Propósito:** Mantener múltiples branches sincronizados con las actualizaciones de `main` sin perder cambios específicos de cada branch.

#### Reglas de Sincronización:

| Tipo de Archivo | Acción | Ejemplo |
|-----------------|--------|---------|
| **Modificado en main** | Se sobreescribe automáticamente | Tutorial actualizado |
| **Nuevo en main** | Se agrega al branch | Nuevo seeder |
| **Único del branch** | Se mantiene intacto | Tu código específico |
| **En lista de ignorados** | Se omite completamente | User.php, .env |

#### Archivos Protegidos (No se sincronizan):

```bash
IGNORE_FILES=(
    "app/Models/User.php"              # Modelo de usuario personalizado
    "database/factories/UserFactory.php"
    "database/seeders/DatabaseSeeder.php"
    "routes/api.php"                   # Rutas API específicas del branch
    "routes/web.php"                   # Rutas web específicas
    "config/app.php"                   # Configuración personalizada
    "config/database.php"
    ".env"                             # Variables de entorno
)
```

**¿Por qué protegerlos?** Estos archivos suelen tener configuraciones o código específico de cada branch que no debe ser sobreescrito.

#### Uso del Script:

**1. Sincronizar desde main al branch actual:**
```bash
./mainsync.sh
```

**2. Sincronizar a un branch específico:**
```bash
./mainsync.sh develop
./mainsync.sh feature/nueva-funcionalidad
```

**3. Ver ayuda:**
```bash
./mainsync.sh --help
```

#### Flujo de Ejecución:

```
1. Verificar que estás en un repo Git
   ↓
2. Verificar que el branch 'main' existe
   ↓
3. Cambiar al branch destino (si no estás en él)
   ↓
4. Hacer git fetch de main (obtener últimos cambios)
   ↓
5. Analizar todos los archivos en main
   ↓
6. Categorizar cada archivo:
   ├─ ¿Está en la lista de ignorados? → IGNORAR
   ├─ ¿Existe en tu branch? 
   │  ├─ ¿Es diferente? → MODIFICADO (sobreescribir)
   │  └─ ¿Es igual? → SIN CAMBIOS
   └─ ¿No existe en tu branch? → NUEVO (agregar)
   ↓
7. Mostrar resumen de cambios
   ↓
8. Ejecutar sincronización automática:
   ├─ Sobreescribir archivos modificados
   └─ Agregar archivos nuevos
   ↓
9. Preparar cambios para commit (git add)
   ↓
10. Mostrar instrucciones para hacer commit manual
```

#### Ejemplo de Output:

```bash
$ ./mainsync.sh

=== Sincronización desde Main ===
Branch origen: main
Branch destino: difexa

Obteniendo últimos cambios de main...
Analizando archivos en main...
Categorizando archivos...
  Modificado: README.md
  Nuevo: docs/NEW_GUIDE.md
  Ignorado: app/Models/User.php

=== RESUMEN DE SINCRONIZACIÓN ===
Archivos que se sobreescribirán (1):
  ▶ README.md

Archivos nuevos que se agregarán (1):
  ➕ docs/NEW_GUIDE.md

Archivos ignorados (1):
  🚫 app/Models/User.php

Iniciando sincronización automática...
Sobreescribiendo archivos modificados...
  Sobreescribiendo: README.md
Agregando archivos nuevos...
  Agregando: docs/NEW_GUIDE.md

✓ Sincronización completada exitosamente

Los cambios están listos para commit manual.
Para crear el commit, ejecuta:
  git commit -m "Sync from main: 2 files updated"

🎉 ¡Sincronización desde main completada!
```

#### Comandos Git Equivalentes (sin el script):

```bash
# Obtener cambios de main
git fetch origin main

# Por CADA archivo que quieres sincronizar:
git checkout origin/main -- archivo1.php
git add archivo1.php

git checkout origin/main -- archivo2.php
git add archivo2.php

# ... (tedioso para muchos archivos)

# Hacer commit
git commit -m "Sync from main"
```

**Ventaja del script:** 
- ✅ Automatiza el proceso para cientos de archivos
- ✅ Categoriza y muestra resumen claro
- ✅ Protege archivos críticos automáticamente
- ✅ Manejo de errores robusto
- ✅ Colores y formato legible

#### Características Avanzadas del Script:

**1. Verificación de prerrequisitos:**
```bash
# Verifica que estás en un repo Git
if ! git rev-parse --git-dir > /dev/null 2>&1; then
    echo "Error: No estás en un repositorio Git"
    exit 1
fi
```

**2. Manejo de errores:**
```bash
ERROR_COUNT=0

# Si falla un archivo, cuenta el error pero continúa
if [[ $? -ne 0 ]]; then
    ((ERROR_COUNT++))
fi

# Al final reporta cuántos errores hubo
if [[ $ERROR_COUNT -gt 0 ]]; then
    echo "Se encontraron $ERROR_COUNT errores"
    exit 1
fi
```

**3. Colores para mejor legibilidad:**
```bash
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
CYAN='\033[0;36m'
NC='\033[0m' # No Color

echo -e "${GREEN}✓ Sincronización completada${NC}"
```

**4. Creación automática de directorios:**
```bash
file_dir=$(dirname "$file")
if [[ "$file_dir" != "." ]]; then
    mkdir -p "$file_dir"  # Crea directorios necesarios
fi
```

#### ¿Cuándo usar `mainsync.sh`?

| Situación | ¿Usar este script? | Razón |
|-----------|-------------------|-------|
| Main tiene actualizaciones de tutoriales | ✅ SÍ | Sincronizar documentación |
| Main tiene nuevos seeders/migraciones | ✅ SÍ | Traer nuevas funcionalidades base |
| Trabajas en branch de feature largo plazo | ✅ SÍ | Mantener sincronizado con main |
| Tu branch tiene cambios conflictivos | ⚠️ CUIDADO | Revisar qué se va a sobreescribir |
| Solo quieres un archivo específico | ❌ NO | Usa `git checkout main -- archivo` |

#### Mejores Prácticas:

**1. Antes de ejecutar:**
```bash
# Hacer commit de tus cambios actuales
git add .
git commit -m "Mi trabajo antes de sync"

# Ejecutar sync
./mainsync.sh

# Revisar qué cambió
git diff HEAD
```

**2. Después de ejecutar:**
```bash
# Revisar los cambios staged
git status

# Si algo no te gusta, puedes revertir
git restore --staged archivo.php
git restore archivo.php

# O hacer commit si todo está bien
git commit -m "Sync from main: updated docs and seeders"
```

**3. Si hay conflictos:**
```bash
# El script NO hace merge, solo copia archivos
# Si necesitas resolver conflictos manualmente:
git merge main  # Después del sync, si quieres
```

---

### Comparación de Scripts

| Aspecto | migrate.sh | mainsync.sh |
|---------|-----------|-------------|
| **Propósito** | Resetear base de datos | Sincronizar código entre branches |
| **Destructivo** | ✅ Sí (borra BD) | ⚠️ Parcial (sobreescribe archivos) |
| **Reversible** | ❌ No (datos perdidos) | ✅ Sí (con git) |
| **Frecuencia de uso** | Varias veces al día | Cada vez que main se actualiza |
| **Riesgo** | Bajo (solo dev) | Medio (puede sobreescribir código) |
| **Alternativas** | `php artisan migrate:fresh --seed` | `git merge main` |

---

## 9. STORAGE Y ARCHIVOS

### Configuración de Discos

```php
// config/filesystems.php

'disks' => [
    'public' => [
        'driver' => 'local',
        'root' => storage_path('app/public'),
        'url' => env('APP_URL').'/storage',
        'visibility' => 'public',
    ],
],
```

### Crear Symlink

```bash
php artisan storage:link
# Crea: public/storage -> storage/app/public
```

### Subir Archivos

```php
use Illuminate\Support\Facades\Storage;

// En el controlador
public function upload(Request $request)
{
    $request->validate([
        'file' => 'required|file|mimes:jpg,png,pdf|max:2048',
    ]);

    // Guardar archivo
    $path = $request->file('file')->store('uploads', 'public');
    
    // O con nombre personalizado
    $fileName = time() . '_' . $request->file('file')->getClientOriginalName();
    $path = $request->file('file')->storeAs('uploads', $fileName, 'public');

    // Obtener URL pública
    $url = Storage::url($path);

    return response()->json([
        'path' => $path,
        'url' => $url,
    ]);
}
```

### Operaciones con Storage

```php
// Verificar si existe
Storage::exists('file.jpg');

// Leer contenido
$contents = Storage::get('file.txt');

// Guardar contenido
Storage::put('file.txt', 'Contenido');

// Eliminar archivo
Storage::delete('file.jpg');
Storage::delete(['file1.jpg', 'file2.jpg']);

// Copiar archivo
Storage::copy('old.jpg', 'new.jpg');

// Mover archivo
Storage::move('old.jpg', 'new.jpg');

// Listar archivos de un directorio
$files = Storage::files('uploads');
$allFiles = Storage::allFiles('uploads');

// Tamaño del archivo (bytes)
$size = Storage::size('file.jpg');

// Última modificación (timestamp)
$time = Storage::lastModified('file.jpg');
```

### Modelo de Attachment

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Attachment extends Model
{
    protected $fillable = [
        'name',
        'file_name',
        'file_path',
        'mime_type',
        'size',
        'disk',
        'user_id',
    ];

    // Obtener URL pública
    public function getUrlAttribute()
    {
        return Storage::disk($this->disk)->url($this->file_path);
    }

    // Eliminar archivo físico al eliminar registro
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($attachment) {
            Storage::disk($attachment->disk)->delete($attachment->file_path);
        });
    }
}
```

---

## 10. PREGUNTAS FRECUENTES DE PARCIAL

### Pregunta 1: ¿Cómo crear un modelo con migración?

**Respuesta:**
```bash
php artisan make:model Post -m
```
Esto crea:
- `app/Models/Post.php`
- `database/migrations/YYYY_MM_DD_HHMMSS_create_posts_table.php`

---

### Pregunta 2: Escribe la migración para una tabla "products" con los siguientes campos: id, name, price, stock, is_active

**Respuesta:**
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
            $table->string('name');
            $table->decimal('price', 8, 2);
            $table->integer('stock');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
```

---

### Pregunta 3: ¿Cómo definir una relación Many-to-Many entre Post y Category?

**Respuesta:**
```php
// Migración de tabla pivot
Schema::create('category_post', function (Blueprint $table) {
    $table->id();
    $table->foreignId('category_id')->constrained()->onDelete('cascade');
    $table->foreignId('post_id')->constrained()->onDelete('cascade');
    $table->timestamps();
    $table->unique(['category_id', 'post_id']);
});

// Modelo Post
class Post extends Model
{
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }
}

// Modelo Category
class Category extends Model
{
    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class);
    }
}
```

---

### Pregunta 4: Escribe un seeder para crear 3 categorías sin duplicados

**Respuesta:**
```php
<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Tecnología', 'slug' => 'tecnologia'],
            ['name' => 'Deportes', 'slug' => 'deportes'],
            ['name' => 'Ciencia', 'slug' => 'ciencia'],
        ];

        foreach ($categories as $categoryData) {
            Category::firstOrCreate(
                ['slug' => $categoryData['slug']],
                $categoryData
            );
        }
    }
}
```

---

### Pregunta 5: ¿Cómo obtener todos los posts con sus usuarios y canales relacionados?

**Respuesta:**
```php
$posts = Post::with(['user', 'channels'])->get();

// O con paginación
$posts = Post::with(['user', 'channels'])->paginate(15);
```

---

### Pregunta 6: Escribe el código para asociar un post con los canales con IDs 1, 2, 3

**Respuesta:**
```php
$post = Post::find(1);
$post->channels()->sync([1, 2, 3]);

// O sin eliminar relaciones existentes
$post->channels()->syncWithoutDetaching([1, 2, 3]);

// O solo agregar
$post->channels()->attach([1, 2, 3]);
```

---

### Pregunta 7: ¿Qué hace el método `fillable` en un modelo?

**Respuesta:**
Define qué campos pueden ser asignados masivamente mediante `create()` o `update()`:
```php
protected $fillable = ['name', 'email', 'password'];

// Ahora puedes hacer:
User::create([
    'name' => 'Juan',
    'email' => 'juan@example.com',
    'password' => 'secret',
]);
```

---

### Pregunta 8: ¿Cómo crear un Enum para estados de pedido (pending, processing, completed, cancelled)?

**Respuesta:**
```php
<?php

namespace App\Enums;

enum OrderStatus: string
{
    case PENDING = 'pending';
    case PROCESSING = 'processing';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function label(): string
    {
        return match($this) {
            self::PENDING => 'Pendiente',
            self::PROCESSING => 'En Proceso',
            self::COMPLETED => 'Completado',
            self::CANCELLED => 'Cancelado',
        };
    }
}
```

---

### Pregunta 9: ¿Cómo validar que un campo email sea único en la tabla users?

**Respuesta:**
```php
$request->validate([
    'email' => 'required|email|unique:users,email',
]);

// O al actualizar (ignorando el email del usuario actual)
$request->validate([
    'email' => 'required|email|unique:users,email,' . $user->id,
]);
```

---

### Pregunta 10: Escribe una ruta API RESTful para gestionar productos

**Respuesta:**
```php
// routes/api.php
use App\Http\Controllers\ProductController;

Route::apiResource('products', ProductController::class);

// Genera:
// GET    /api/products        -> index()
// POST   /api/products        -> store()
// GET    /api/products/{id}   -> show()
// PUT    /api/products/{id}   -> update()
// DELETE /api/products/{id}   -> destroy()
```

---

### Pregunta 11: ¿Cuál es la diferencia entre `delete()` y `forceDelete()`?

**Respuesta:**
- **`delete()`**: Soft delete (si el modelo usa `SoftDeletes`), marca como eliminado pero mantiene el registro
- **`forceDelete()`**: Eliminación permanente de la base de datos

```php
// Soft delete
$post->delete();  // Establece deleted_at = now()

// Hard delete
$post->forceDelete();  // Elimina el registro completamente

// Ver registros eliminados
Post::withTrashed()->get();

// Restaurar registro eliminado
$post->restore();
```

---

### Pregunta 12: ¿Cómo ejecutar migraciones y seeders en un solo comando?

**Respuesta:**
```bash
php artisan migrate:fresh --seed
```
Esto:
1. Elimina todas las tablas
2. Ejecuta todas las migraciones
3. Ejecuta todos los seeders

---

### Pregunta 13: Escribe un query para obtener posts con más de 100 vistas, ordenados por fecha de creación descendente

**Respuesta:**
```php
$posts = Post::where('views', '>', 100)
             ->orderBy('created_at', 'desc')
             ->get();

// O usando latest()
$posts = Post::where('views', '>', 100)
             ->latest()
             ->get();
```

---

### Pregunta 14: ¿Cómo definir un valor por defecto en una migración?

**Respuesta:**
```php
$table->boolean('is_active')->default(true);
$table->integer('views')->default(0);
$table->string('status')->default('pending');
$table->timestamp('created_at')->useCurrent();
```

---

### Pregunta 15: ¿Qué es el N+1 problem y cómo se soluciona?

**Respuesta:**
**Problema N+1**: Hacer una query por cada registro relacionado.

```php
// MALO (N+1)
$posts = Post::all();  // 1 query
foreach ($posts as $post) {
    echo $post->user->name;  // N queries (una por post)
}

// BUENO (Eager Loading)
$posts = Post::with('user')->get();  // 2 queries totales
foreach ($posts as $post) {
    echo $post->user->name;  // Sin queries adicionales
}
```

---

## 🎯 TIPS PARA EL PARCIAL

1. **Memoriza los comandos Artisan más importantes**
2. **Practica escribir migraciones a mano**
3. **Entiende las diferencias entre `create()`, `firstOrCreate()`, y `updateOrCreate()`**
4. **Conoce los tipos de relaciones y cuándo usar cada una**
5. **Practica consultas Eloquent con `where()`, `orderBy()`, `with()`**
6. **Entiende qué hace `fillable`, `casts`, y `hidden`**
7. **Practica crear Enums con `values()` y `label()`**
8. **Conoce la estructura de rutas RESTful**
9. **Entiende Eager Loading para evitar N+1**
10. **Practica validaciones comunes (required, email, unique, etc.)**

---

## ✅ CHECKLIST DE ESTUDIO

- [ ] Puedo crear migraciones con tipos de columna correctos
- [ ] Sé definir llaves foráneas con `foreignId()` y `constrained()`
- [ ] Conozco la diferencia entre `hasMany()` y `belongsToMany()`
- [ ] Puedo escribir un seeder idempotente con `firstOrCreate()`
- [ ] Entiendo cómo usar Enums en migraciones y modelos
- [ ] Sé hacer queries con `where()`, `orderBy()`, `paginate()`
- [ ] Puedo crear controladores RESTful
- [ ] Entiendo qué es Eager Loading y por qué es importante
- [ ] Sé validar requests en controladores
- [ ] Conozco los comandos `migrate`, `db:seed`, `make:model`

---

## 📝 RESUMEN DE CONCEPTOS CLAVE

### MIGRACIONES
- ✅ Son "control de versiones" para la base de datos
- ✅ Métodos: `up()` crea/modifica, `down()` revierte
- ✅ Se ejecutan en orden cronológico por timestamp
- ✅ Registradas en tabla `migrations` para no duplicar
- ✅ Comandos: `migrate`, `rollback`, `refresh`, `fresh`

### MODELOS ELOQUENT
- ✅ ORM que mapea tablas a clases PHP
- ✅ Convenciones: Modelo singular (Post) → Tabla plural (posts)
- ✅ `$fillable`: campos asignables masivamente
- ✅ `$casts`: conversión automática de tipos
- ✅ Estados: new, persisted, dirty, clean, deleted
- ✅ CRUD: `create()`, `find()`, `update()`, `delete()`

### SEEDERS
- ✅ Pobladores automáticos de datos
- ✅ Idempotencia: usar `firstOrCreate()` para no duplicar
- ✅ Orden de ejecución importa (dependencias primero)
- ✅ Validar dependencias antes de ejecutar
- ✅ Métodos: `create()`, `firstOrCreate()`, `updateOrCreate()`

### ENUMS
- ✅ Conjunto cerrado de valores posibles
- ✅ Type-safe: PHP valida valores correctos
- ✅ Método `values()`: para usar en migraciones
- ✅ Método `label()`: para mostrar en UI
- ✅ En modelos: usar `$casts` para conversión automática
- ✅ En seeders: usar `->value` para obtener string

### RELACIONES
- ✅ **1:1**: `hasOne()` / `belongsTo()` - User ↔ Profile
- ✅ **1:N**: `hasMany()` / `belongsTo()` - User ↔ Posts
- ✅ **N:M**: `belongsToMany()` - Posts ↔ Tags (tabla pivot)
- ✅ Eager Loading: usar `with()` para evitar N+1
- ✅ Pivot: tabla intermedia con `sync()`, `attach()`, `detach()`

### CONTROLADORES
- ✅ Organizan lógica de negocio por recurso
- ✅ Patrón RESTful: index, show, store, update, destroy
- ✅ Resource routes: `Route::apiResource()`
- ✅ Inyección de dependencias en métodos

### RUTAS
- ✅ Mapean URL + HTTP method a acción
- ✅ `web.php`: páginas HTML con sesiones
- ✅ `api.php`: APIs JSON con tokens
- ✅ Parámetros dinámicos: `/posts/{id}`
- ✅ Grupos: prefijos, middleware, namespaces

### VALIDACIÓN
- ✅ Inline: `$request->validate()` en controlador
- ✅ Form Request: clase separada reutilizable
- ✅ Retorna 422 con errores si falla
- ✅ Reglas comunes: required, email, unique, exists, in
- ✅ Arrays: `'tags.*'` valida cada elemento
- ✅ Condicional: `required_if`, `nullable`

### SCRIPTS DE AUTOMATIZACIÓN

**migrate.sh:**
- ✅ Elimina base de datos SQLite existente
- ✅ Ejecuta `php artisan migrate --force --seed`
- ✅ Manejo de errores con códigos de salida
- ✅ Útil para resetear BD durante desarrollo
- ⚠️ NUNCA usar en producción (borra datos)

**mainsync.sh:**
- ✅ Sincroniza archivos desde branch `main` al actual
- ✅ Sobreescribe archivos modificados automáticamente
- ✅ Agrega archivos nuevos de main
- ✅ Protege archivos críticos (lista de ignorados)
- ✅ Muestra resumen categorizado de cambios
- ✅ Prepara cambios para commit manual
- ⚠️ Revisar cambios antes de hacer commit

### PHP ARTISAN SERVE
- ✅ Servidor de desarrollo integrado de Laravel
- ✅ Usa servidor web incorporado de PHP (`php -S`)
- ✅ Por defecto: `http://127.0.0.1:8000`
- ✅ Recarga automática de código PHP
- ✅ Logs visibles en terminal
- ✅ Opciones: `--host` y `--port`
- ✅ Solo para desarrollo, NUNCA producción
- ✅ Maneja 1 request a la vez (no concurrente)
- ✅ Detener con `Ctrl+C`

---

## 🎓 CONSEJOS PARA EL PARCIAL

### Para MIGRACIONES:
1. Siempre importa los Enums que uses
2. Usa `foreignId()->constrained()` para FK
3. Recuerda `timestamps()` al final
4. Define `unique()` donde corresponda
5. Usa `->onDelete('cascade')` en FK

### Para MODELOS:
1. Define `$fillable` con campos asignables
2. Usa `$casts` para Enums y fechas
3. Nombra relaciones en plural (muchos) o singular (uno)
4. Importa clases de relaciones (HasMany, BelongsTo, etc.)
5. Retorna tipo correcto en relaciones

### Para SEEDERS:
1. Usa `firstOrCreate()` para idempotencia
2. Valida dependencias antes de continuar
3. Ejecuta en orden correcto (DatabaseSeeder)
4. Usa `->value` en Enums
5. Usa `sync()` para relaciones N:M

### Para ENUMS:
1. Siempre incluye método `values()`
2. Usa backed enums con tipo (`: string`)
3. En migraciones: `EnumName::values()`
4. En modelos: `'campo' => EnumName::class` en `$casts`
5. En seeders: `EnumName::CASE->value`

### Para RELACIONES:
1. Identifica el tipo de relación primero
2. Define ambos lados de la relación
3. Usa `with()` para evitar N+1
4. En N:M crea tabla pivot con unique compuesto
5. Usa `sync()` para reemplazar relaciones

### Para VALIDACIÓN:
1. Valida SIEMPRE antes de guardar
2. Usa `required` para campos obligatorios
3. Usa `unique` con `ignore()` al actualizar
4. Valida arrays con `'campo.*'`
5. Retorna mensajes claros al usuario

### Para SCRIPTS DE AUTOMATIZACIÓN:
1. **migrate.sh**: Usa cuando necesites resetear BD completamente
2. Nunca ejecutes migrate.sh en producción
3. **mainsync.sh**: Ejecuta DESPUÉS de hacer commit de tus cambios
4. Revisa qué archivos están en la lista de ignorados
5. Después de mainsync, revisa cambios con `git diff` antes de commit

### Para PHP ARTISAN SERVE:
1. Usa `php artisan serve` SOLO para desarrollo local
2. Puerto por defecto es 8000, cambia con `--port=8001` si está ocupado
3. Usa `--host=0.0.0.0` para acceder desde otros dispositivos en tu red
4. Mantén la terminal abierta mientras desarrollas (no corre en background)
5. Detén con `Ctrl+C` cuando termines
6. Si no ves cambios, limpia cachés y reinicia servidor
7. NUNCA uses en producción (usa Apache/Nginx)

---

## 🔑 PATRONES COMUNES PARA RECORDAR

### Crear Migración con FK:
```php
$table->foreignId('user_id')->constrained()->onDelete('cascade');
```

### Modelo con Enum y Relación:
```php
protected $casts = ['status' => PostStatus::class];
public function user(): BelongsTo {
    return $this->belongsTo(User::class);
}
```

### Seeder Idempotente:
```php
Post::firstOrCreate(['title' => 'X'], $allData);
```

### Validación Básica:
```php
$request->validate([
    'email' => 'required|email|unique:users,email',
]);
```

### Relación N:M:
```php
$post->tags()->sync([1, 2, 3]);
```

### Resetear Base de Datos:
```bash
./migrate.sh
# Equivalente a: rm database/database.sqlite && php artisan migrate --force --seed
```

### Sincronizar desde Main:
```bash
./mainsync.sh
# O a un branch específico:
./mainsync.sh develop
```

---

## 💡 COMANDOS ÚTILES DEL PROYECTO

### Gestión de Base de Datos

```bash
# Reseteo completo (elimina todo y recrea)
./migrate.sh

# Equivalente manual
rm database/database.sqlite
php artisan migrate --force --seed

# Solo migraciones nuevas (sin borrar datos)
php artisan migrate

# Revertir última migración
php artisan migrate:rollback

# Ver estado de migraciones
php artisan migrate:status

# Solo ejecutar seeders
php artisan db:seed
```

### Sincronización entre Branches

```bash
# Sincronizar desde main al branch actual
./mainsync.sh

# Sincronizar a un branch específico
./mainsync.sh nombre-branch

# Ver qué hace el script
./mainsync.sh --help

# Dar permisos de ejecución (primera vez)
chmod +x mainsync.sh
chmod +x migrate.sh
```

### Flujo de Trabajo Recomendado

```bash
# 1. Guardar tu trabajo actual
git add .
git commit -m "Mi trabajo en progreso"

# 2. Sincronizar con main (si hay actualizaciones)
./mainsync.sh

# 3. Revisar qué cambió
git status
git diff HEAD

# 4. Si todo está bien, hacer commit
git commit -m "Sync from main: updated docs and tutorials"

# 5. Resetear BD con nuevas migraciones/seeders
./migrate.sh

# 6. Continuar trabajando
```

### Comandos de Desarrollo Laravel

```bash
# Servidor de desarrollo
php artisan serve

# Limpiar cachés
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Ver todas las rutas
php artisan route:list

# Consola interactiva (probar código)
php artisan tinker

# Crear symlink para storage público
php artisan storage:link
```

---

## 11. COMANDO PHP ARTISAN SERVE (DETALLADO)

### ¿Qué es `php artisan serve`?

El comando `php artisan serve` es un **servidor web de desarrollo integrado** que Laravel proporciona para facilitar el desarrollo local. Levanta un servidor HTTP ligero usando el servidor web incorporado de PHP.

#### Conceptos Fundamentales:

**¿Qué hace exactamente?**
- ✅ Inicia un servidor web en tu máquina local
- ✅ Sirve tu aplicación Laravel sin necesidad de Apache/Nginx
- ✅ Permite acceder a tu app desde el navegador
- ✅ Recarga automáticamente archivos PHP en cada request
- ✅ Muestra logs de peticiones HTTP en la consola

**¿Por qué existe?**
- ✅ **Desarrollo Rápido**: No necesitas instalar/configurar Apache o Nginx
- ✅ **Portabilidad**: Funciona igual en Windows, Mac, Linux
- ✅ **Simplicidad**: Un comando y ya tienes servidor
- ✅ **Debugging**: Ves logs directamente en la terminal
- ✅ **Aislamiento**: No interfiere con otros servidores en tu máquina

---

### Uso Básico

#### Comando Simple:
```bash
php artisan serve
```

**Resultado:**
```
Starting Laravel development server: http://127.0.0.1:8000
[Sun Nov 17 10:30:00 2025] PHP 8.2.0 Development Server (http://127.0.0.1:8000) started
```

**¿Qué significa?**
- `http://127.0.0.1:8000` → Dirección donde acceder
- `127.0.0.1` → IP localhost (tu propia máquina)
- `8000` → Puerto por defecto
- El servidor quedará **corriendo** hasta que presiones `Ctrl+C`

---

### Opciones y Parámetros

#### 1. Cambiar el Host:

```bash
php artisan serve --host=0.0.0.0
```

**¿Qué hace?**
- Por defecto, solo accesible desde tu máquina (`127.0.0.1`)
- Con `0.0.0.0`, accesible desde **cualquier dispositivo en tu red**

**Uso real:**
```bash
# Servidor en 0.0.0.0:8000
php artisan serve --host=0.0.0.0

# Ahora puedes acceder desde:
# - Tu PC: http://localhost:8000
# - Tu celular en misma WiFi: http://192.168.1.100:8000
# - Otra PC en tu red: http://192.168.1.100:8000
```

**Cuándo usar:**
- ✅ Probar tu app en celular/tablet
- ✅ Mostrar demo a alguien en tu red local
- ✅ Desarrollo con múltiples dispositivos

#### 2. Cambiar el Puerto:

```bash
php artisan serve --port=8080
```

**¿Por qué cambiar el puerto?**
- ❌ Puerto 8000 ya está ocupado por otra app
- ✅ Correr múltiples apps Laravel simultáneamente
- ✅ Evitar conflictos con otros servicios

**Ejemplo práctico:**
```bash
# Terminal 1 - Proyecto A
cd proyecto-a
php artisan serve --port=8000  # http://localhost:8000

# Terminal 2 - Proyecto B
cd proyecto-b
php artisan serve --port=8001  # http://localhost:8001

# Terminal 3 - Proyecto C
cd proyecto-c
php artisan serve --port=8002  # http://localhost:8002
```

#### 3. Combinar Host y Puerto:

```bash
php artisan serve --host=192.168.1.100 --port=9000
```

**Resultado:** Servidor en `http://192.168.1.100:9000`

---

### Logs del Servidor

Cuando el servidor está corriendo, verás logs de cada petición HTTP:

```bash
[Sun Nov 17 10:35:23 2025] 127.0.0.1:54234 Accepted
[Sun Nov 17 10:35:23 2025] 127.0.0.1:54234 [200]: GET /
[Sun Nov 17 10:35:23 2025] 127.0.0.1:54234 Closing

[Sun Nov 17 10:35:30 2025] 127.0.0.1:54235 Accepted
[Sun Nov 17 10:35:30 2025] 127.0.0.1:54235 [200]: GET /api/posts
[Sun Nov 17 10:35:30 2025] 127.0.0.1:54235 Closing

[Sun Nov 17 10:35:45 2025] 127.0.0.1:54236 Accepted
[Sun Nov 17 10:35:45 2025] 127.0.0.1:54236 [404]: GET /api/invalid
[Sun Nov 17 10:35:45 2025] 127.0.0.1:54236 Closing
```

**Interpretación:**
- `127.0.0.1:54234` → IP cliente y puerto aleatorio
- `[200]` → Código HTTP (200 OK, 404 Not Found, 500 Error)
- `GET /` → Método HTTP y ruta solicitada
- `Accepted` → Conexión iniciada
- `Closing` → Conexión terminada

---

### Detener el Servidor

```bash
# Presionar en la terminal donde corre el servidor:
Ctrl + C

# Verás:
^C
Server stopped.
```

**Importante:** El servidor NO corre en background (segundo plano), debes mantener la terminal abierta.

---

### Bajo el Capó: ¿Cómo Funciona?

#### 1. Servidor PHP Integrado

Laravel usa el servidor web integrado de PHP (`php -S`):

```bash
# Lo que realmente ejecuta Laravel internamente:
php -S localhost:8000 -t public/

# Donde:
# -S localhost:8000  → Host:Puerto
# -t public/         → Document root (carpeta pública)
```

#### 2. Punto de Entrada

```
Cliente (Navegador)
    ↓
http://localhost:8000/api/posts
    ↓
Servidor PHP (php artisan serve)
    ↓
public/index.php (punto de entrada único)
    ↓
bootstrap/app.php (carga Laravel)
    ↓
Kernel HTTP (maneja la request)
    ↓
Router (encuentra la ruta)
    ↓
Middleware (autenticación, etc.)
    ↓
Controlador (lógica de negocio)
    ↓
Modelo (consulta BD)
    ↓
Response (JSON/HTML)
    ↓
Cliente recibe respuesta
```

#### 3. Recarga Automática

```php
// NO necesitas reiniciar el servidor cuando cambias:
// ✅ Código en controladores
// ✅ Modelos
// ✅ Rutas
// ✅ Vistas Blade
// ✅ Configuraciones .env (en la siguiente request)

// SÍ necesitas reiniciar cuando cambias:
// ❌ Archivos en config/ (usa cache)
// ❌ Service Providers
// ❌ Middleware registrado
```

**Solución si no detecta cambios:**
```bash
# Detener servidor (Ctrl+C)
php artisan config:clear
php artisan cache:clear
php artisan serve  # Reiniciar
```

---

### Comparación: php artisan serve vs Apache/Nginx

| Característica | `php artisan serve` | Apache/Nginx |
|----------------|---------------------|--------------|
| **Instalación** | ✅ Incluido con Laravel | ❌ Instalación separada |
| **Configuración** | ✅ Cero configuración | ❌ Requiere vhosts/config |
| **Velocidad** | ⚠️ Más lento | ✅ Optimizado para producción |
| **Uso** | ✅ Solo desarrollo | ✅ Desarrollo y producción |
| **Concurrencia** | ❌ Una petición a la vez | ✅ Múltiples simultáneas |
| **Performance** | ⚠️ Básica | ✅ Alta |
| **SSL/HTTPS** | ❌ No | ✅ Sí |
| **Logs** | ✅ En terminal | ⚠️ En archivos |
| **Hot Reload** | ✅ Automático (PHP) | ⚠️ Requiere restart |

**Conclusión:**
- 🏗️ **Desarrollo**: Usa `php artisan serve`
- 🚀 **Producción**: Usa Apache/Nginx + PHP-FPM

---

### Casos de Uso Comunes

#### 1. Desarrollo Local Simple:
```bash
# Iniciar servidor
php artisan serve

# Abrir navegador
# http://localhost:8000
```

#### 2. Probar API desde Postman/Insomnia:
```bash
php artisan serve

# En Postman:
# GET http://localhost:8000/api/posts
# POST http://localhost:8000/api/posts
```

#### 3. Desarrollo Frontend (Vue/React) + Backend Laravel:
```bash
# Terminal 1 - Backend Laravel
cd backend
php artisan serve --port=8000

# Terminal 2 - Frontend Vue/React
cd frontend
npm run dev  # Corre en puerto 3000

# Frontend hace requests a http://localhost:8000/api/*
```

#### 4. Demo en Red Local:
```bash
# Obtener tu IP local
ifconfig  # Mac/Linux
ipconfig  # Windows
# Ejemplo: 192.168.1.100

# Iniciar servidor accesible en red
php artisan serve --host=0.0.0.0 --port=8000

# Compartir URL con otros:
# http://192.168.1.100:8000
```

#### 5. Debugging con Logs:
```bash
# Terminal 1 - Servidor
php artisan serve

# Terminal 2 - Ver logs Laravel en tiempo real
tail -f storage/logs/laravel.log

# Ahora ves requests en Terminal 1 y errores PHP en Terminal 2
```

---

### Problemas Comunes y Soluciones

#### Error: "Address already in use"

```bash
# Error
Failed to listen on 127.0.0.1:8000 (reason: Address already in use)
```

**Solución:**
```bash
# Opción 1: Cambiar puerto
php artisan serve --port=8001

# Opción 2: Encontrar y matar proceso que usa puerto 8000
# Linux/Mac:
lsof -ti:8000 | xargs kill -9

# Windows:
netstat -ano | findstr :8000
taskkill /PID <PID> /F
```

#### No se ven los cambios en el código

**Solución:**
```bash
# 1. Detener servidor (Ctrl+C)

# 2. Limpiar cachés
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# 3. Reiniciar servidor
php artisan serve
```

#### Error 404 en todas las rutas

**Problema:** El servidor no encuentra `public/index.php`

**Solución:**
```bash
# Verificar que estás en la raíz del proyecto Laravel
ls -la
# Deberías ver: artisan, composer.json, app/, public/, etc.

# Si estás en otra carpeta:
cd /ruta/a/tu/proyecto
php artisan serve
```

#### Muy lento en Windows

**Problema:** Windows Defender escanea cada archivo

**Solución:**
```bash
# Agregar carpeta del proyecto a exclusiones de Windows Defender
# Configuración > Virus & threat protection > Exclusions
# Agregar: C:\ruta\a\tu\proyecto
```

---

### Alternativas a php artisan serve

#### 1. Laravel Sail (Docker):
```bash
# Entorno completo con MySQL, Redis, etc.
./vendor/bin/sail up
# Acceder: http://localhost
```

#### 2. Laravel Valet (Mac):
```bash
# Servidor permanente en background
valet park
# Acceder: http://nombre-carpeta.test
```

#### 3. XAMPP/WAMP/MAMP:
```bash
# Servidor Apache + MySQL integrado
# Colocar proyecto en htdocs/
# Acceder: http://localhost/proyecto/public
```

#### 4. Docker Compose personalizado:
```yaml
# docker-compose.yml
version: '3'
services:
  app:
    image: php:8.2-apache
    ports:
      - "8000:80"
    volumes:
      - ./:/var/www/html
```

---

### Mejores Prácticas

#### ✅ DO (Hacer):
```bash
# Usar para desarrollo local
php artisan serve

# Cambiar puerto si hay conflictos
php artisan serve --port=8001

# Usar 0.0.0.0 para acceso en red
php artisan serve --host=0.0.0.0

# Mantener terminal visible para ver logs
# (No cerrar la ventana)
```

#### ❌ DON'T (No hacer):
```bash
# NUNCA usar en producción
# php artisan serve en servidor real ❌

# No dejar corriendo sin supervisión
# (Puede consumir recursos)

# No exponer a Internet directamente
# (Sin SSL, sin protecciones)

# No confiar en él para alto tráfico
# (Maneja 1 request a la vez)
```

---

### Resumen Ejecutivo

**`php artisan serve` es:**
- 🚀 Servidor de desarrollo **rápido y fácil**
- 💻 **Local-first**: ideal para desarrollo en tu máquina
- 📝 **Transparente**: ves todos los logs en consola
- ⚡ **Recarga automática** de código PHP
- 🔧 **Sin configuración**: funciona out-of-the-box

**NO uses `php artisan serve` para:**
- ❌ Producción (servidores reales)
- ❌ Alto tráfico (solo 1 request simultánea)
- ❌ SSL/HTTPS (no soportado)
- ❌ Performance crítica

**Comandos esenciales:**
```bash
php artisan serve                              # Iniciar
php artisan serve --port=8001                  # Puerto custom
php artisan serve --host=0.0.0.0               # Acceso red
php artisan serve --host=0.0.0.0 --port=8080  # Ambos
Ctrl + C                                       # Detener
```

---

## 12. ENDPOINTS Y APIS RESTFUL

### ¿Qué es un Endpoint?

Un **endpoint** es una **URL específica** de una API que acepta peticiones HTTP y devuelve respuestas. Es el "punto de entrada" a una funcionalidad específica de tu aplicación.

**Anatomía de un endpoint:**
```
MÉTODO HTTP + RUTA + PARÁMETROS (opcionales)
    ↓         ↓           ↓
   POST   /api/register  {name, email, password}
```

**Componentes clave:**
1. **Método HTTP**: Define la acción (GET, POST, PUT, DELETE)
2. **Ruta (URI)**: Identifica el recurso (`/api/users`, `/api/posts`)
3. **Parámetros**: Datos enviados (query params, body, headers)
4. **Respuesta**: JSON con datos o mensajes de error

---

### Ejemplo 1: POST /api/register - Registrar Usuario

**Propósito:** Crear una nueva cuenta de usuario en el sistema.

#### Detalles del Endpoint:
```http
POST /api/register
Content-Type: application/json
Accept: application/json

Body:
{
  "name": "Juan Pérez",
  "email": "juan@example.com",
  "password": "secreto123",
  "password_confirmation": "secreto123"
}
```

#### ¿Qué hace este endpoint?

1. **Recibe datos del usuario** en formato JSON
2. **Valida** que los datos sean correctos:
   - Email válido y único
   - Password mínimo 8 caracteres
   - Password confirmation coincide
3. **Hashea la contraseña** con bcrypt (seguridad)
4. **Crea el registro** en la tabla `users`
5. **Devuelve respuesta** con el usuario creado y/o token

#### Implementación en Laravel:

**Ruta (routes/api.php):**
```php
use App\Http\Controllers\AuthController;

Route::post('/register', [AuthController::class, 'register']);
```

**Controlador (AuthController.php):**
```php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // 1. VALIDAR datos de entrada
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // 2. CREAR usuario
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']), // Hashear password
        ]);

        // 3. GENERAR token (Sanctum)
        $token = $user->createToken('auth-token')->plainTextToken;

        // 4. DEVOLVER respuesta
        return response()->json([
            'message' => 'Usuario registrado exitosamente',
            'user' => $user,
            'token' => $token
        ], 201); // 201 = Created
    }
}
```

#### Respuestas posibles:

**✅ Éxito (201 Created):**
```json
{
  "message": "Usuario registrado exitosamente",
  "user": {
    "id": 1,
    "name": "Juan Pérez",
    "email": "juan@example.com",
    "created_at": "2025-11-17T10:30:00.000000Z"
  },
  "token": "1|abc123xyz..."
}
```

**❌ Error de validación (422 Unprocessable Entity):**
```json
{
  "message": "The email has already been taken.",
  "errors": {
    "email": [
      "The email has already been taken."
    ]
  }
}
```

---

### Ejemplo 2: GET /api/email/verify/{id}/{hash} - Verificar Email

**Propósito:** Confirmar que el usuario tiene acceso al email proporcionado durante el registro.

#### Detalles del Endpoint:
```http
GET /api/email/verify/1/abc123def456?expires=1700219400&signature=xyz789
```

**Parámetros:**
- `{id}`: ID del usuario (en la URL)
- `{hash}`: Hash del email (seguridad, evita manipulación)
- `?expires`: Timestamp de expiración del link
- `?signature`: Firma criptográfica (Laravel lo genera automáticamente)

#### ¿Qué hace este endpoint?

1. **Verifica la firma** del enlace (evita links falsos)
2. **Comprueba que no haya expirado** el link
3. **Valida el hash del email** contra el email real del usuario
4. **Marca el email como verificado** (`email_verified_at`)
5. **Redirige al usuario** o devuelve confirmación

#### Implementación en Laravel:

**Ruta (routes/api.php):**
```php
use Illuminate\Foundation\Auth\EmailVerificationRequest;

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill(); // Marca como verificado
    
    return response()->json([
        'message' => 'Email verificado exitosamente'
    ], 200);
})->middleware(['auth:sanctum', 'signed'])->name('verification.verify');
```

**Middleware aplicado:**
- `auth:sanctum`: Usuario debe estar autenticado
- `signed`: Verifica la firma criptográfica del link

#### Flujo completo de verificación:

```
1. Usuario se registra
   ↓
2. Sistema envía email con link de verificación
   Link: https://app.com/api/email/verify/1/abc123?expires=...&signature=...
   ↓
3. Usuario hace clic en el link
   ↓
4. Laravel ejecuta el endpoint GET /api/email/verify/{id}/{hash}
   ↓
5. Sistema valida:
   ✓ Firma correcta
   ✓ No expirado
   ✓ Hash coincide con email
   ↓
6. Actualiza user: email_verified_at = NOW()
   ↓
7. Respuesta: "Email verificado exitosamente"
```

#### Respuestas posibles:

**✅ Éxito (200 OK):**
```json
{
  "message": "Email verificado exitosamente"
}
```

**❌ Error - Link expirado (403 Forbidden):**
```json
{
  "message": "El link de verificación ha expirado."
}
```

**❌ Error - Firma inválida (403 Forbidden):**
```json
{
  "message": "Firma inválida."
}
```

**❌ Error - Ya verificado (400 Bad Request):**
```json
{
  "message": "El email ya ha sido verificado."
}
```

#### Modelo User con verificación:

```php
namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    protected $fillable = ['name', 'email', 'password'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
```

---

### APIs RESTful: Conceptos Fundamentales

**REST** (Representational State Transfer) es un **estilo arquitectónico** para diseñar APIs que se basa en:

#### Principios REST:

1. **Recursos (Resources)**: Todo es un recurso identificable
   - Usuarios → `/users`
   - Posts → `/posts`
   - Comentarios → `/comments`

2. **Métodos HTTP estándar**: Cada método tiene un propósito
   - `GET`: Leer/Obtener datos
   - `POST`: Crear nuevos recursos
   - `PUT/PATCH`: Actualizar recursos existentes
   - `DELETE`: Eliminar recursos

3. **Respuestas con códigos HTTP semánticos**:
   - `200 OK`: Éxito general
   - `201 Created`: Recurso creado
   - `204 No Content`: Éxito sin datos (ej: DELETE)
   - `400 Bad Request`: Error del cliente
   - `401 Unauthorized`: No autenticado
   - `403 Forbidden`: No autorizado
   - `404 Not Found`: Recurso no existe
   - `422 Unprocessable Entity`: Validación falló
   - `500 Internal Server Error`: Error del servidor

4. **Formato JSON estándar**:
   ```json
   {
     "data": { ... },
     "message": "Operación exitosa",
     "status": "success"
   }
   ```

5. **Sin estado (Stateless)**: Cada petición es independiente
   - No hay sesiones del lado del servidor
   - Se usa token (JWT, Sanctum) en cada request

---

### Ejemplo 3: API RESTful Completa para Posts

#### Endpoints del recurso Posts:

```http
GET    /api/posts           → Listar todos los posts
GET    /api/posts/{id}      → Ver un post específico
POST   /api/posts           → Crear un nuevo post
PUT    /api/posts/{id}      → Actualizar post completo
PATCH  /api/posts/{id}      → Actualizar campos parciales
DELETE /api/posts/{id}      → Eliminar un post
```

#### Implementación Laravel:

**Ruta (routes/api.php):**
```php
use App\Http\Controllers\PostController;

// Forma 1: Definir todas las rutas manualmente
Route::get('/posts', [PostController::class, 'index']);
Route::get('/posts/{id}', [PostController::class, 'show']);
Route::post('/posts', [PostController::class, 'store']);
Route::put('/posts/{id}', [PostController::class, 'update']);
Route::delete('/posts/{id}', [PostController::class, 'destroy']);

// Forma 2: Route Resource (genera todas automáticamente) ✅
Route::apiResource('posts', PostController::class);
```

**Controlador (PostController.php):**
```php
namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    // GET /api/posts - Listar todos
    public function index()
    {
        $posts = Post::with('user')->paginate(15);
        
        return response()->json([
            'data' => $posts
        ], 200);
    }

    // GET /api/posts/{id} - Ver uno
    public function show($id)
    {
        $post = Post::with('user', 'comments')->findOrFail($id);
        
        return response()->json([
            'data' => $post
        ], 200);
    }

    // POST /api/posts - Crear
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|in:draft,published'
        ]);

        $post = Post::create([
            ...$validated,
            'user_id' => auth()->id()
        ]);

        return response()->json([
            'message' => 'Post creado exitosamente',
            'data' => $post
        ], 201);
    }

    // PUT/PATCH /api/posts/{id} - Actualizar
    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);
        
        // Verificar autorización
        if ($post->user_id !== auth()->id()) {
            return response()->json([
                'message' => 'No autorizado'
            ], 403);
        }

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|string',
            'status' => 'sometimes|required|in:draft,published'
        ]);

        $post->update($validated);

        return response()->json([
            'message' => 'Post actualizado exitosamente',
            'data' => $post
        ], 200);
    }

    // DELETE /api/posts/{id} - Eliminar
    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        
        if ($post->user_id !== auth()->id()) {
            return response()->json([
                'message' => 'No autorizado'
            ], 403);
        }

        $post->delete();

        return response()->json([
            'message' => 'Post eliminado exitosamente'
        ], 204); // 204 No Content
    }
}
```

---

### Ejemplo 4: API RESTful para Autenticación

Sistema completo de autenticación con Sanctum:

```http
POST   /api/register            → Registrar usuario
POST   /api/login               → Login (devuelve token)
POST   /api/logout              → Logout (revoca token)
GET    /api/user                → Obtener usuario autenticado
POST   /api/password/forgot     → Solicitar reset de password
POST   /api/password/reset      → Resetear password
```

**Implementación AuthController:**
```php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // POST /api/login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return response()->json([
                'message' => 'Credenciales incorrectas'
            ], 401);
        }

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'message' => 'Login exitoso',
            'user' => $user,
            'token' => $token
        ], 200);
    }

    // POST /api/logout
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout exitoso'
        ], 200);
    }

    // GET /api/user
    public function user(Request $request)
    {
        return response()->json([
            'data' => $request->user()
        ], 200);
    }
}
```

**Rutas protegidas con Sanctum:**
```php
// routes/api.php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;

// Rutas públicas
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Rutas protegidas (requieren token)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    
    // CRUD de posts (solo usuarios autenticados)
    Route::apiResource('posts', PostController::class);
});
```

---

### Ventajas de las APIs RESTful

| Ventaja | Descripción |
|---------|-------------|
| 🌐 **Universal** | Funciona con cualquier cliente (web, móvil, IoT) |
| 📱 **Frontend agnóstico** | React, Vue, Angular, móviles nativos |
| 🔧 **Escalable** | Separa backend de frontend |
| 📚 **Predecible** | Convenciones claras (GET, POST, PUT, DELETE) |
| 🔄 **Cacheable** | GET requests se pueden cachear |
| 🧪 **Testeable** | Fácil de probar con Postman, PHPUnit |
| 🔐 **Seguro** | Token-based auth (Sanctum, JWT) |

---

### Convenciones REST en Laravel

#### Nomenclatura de rutas:
```php
GET    /api/posts           → posts.index
GET    /api/posts/{id}      → posts.show
POST   /api/posts           → posts.store
PUT    /api/posts/{id}      → posts.update
DELETE /api/posts/{id}      → posts.destroy
```

#### Plural vs Singular:
```php
✅ /api/posts        (CORRECTO - plural)
❌ /api/post         (INCORRECTO)

✅ /api/users/{id}   (CORRECTO)
❌ /api/user/{id}    (INCORRECTO)
```

#### Recursos anidados:
```php
GET /api/posts/{post_id}/comments          → Comentarios de un post
GET /api/users/{user_id}/posts             → Posts de un usuario
GET /api/posts/{post_id}/comments/{id}     → Un comentario específico
```

---

## 13. TESTING CON PHP ARTISAN TEST

### ¿Qué hace `php artisan test`?

`php artisan test` es el comando de Laravel para **ejecutar tests automatizados** usando **Pest** o **PHPUnit**. Verifica que tu código funcione correctamente y detecta bugs antes de producción.

**Sintaxis básica:**
```bash
php artisan test                           # Ejecutar todos los tests
php artisan test --parallel                # Ejecutar en paralelo (más rápido)
php artisan test --filter=UserTest         # Ejecutar test específico
php artisan test tests/Feature/PostTest.php # Ejecutar archivo específico
```

---

### ¿Por qué hacer testing?

| Beneficio | Descripción |
|-----------|-------------|
| 🐛 **Detectar bugs temprano** | Antes de que lleguen a producción |
| 🔒 **Código confiable** | Cada cambio se verifica automáticamente |
| 📚 **Documentación viva** | Los tests muestran cómo usar el código |
| 🚀 **Refactorizar seguro** | Cambiar código sin miedo a romper funcionalidad |
| ⏱️ **Ahorro de tiempo** | Testing manual es lento y propenso a errores |

---

### Tipos de Tests en Laravel

#### 1. **Feature Tests** (Tests de Funcionalidad)
Prueban **flujos completos** de la aplicación como un usuario real.

**Ejemplo: Test de registro de usuario**

```php
// tests/Feature/AuthTest.php
<?php

use App\Models\User;

test('user can register', function () {
    // Arrange: Preparar datos
    $userData = [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123'
    ];

    // Act: Ejecutar acción (POST al endpoint)
    $response = $this->postJson('/api/register', $userData);

    // Assert: Verificar resultados
    $response->assertStatus(201); // Status code 201 Created
    $response->assertJsonStructure([
        'message',
        'user' => ['id', 'name', 'email'],
        'token'
    ]);

    // Verificar que el usuario existe en BD
    $this->assertDatabaseHas('users', [
        'email' => 'test@example.com'
    ]);
});

test('user cannot register with duplicate email', function () {
    // Crear usuario existente
    User::factory()->create(['email' => 'test@example.com']);

    // Intentar registrar con mismo email
    $response = $this->postJson('/api/register', [
        'name' => 'Another User',
        'email' => 'test@example.com', // Email duplicado
        'password' => 'password123',
        'password_confirmation' => 'password123'
    ]);

    // Debe fallar con error 422
    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['email']);
});
```

#### 2. **Unit Tests** (Tests Unitarios)
Prueban **funciones o métodos individuales** en aislamiento.

**Ejemplo: Test de modelo Post**

```php
// tests/Unit/PostTest.php
<?php

use App\Models\Post;
use App\Models\User;

test('post belongs to a user', function () {
    // Crear usuario y post
    $user = User::factory()->create();
    $post = Post::factory()->create(['user_id' => $user->id]);

    // Verificar relación
    expect($post->user)->toBeInstanceOf(User::class);
    expect($post->user->id)->toBe($user->id);
});

test('post status enum works correctly', function () {
    $post = Post::factory()->create(['status' => 'draft']);

    expect($post->status)->toBe('draft');
    expect($post->isDraft())->toBeTrue();
    expect($post->isPublished())->toBeFalse();
});
```

---

### Estructura de un Test (Patrón AAA)

Todos los tests siguen el patrón **Arrange-Act-Assert**:

```php
test('example test', function () {
    // 🔧 ARRANGE (Preparar)
    // Configurar datos, crear modelos, preparar estado
    $user = User::factory()->create();
    $this->actingAs($user); // Autenticar usuario

    // ⚡ ACT (Actuar)
    // Ejecutar la acción que quieres probar
    $response = $this->getJson('/api/posts');

    // ✅ ASSERT (Afirmar)
    // Verificar que el resultado es el esperado
    $response->assertStatus(200);
    $response->assertJsonCount(10, 'data');
});
```

---

### Comandos y Opciones de `php artisan test`

```bash
# EJECUCIÓN BÁSICA
php artisan test                              # Todos los tests
php artisan test --testsuite=Feature         # Solo Feature tests
php artisan test --testsuite=Unit            # Solo Unit tests

# FILTROS
php artisan test --filter=UserTest           # Por nombre de clase
php artisan test --filter=can_register       # Por nombre de método/test
php artisan test tests/Feature/PostTest.php  # Archivo específico

# PERFORMANCE
php artisan test --parallel                  # Ejecutar en paralelo (más rápido)
php artisan test --parallel --processes=4    # Especificar procesos

# OUTPUT
php artisan test --compact                   # Output compacto
php artisan test --verbose                   # Output detallado
php artisan test --stop-on-failure           # Parar al primer error

# COBERTURA
php artisan test --coverage                  # Mostrar cobertura de código
php artisan test --coverage --min=80         # Requiere mínimo 80% cobertura
```

---

### Ejemplo Completo: Test de API RESTful Posts

```php
// tests/Feature/PostApiTest.php
<?php

use App\Models\Post;
use App\Models\User;

beforeEach(function () {
    // Se ejecuta antes de cada test
    $this->user = User::factory()->create();
});

test('authenticated user can list posts', function () {
    Post::factory()->count(5)->create();

    $response = $this->actingAs($this->user)
        ->getJson('/api/posts');

    $response->assertStatus(200);
    $response->assertJsonCount(5, 'data');
});

test('authenticated user can create post', function () {
    $postData = [
        'title' => 'New Post',
        'content' => 'This is the content',
        'status' => 'draft'
    ];

    $response = $this->actingAs($this->user)
        ->postJson('/api/posts', $postData);

    $response->assertStatus(201);
    $response->assertJsonFragment(['title' => 'New Post']);

    $this->assertDatabaseHas('posts', [
        'title' => 'New Post',
        'user_id' => $this->user->id
    ]);
});

test('user can update their own post', function () {
    $post = Post::factory()->create(['user_id' => $this->user->id]);

    $response = $this->actingAs($this->user)
        ->putJson("/api/posts/{$post->id}", [
            'title' => 'Updated Title'
        ]);

    $response->assertStatus(200);

    $this->assertDatabaseHas('posts', [
        'id' => $post->id,
        'title' => 'Updated Title'
    ]);
});

test('user cannot update another user post', function () {
    $otherUser = User::factory()->create();
    $post = Post::factory()->create(['user_id' => $otherUser->id]);

    $response = $this->actingAs($this->user)
        ->putJson("/api/posts/{$post->id}", [
            'title' => 'Hacked Title'
        ]);

    $response->assertStatus(403); // Forbidden
});

test('unauthenticated user cannot create post', function () {
    $response = $this->postJson('/api/posts', [
        'title' => 'Unauthorized Post'
    ]);

    $response->assertStatus(401); // Unauthorized
});
```

---

### Aserciones Comunes en Laravel Tests

#### Aserciones HTTP:
```php
$response->assertStatus(200);              // Status code exacto
$response->assertOk();                     // Status 200
$response->assertCreated();                // Status 201
$response->assertNoContent();              // Status 204
$response->assertNotFound();               // Status 404
$response->assertForbidden();              // Status 403
$response->assertUnauthorized();           // Status 401
```

#### Aserciones JSON:
```php
$response->assertJson(['key' => 'value']); // Contiene fragmento JSON
$response->assertJsonFragment(['name' => 'John']);
$response->assertJsonStructure([          // Verifica estructura
    'data' => ['id', 'name', 'email']
]);
$response->assertJsonCount(5, 'data');    // Cantidad de elementos
$response->assertJsonMissing(['password']); // No contiene campo
```

#### Aserciones de Base de Datos:
```php
$this->assertDatabaseHas('users', [        // Registro existe
    'email' => 'test@example.com'
]);
$this->assertDatabaseMissing('users', [    // Registro NO existe
    'email' => 'deleted@example.com'
]);
$this->assertDatabaseCount('posts', 10);   // Cantidad de registros
```

---

### Factories para Testing

Los **Factories** generan datos de prueba rápidamente:

```php
// database/factories/PostFactory.php
<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => fake()->sentence(),
            'content' => fake()->paragraphs(3, true),
            'status' => fake()->randomElement(['draft', 'published']),
        ];
    }

    // State: post publicado
    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'published',
        ]);
    }
}
```

**Uso en tests:**
```php
// Crear un post
$post = Post::factory()->create();

// Crear 10 posts
$posts = Post::factory()->count(10)->create();

// Crear post con datos específicos
$post = Post::factory()->create([
    'title' => 'Custom Title'
]);

// Crear post publicado
$post = Post::factory()->published()->create();

// Crear usuario con 5 posts
$user = User::factory()
    ->has(Post::factory()->count(5))
    ->create();
```

---

### Output de `php artisan test`

**Ejemplo de ejecución exitosa:**
```bash
$ php artisan test

   PASS  Tests\Feature\AuthTest
  ✓ user can register                                    0.15s
  ✓ user cannot register with duplicate email            0.08s

   PASS  Tests\Feature\PostApiTest
  ✓ authenticated user can list posts                    0.12s
  ✓ authenticated user can create post                   0.10s
  ✓ user can update their own post                       0.11s
  ✓ user cannot update another user post                 0.09s
  ✓ unauthenticated user cannot create post              0.06s

   PASS  Tests\Unit\PostTest
  ✓ post belongs to a user                               0.05s
  ✓ post status enum works correctly                     0.04s

  Tests:    9 passed (18 assertions)
  Duration: 0.80s
```

**Ejemplo de test fallido:**
```bash
   FAIL  Tests\Feature\PostApiTest
  ⨯ user can create post                                 0.12s
  ──────────────────────────────────────────────────────────────
   FAILED  Tests\Feature\PostApiTest > user can create post
  Expected response status code [201] but received 422.
  Failed asserting that 422 is identical to 201.

  at tests/Feature/PostApiTest.php:23
```

---

### Buenas Prácticas de Testing

| Práctica | Descripción |
|----------|-------------|
| 🎯 **Un concepto por test** | Cada test verifica UNA cosa |
| 📝 **Nombres descriptivos** | `test('user can update their own post')` |
| 🧹 **Tests independientes** | No dependen del orden de ejecución |
| 🔄 **Usar Factories** | No datos hardcodeados |
| 🚀 **Ejecutar frecuentemente** | En cada cambio de código |
| 📊 **Cobertura > 80%** | Código crítico 100% cubierto |
| ⚡ **Tests rápidos** | < 1 segundo por test idealmente |

---

## 14. ORM ELOQUENT - MAPEO OBJETO-RELACIONAL

### ¿Qué es Eloquent ORM?

**Eloquent** es el **ORM (Object-Relational Mapping)** de Laravel. Permite **trabajar con la base de datos usando objetos PHP** en lugar de escribir SQL manualmente.

**ORM = Object-Relational Mapping**
- **Object** (Objeto): Clases PHP (modelos)
- **Relational** (Relacional): Tablas de base de datos
- **Mapping** (Mapeo): Traducción automática entre objetos y tablas

---

### Concepto: ¿Qué resuelve un ORM?

**Sin ORM (SQL puro):**
```php
// Obtener todos los posts
$posts = DB::select('SELECT * FROM posts WHERE status = ?', ['published']);

// Crear un post
DB::insert('INSERT INTO posts (title, content, user_id) VALUES (?, ?, ?)', 
    ['My Post', 'Content here', 1]
);

// Actualizar post
DB::update('UPDATE posts SET title = ? WHERE id = ?', ['New Title', 5]);

// Eliminar post
DB::delete('DELETE FROM posts WHERE id = ?', [5]);

// Obtener posts con usuario (JOIN manual)
$posts = DB::select('
    SELECT posts.*, users.name as user_name 
    FROM posts 
    INNER JOIN users ON posts.user_id = users.id
    WHERE posts.status = ?
', ['published']);
```

**Con ORM Eloquent:**
```php
// Obtener todos los posts
$posts = Post::where('status', 'published')->get();

// Crear un post
$post = Post::create([
    'title' => 'My Post',
    'content' => 'Content here',
    'user_id' => 1
]);

// Actualizar post
$post = Post::find(5);
$post->update(['title' => 'New Title']);

// Eliminar post
Post::destroy(5);

// Obtener posts con usuario (relación automática)
$posts = Post::with('user')->where('status', 'published')->get();

// Acceder al usuario del post
echo $posts[0]->user->name; // Acceso directo por relación
```

---

### Ventajas de Eloquent ORM

| Ventaja | Descripción |
|---------|-------------|
| 🧑‍💻 **Sintaxis elegante** | Código más limpio y legible |
| 🔐 **Seguridad** | Previene SQL Injection automáticamente |
| 🔗 **Relaciones fáciles** | `$post->user`, `$user->posts` |
| 🚀 **Productividad** | Menos código, más funcionalidad |
| 🧪 **Testeable** | Fácil de mockear en tests |
| 📦 **Reutilizable** | Lógica en el modelo (encapsulación) |
| 🔄 **Portabilidad** | Cambiar BD (MySQL, PostgreSQL) sin cambiar código |

---

### Mapeo Tabla ↔ Modelo

Eloquent sigue convenciones para mapear automáticamente:

| Convención | Ejemplo |
|------------|---------|
| **Tabla plural** → **Modelo singular** | `posts` → `Post` |
| | `users` → `User` |
| | `categories` → `Category` |
| **ID de tabla** | `id` (auto-incremental) |
| **Timestamps** | `created_at`, `updated_at` |
| **Clave foránea** | `{modelo}_id` (ej: `user_id`) |

**Ejemplo de mapeo:**
```php
// Modelo: Post.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $table = 'posts';          // Opcional si sigue convención
    protected $primaryKey = 'id';        // Opcional si es 'id'
    public $timestamps = true;           // Opcional si usa created_at/updated_at
    
    protected $fillable = [              // Campos asignables masivamente
        'title', 'content', 'user_id', 'status'
    ];
}
```

```sql
-- Tabla: posts
CREATE TABLE posts (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    status ENUM('draft', 'published') DEFAULT 'draft',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id)
);
```

---

### CRUD con Eloquent (Ejemplos Prácticos)

#### 1. CREATE (Crear registros)

```php
// Forma 1: create() con array
$post = Post::create([
    'title' => 'Mi primer post',
    'content' => 'Contenido del post',
    'user_id' => auth()->id(),
    'status' => 'draft'
]);

// Forma 2: new + save()
$post = new Post();
$post->title = 'Mi segundo post';
$post->content = 'Más contenido';
$post->user_id = 1;
$post->status = 'published';
$post->save();

// Forma 3: firstOrCreate() (buscar o crear)
$post = Post::firstOrCreate(
    ['title' => 'Post único'],           // Criterio de búsqueda
    ['content' => 'Contenido', 'user_id' => 1] // Datos si se crea
);

// Forma 4: updateOrCreate() (buscar y actualizar o crear)
$post = Post::updateOrCreate(
    ['title' => 'Post actualizable'],
    ['content' => 'Nuevo contenido', 'status' => 'published']
);
```

#### 2. READ (Leer/Consultar registros)

```php
// Obtener todos
$posts = Post::all();

// Obtener con condición
$publishedPosts = Post::where('status', 'published')->get();

// Obtener uno por ID
$post = Post::find(1);                  // Devuelve null si no existe
$post = Post::findOrFail(1);            // Lanza excepción si no existe

// Obtener primero que coincida
$post = Post::where('status', 'draft')->first();
$post = Post::where('status', 'draft')->firstOrFail();

// Contar registros
$count = Post::where('status', 'published')->count();

// Verificar existencia
$exists = Post::where('title', 'Test')->exists();

// Paginación
$posts = Post::paginate(15);            // 15 por página

// Seleccionar campos específicos
$posts = Post::select('id', 'title')->get();

// Ordenar
$posts = Post::orderBy('created_at', 'desc')->get();
$posts = Post::latest()->get();         // Atajo para orderBy created_at desc

// Consultas avanzadas
$posts = Post::where('status', 'published')
    ->where('user_id', 1)
    ->orWhere('featured', true)
    ->orderBy('created_at', 'desc')
    ->limit(10)
    ->get();
```

#### 3. UPDATE (Actualizar registros)

```php
// Forma 1: Buscar y actualizar
$post = Post::find(1);
$post->title = 'Título actualizado';
$post->save();

// Forma 2: update() con array
$post = Post::find(1);
$post->update([
    'title' => 'Nuevo título',
    'status' => 'published'
]);

// Forma 3: Actualización masiva (múltiples registros)
Post::where('status', 'draft')->update([
    'status' => 'archived'
]);

// Incrementar/decrementar valores
$post->increment('views');              // views = views + 1
$post->increment('views', 5);           // views = views + 5
$post->decrement('likes');              // likes = likes - 1
```

#### 4. DELETE (Eliminar registros)

```php
// Forma 1: Buscar y eliminar
$post = Post::find(1);
$post->delete();

// Forma 2: Eliminar por ID
Post::destroy(1);                       // Un registro
Post::destroy([1, 2, 3]);               // Múltiples registros

// Forma 3: Eliminación masiva
Post::where('status', 'draft')->delete();

// Soft Delete (eliminación suave - no borra físicamente)
// Requiere usar trait SoftDeletes en el modelo
$post->delete();                        // Marca como eliminado
$post->forceDelete();                   // Elimina definitivamente
$post->restore();                       // Restaura registro eliminado

// Consultar registros eliminados (soft deleted)
$posts = Post::withTrashed()->get();    // Incluye eliminados
$posts = Post::onlyTrashed()->get();    // Solo eliminados
```

---

### Relaciones en Eloquent

Las relaciones permiten trabajar con tablas relacionadas como objetos:

```php
// Modelo Post
class Post extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}

// Modelo User
class User extends Model
{
    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}

// Uso en código
$post = Post::find(1);
echo $post->user->name;                 // Acceso al usuario del post

$user = User::find(1);
foreach ($user->posts as $post) {       // Acceso a todos los posts del usuario
    echo $post->title;
}

// Eager Loading (evitar problema N+1)
$posts = Post::with('user', 'comments')->get(); // Carga relaciones en 1 query
```

---

### Query Builder vs Eloquent

**Query Builder** (sin modelo):
```php
$posts = DB::table('posts')
    ->where('status', 'published')
    ->join('users', 'posts.user_id', '=', 'users.id')
    ->select('posts.*', 'users.name')
    ->get();

// Devuelve objetos stdClass (no modelos)
```

**Eloquent ORM** (con modelo):
```php
$posts = Post::where('status', 'published')
    ->with('user')
    ->get();

// Devuelve colección de modelos Post
// Acceso: $posts[0]->user->name
```

---

### Scopes en Eloquent (Consultas Reutilizables)

**Local Scopes** (métodos del modelo):
```php
// Modelo Post
class Post extends Model
{
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }
}

// Uso
$posts = Post::published()->recent()->get();
$posts = Post::published()->where('user_id', 1)->get();
```

---

### Eloquent Collections

Eloquent devuelve **Collections** (colecciones) con métodos útiles:

```php
$posts = Post::all();

// Filtrar
$published = $posts->where('status', 'published');

// Mapear
$titles = $posts->pluck('title');       // ['Title 1', 'Title 2', ...]

// Agrupar
$grouped = $posts->groupBy('status');   // ['draft' => [...], 'published' => [...]]

// Contar
$count = $posts->count();

// Primero/Último
$first = $posts->first();
$last = $posts->last();

// Encadenar métodos
$result = $posts
    ->where('status', 'published')
    ->sortByDesc('created_at')
    ->take(5);
```

---

### Resumen: Eloquent ORM

**Eloquent ORM** convierte tablas en objetos PHP:
- ✅ **Menos código SQL**: Métodos PHP en lugar de queries
- ✅ **Más seguro**: Previene SQL Injection
- ✅ **Relaciones simples**: `$post->user` en lugar de JOINs
- ✅ **Código expresivo**: `Post::where('status', 'published')->get()`
- ✅ **Testeable**: Fácil de mockear en tests
- ✅ **Productivo**: Desarrollar más rápido

**Mapeo básico:**
```
Tabla posts ↔ Modelo Post
    - posts.id ↔ $post->id
    - posts.title ↔ $post->title
    - posts.user_id ↔ $post->user_id
```

**CRUD básico:**
```php
Post::create([...]);            // CREATE
Post::find(1);                  // READ
$post->update([...]);           // UPDATE
$post->delete();                // DELETE
```

---

## 15. PROMESAS EN JAVASCRIPT

### ¿Qué es una Promesa?

Una **Promesa (Promise)** es un objeto de JavaScript que representa el **resultado eventual** (éxito o fallo) de una **operación asíncrona**. Es una forma moderna y elegante de manejar código asíncrono, evitando el "callback hell".

**Analogía del mundo real:**
```
Imagina que pides una pizza por teléfono:
1. Haces el pedido (inicias la promesa)
2. Te dan un número de orden (la promesa)
3. Mientras esperas, puedes hacer otras cosas (código asíncrono)
4. La pizza llega (promesa cumplida) o se cancela el pedido (promesa rechazada)
```

---

### Estados de una Promesa

Una promesa puede estar en uno de estos tres estados:

```javascript
// 1. PENDING (Pendiente) - Estado inicial
const promesa = new Promise((resolve, reject) => {
    // Operación asíncrona en progreso...
});

// 2. FULFILLED (Cumplida) - Operación exitosa
resolve(resultado); // La promesa se resuelve con un valor

// 3. REJECTED (Rechazada) - Operación fallida
reject(error); // La promesa se rechaza con un error
```

**Diagrama de estados:**
```
    PENDING
       ↓
    ┌──┴──┐
    ↓     ↓
FULFILLED  REJECTED
(éxito)    (error)
```

---

### Crear una Promesa

```javascript
// Sintaxis básica
const miPromesa = new Promise((resolve, reject) => {
    // Código asíncrono aquí
    
    if (/* operación exitosa */) {
        resolve(valor); // Cumplir la promesa
    } else {
        reject(error);  // Rechazar la promesa
    }
});
```

**Ejemplo 1: Promesa simple**
```javascript
const esperarTresSegundos = new Promise((resolve, reject) => {
    setTimeout(() => {
        resolve("¡Han pasado 3 segundos!");
    }, 3000);
});

// Usar la promesa
esperarTresSegundos.then(mensaje => {
    console.log(mensaje); // "¡Han pasado 3 segundos!"
});
```

**Ejemplo 2: Promesa con éxito o error**
```javascript
function verificarEdad(edad) {
    return new Promise((resolve, reject) => {
        setTimeout(() => {
            if (edad >= 18) {
                resolve("✅ Acceso permitido");
            } else {
                reject("❌ Acceso denegado: Eres menor de edad");
            }
        }, 1000);
    });
}

// Usar la promesa
verificarEdad(20)
    .then(mensaje => console.log(mensaje))  // "✅ Acceso permitido"
    .catch(error => console.error(error));

verificarEdad(15)
    .then(mensaje => console.log(mensaje))
    .catch(error => console.error(error));  // "❌ Acceso denegado..."
```

---

### Consumir Promesas: .then() y .catch()

```javascript
promesa
    .then(resultado => {
        // Se ejecuta si la promesa se cumple (resolve)
        console.log("Éxito:", resultado);
        return otraOperacion(); // Puedes encadenar
    })
    .catch(error => {
        // Se ejecuta si la promesa se rechaza (reject)
        console.error("Error:", error);
    })
    .finally(() => {
        // Se ejecuta SIEMPRE, éxito o error
        console.log("Operación finalizada");
    });
```

**Ejemplo práctico: Llamada a API**
```javascript
function obtenerUsuario(id) {
    return fetch(`/api/users/${id}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Usuario no encontrado');
            }
            return response.json();
        })
        .then(usuario => {
            console.log('Usuario:', usuario.name);
            return usuario;
        })
        .catch(error => {
            console.error('Error:', error.message);
        })
        .finally(() => {
            console.log('Petición completada');
        });
}

obtenerUsuario(1);
```

---

### Encadenamiento de Promesas

Las promesas se pueden encadenar para realizar operaciones secuenciales:

```javascript
function paso1() {
    return new Promise(resolve => {
        setTimeout(() => {
            console.log("Paso 1 completado");
            resolve(10);
        }, 1000);
    });
}

function paso2(numero) {
    return new Promise(resolve => {
        setTimeout(() => {
            console.log("Paso 2 completado");
            resolve(numero * 2);
        }, 1000);
    });
}

function paso3(numero) {
    return new Promise(resolve => {
        setTimeout(() => {
            console.log("Paso 3 completado");
            resolve(numero + 5);
        }, 1000);
    });
}

// Encadenamiento
paso1()
    .then(resultado1 => paso2(resultado1))  // 10 * 2 = 20
    .then(resultado2 => paso3(resultado2))  // 20 + 5 = 25
    .then(resultadoFinal => {
        console.log("Resultado final:", resultadoFinal); // 25
    })
    .catch(error => {
        console.error("Error en algún paso:", error);
    });
```

---

### Async/Await: Sintaxis Moderna

`async/await` es **azúcar sintáctica** sobre las promesas, hace el código asíncrono más legible:

```javascript
// SIN async/await (con .then)
function obtenerDatos() {
    return fetch('/api/posts')
        .then(response => response.json())
        .then(posts => {
            console.log(posts);
            return posts;
        })
        .catch(error => {
            console.error(error);
        });
}

// CON async/await (más legible)
async function obtenerDatos() {
    try {
        const response = await fetch('/api/posts');
        const posts = await response.json();
        console.log(posts);
        return posts;
    } catch (error) {
        console.error(error);
    }
}
```

**Reglas de async/await:**
1. `async` convierte una función en asíncrona (devuelve una promesa)
2. `await` pausa la ejecución hasta que la promesa se resuelva
3. Solo se puede usar `await` dentro de funciones `async`
4. Usar `try/catch` para manejar errores

**Ejemplo completo con Laravel API:**
```javascript
// Función para obtener y mostrar posts
async function mostrarPosts() {
    try {
        // 1. Obtener posts
        const response = await fetch('/api/posts');
        
        if (!response.ok) {
            throw new Error('Error al obtener posts');
        }
        
        const data = await response.json();
        const posts = data.data; // Laravel suele envolver en 'data'
        
        // 2. Mostrar en consola
        console.log(`Total de posts: ${posts.length}`);
        posts.forEach(post => {
            console.log(`- ${post.title}`);
        });
        
        return posts;
    } catch (error) {
        console.error('Error:', error.message);
    } finally {
        console.log('Operación finalizada');
    }
}

// Llamar la función
mostrarPosts();
```

---

### Promesas Múltiples: Promise.all()

Ejecuta múltiples promesas en **paralelo** y espera a que **todas** se resuelvan:

```javascript
const promesa1 = fetch('/api/users');
const promesa2 = fetch('/api/posts');
const promesa3 = fetch('/api/comments');

// Promise.all espera a que todas se completen
Promise.all([promesa1, promesa2, promesa3])
    .then(([users, posts, comments]) => {
        return Promise.all([
            users.json(),
            posts.json(),
            comments.json()
        ]);
    })
    .then(([usersData, postsData, commentsData]) => {
        console.log('Usuarios:', usersData);
        console.log('Posts:', postsData);
        console.log('Comentarios:', commentsData);
    })
    .catch(error => {
        // Si CUALQUIERA falla, entra aquí
        console.error('Error en alguna petición:', error);
    });

// Con async/await (más limpio)
async function obtenerTodosLosDatos() {
    try {
        const [users, posts, comments] = await Promise.all([
            fetch('/api/users').then(r => r.json()),
            fetch('/api/posts').then(r => r.json()),
            fetch('/api/comments').then(r => r.json())
        ]);
        
        console.log('Usuarios:', users);
        console.log('Posts:', posts);
        console.log('Comentarios:', comments);
    } catch (error) {
        console.error('Error:', error);
    }
}
```

---

### Promise.race()

Devuelve el resultado de la **primera** promesa que se resuelva (éxito o error):

```javascript
const promesaLenta = new Promise(resolve => {
    setTimeout(() => resolve("Soy lenta (5s)"), 5000);
});

const promesaRapida = new Promise(resolve => {
    setTimeout(() => resolve("Soy rápida (1s)"), 1000);
});

Promise.race([promesaLenta, promesaRapida])
    .then(resultado => {
        console.log(resultado); // "Soy rápida (1s)"
    });

// Uso práctico: Timeout
async function fetchConTimeout(url, timeout = 5000) {
    const fetchPromise = fetch(url);
    const timeoutPromise = new Promise((_, reject) => {
        setTimeout(() => reject(new Error('Timeout')), timeout);
    });
    
    return Promise.race([fetchPromise, timeoutPromise]);
}
```

---

### Ejemplo Real: Autenticación con Laravel API

```javascript
// Función de login que devuelve una promesa
async function login(email, password) {
    try {
        const response = await fetch('/api/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ email, password })
        });

        if (!response.ok) {
            const error = await response.json();
            throw new Error(error.message || 'Credenciales incorrectas');
        }

        const data = await response.json();
        
        // Guardar token
        localStorage.setItem('token', data.token);
        localStorage.setItem('user', JSON.stringify(data.user));
        
        console.log('Login exitoso:', data.user.name);
        return data;
        
    } catch (error) {
        console.error('Error en login:', error.message);
        throw error; // Re-lanzar para que quien llame pueda manejarlo
    }
}

// Usar la función
async function iniciarSesion() {
    try {
        const usuario = await login('juan@example.com', 'password123');
        console.log('Bienvenido:', usuario.user.name);
        
        // Redirigir al dashboard
        window.location.href = '/dashboard';
    } catch (error) {
        // Mostrar error en la UI
        document.getElementById('error').textContent = error.message;
    }
}
```

---

### Buenas Prácticas con Promesas

| ✅ HACER | ❌ NO HACER |
|---------|-------------|
| Usar `async/await` para código más limpio | Callback hell (promesas anidadas) |
| Siempre usar `.catch()` o `try/catch` | Ignorar errores |
| Usar `Promise.all()` para paralelizar | Ejecutar promesas secuencialmente innecesariamente |
| Devolver promesas en funciones async | Mezclar callbacks y promesas |
| Usar `finally()` para cleanup | Duplicar código en then/catch |

**❌ Callback Hell (mal):**
```javascript
fetch('/api/user/1')
    .then(response => response.json())
    .then(user => {
        fetch(`/api/posts?user_id=${user.id}`)
            .then(response => response.json())
            .then(posts => {
                fetch(`/api/comments?post_id=${posts[0].id}`)
                    .then(response => response.json())
                    .then(comments => {
                        console.log(comments); // 😱 Anidación profunda
                    });
            });
    });
```

**✅ Encadenamiento limpio (bien):**
```javascript
async function obtenerDatos() {
    const user = await fetch('/api/user/1').then(r => r.json());
    const posts = await fetch(`/api/posts?user_id=${user.id}`).then(r => r.json());
    const comments = await fetch(`/api/comments?post_id=${posts[0].id}`).then(r => r.json());
    return comments;
}
```

---

### Resumen: Promesas

**¿Qué es?** Objeto que representa el resultado futuro de una operación asíncrona.

**Estados:**
- `Pending` (pendiente)
- `Fulfilled` (cumplida)
- `Rejected` (rechazada)

**Métodos principales:**
```javascript
.then(callback)     // Cuando se cumple
.catch(callback)    // Cuando se rechaza
.finally(callback)  // Siempre se ejecuta
```

**Métodos estáticos:**
```javascript
Promise.all([p1, p2])    // Espera a todas
Promise.race([p1, p2])   // Primera que resuelva
Promise.resolve(valor)   // Promesa cumplida inmediata
Promise.reject(error)    // Promesa rechazada inmediata
```

**Sintaxis moderna:**
```javascript
async function miFuncion() {
    try {
        const resultado = await miPromesa();
        return resultado;
    } catch (error) {
        console.error(error);
    }
}
```

---

## 16. DOM - DOCUMENT OBJECT MODEL

### ¿Qué es el DOM?

El **DOM (Document Object Model)** es una **representación en árbol** del documento HTML que permite a JavaScript **acceder, modificar, añadir o eliminar** elementos y contenido de la página web.

**Analogía:**
```
HTML es como un árbol genealógico:
- Cada elemento es un nodo
- Los elementos están organizados jerárquicamente
- JavaScript puede "navegar" y modificar este árbol
```

---

### Estructura del DOM

```html
<!DOCTYPE html>
<html>                          ← Raíz del árbol
  <head>                        ← Rama
    <title>Mi Página</title>    ← Hoja
  </head>
  <body>                        ← Rama
    <h1>Título</h1>             ← Hoja
    <p>Párrafo</p>              ← Hoja
    <div id="container">        ← Rama
      <span>Texto</span>        ← Hoja
    </div>
  </body>
</html>
```

**Representación en árbol:**
```
document
  └─ html
      ├─ head
      │   └─ title
      │       └─ "Mi Página"
      └─ body
          ├─ h1
          │   └─ "Título"
          ├─ p
          │   └─ "Párrafo"
          └─ div#container
              └─ span
                  └─ "Texto"
```

---

### Tipos de Nodos en el DOM

| Tipo | Descripción | Ejemplo |
|------|-------------|---------|
| **Element Node** | Elementos HTML | `<div>`, `<p>`, `<h1>` |
| **Text Node** | Contenido de texto | `"Hola mundo"` |
| **Attribute Node** | Atributos de elementos | `id="container"`, `class="btn"` |
| **Comment Node** | Comentarios HTML | `<!-- comentario -->` |
| **Document Node** | Documento completo | `document` |

---

### Seleccionar Elementos del DOM

#### 1. Métodos Clásicos

```javascript
// Por ID (devuelve UN elemento)
const elemento = document.getElementById('miId');

// Por clase (devuelve HTMLCollection)
const elementos = document.getElementsByClassName('miClase');

// Por tag (devuelve HTMLCollection)
const parrafos = document.getElementsByTagName('p');

// Por name (para formularios)
const inputs = document.getElementsByName('email');
```

#### 2. Métodos Modernos (recomendados)

```javascript
// querySelector - Devuelve EL PRIMER elemento que coincida
const elemento = document.querySelector('#miId');        // Por ID
const elemento = document.querySelector('.miClase');     // Por clase
const elemento = document.querySelector('div.container'); // Combinado
const elemento = document.querySelector('[data-id="1"]'); // Por atributo

// querySelectorAll - Devuelve TODOS los elementos (NodeList)
const elementos = document.querySelectorAll('.miClase');
const parrafos = document.querySelectorAll('p');
const items = document.querySelectorAll('li.active');
```

**Ejemplo práctico:**
```html
<div class="container">
    <h1 id="titulo">Bienvenido</h1>
    <p class="texto">Primer párrafo</p>
    <p class="texto">Segundo párrafo</p>
    <button id="btnSubmit" data-action="submit">Enviar</button>
</div>
```

```javascript
// Seleccionar elementos
const titulo = document.querySelector('#titulo');
const primerParrafo = document.querySelector('.texto');
const todosParrafos = document.querySelectorAll('.texto');
const boton = document.querySelector('[data-action="submit"]');

console.log(titulo.textContent);        // "Bienvenido"
console.log(todosParrafos.length);      // 2
```

---

### Manipular Contenido

#### Cambiar texto

```javascript
const titulo = document.querySelector('h1');

// textContent - Solo texto, más rápido
titulo.textContent = 'Nuevo Título';

// innerHTML - Puede incluir HTML (¡cuidado con XSS!)
titulo.innerHTML = 'Título con <strong>negritas</strong>';

// innerText - Texto visible (respeta CSS)
titulo.innerText = 'Texto visible';
```

#### Cambiar atributos

```javascript
const imagen = document.querySelector('img');

// getAttribute / setAttribute
imagen.setAttribute('src', 'nueva-imagen.jpg');
imagen.setAttribute('alt', 'Descripción');
const src = imagen.getAttribute('src');

// Acceso directo a atributos comunes
imagen.src = 'otra-imagen.jpg';
imagen.alt = 'Otra descripción';

// Atributos data-*
const boton = document.querySelector('#btnSubmit');
boton.dataset.action = 'delete';  // data-action="delete"
boton.dataset.userId = '123';     // data-user-id="123"
console.log(boton.dataset.action); // "delete"
```

#### Cambiar estilos CSS

```javascript
const caja = document.querySelector('.box');

// Estilos inline
caja.style.backgroundColor = 'red';
caja.style.color = 'white';
caja.style.padding = '20px';
caja.style.fontSize = '18px';

// Agregar/quitar clases CSS (mejor práctica)
caja.classList.add('active');
caja.classList.remove('hidden');
caja.classList.toggle('visible');  // Alterna la clase
caja.classList.contains('active'); // true/false

// Múltiples clases
caja.classList.add('active', 'highlighted', 'important');
```

**Ejemplo práctico: Toggle de tema oscuro**
```javascript
const btnTema = document.querySelector('#toggleTema');
const body = document.body;

btnTema.addEventListener('click', () => {
    body.classList.toggle('dark-mode');
    
    if (body.classList.contains('dark-mode')) {
        btnTema.textContent = '☀️ Modo Claro';
    } else {
        btnTema.textContent = '🌙 Modo Oscuro';
    }
});
```

---

### Crear y Agregar Elementos

```javascript
// Crear nuevo elemento
const nuevoDiv = document.createElement('div');
const nuevoParrafo = document.createElement('p');
const nuevoBoton = document.createElement('button');

// Agregar contenido
nuevoParrafo.textContent = 'Este es un nuevo párrafo';
nuevoBoton.innerHTML = '<strong>Click aquí</strong>';

// Agregar clases y atributos
nuevoDiv.classList.add('card', 'shadow');
nuevoDiv.id = 'miNuevaCard';
nuevoBoton.setAttribute('type', 'button');

// Insertar en el DOM
const container = document.querySelector('#container');

// appendChild - Agregar al final
container.appendChild(nuevoDiv);

// prepend - Agregar al inicio
container.prepend(nuevoParrafo);

// insertBefore - Insertar antes de un elemento
const referencia = document.querySelector('#referencia');
container.insertBefore(nuevoBoton, referencia);

// insertAdjacentHTML - Insertar HTML en posición específica
container.insertAdjacentHTML('beforeend', '<p>HTML insertado</p>');
// Posiciones: 'beforebegin', 'afterbegin', 'beforeend', 'afterend'
```

**Ejemplo: Agregar items a una lista**
```javascript
const lista = document.querySelector('#miLista');

function agregarItem(texto) {
    const li = document.createElement('li');
    li.textContent = texto;
    li.classList.add('list-item');
    lista.appendChild(li);
}

agregarItem('Primer item');
agregarItem('Segundo item');
agregarItem('Tercer item');
```

---

### Eliminar Elementos

```javascript
const elemento = document.querySelector('#eliminar');

// Método moderno
elemento.remove();

// Método clásico
elemento.parentNode.removeChild(elemento);

// Eliminar todos los hijos
const container = document.querySelector('#container');
container.innerHTML = ''; // Rápido pero pierde event listeners

// Mejor forma (preserva memoria)
while (container.firstChild) {
    container.removeChild(container.firstChild);
}
```

---

### Eventos del DOM

Los eventos permiten responder a acciones del usuario:

```javascript
const boton = document.querySelector('#miBoton');

// addEventListener (recomendado)
boton.addEventListener('click', function(event) {
    console.log('Botón clickeado!');
    console.log('Event:', event);
});

// Con arrow function
boton.addEventListener('click', (e) => {
    console.log('Click!', e.target);
});

// Múltiples eventos en el mismo elemento
boton.addEventListener('mouseenter', () => {
    boton.style.backgroundColor = 'blue';
});

boton.addEventListener('mouseleave', () => {
    boton.style.backgroundColor = '';
});
```

#### Eventos Comunes

```javascript
// Click
elemento.addEventListener('click', handler);

// Doble click
elemento.addEventListener('dblclick', handler);

// Mouse
elemento.addEventListener('mouseenter', handler); // Mouse entra
elemento.addEventListener('mouseleave', handler); // Mouse sale
elemento.addEventListener('mousemove', handler);  // Mouse se mueve

// Teclado
input.addEventListener('keydown', handler);   // Tecla presionada
input.addEventListener('keyup', handler);     // Tecla liberada
input.addEventListener('keypress', handler);  // Tecla presionada (deprecated)

// Formularios
form.addEventListener('submit', handler);
input.addEventListener('change', handler);    // Valor cambia
input.addEventListener('input', handler);     // Entrada de texto
input.addEventListener('focus', handler);     // Input recibe foco
input.addEventListener('blur', handler);      // Input pierde foco

// Ventana
window.addEventListener('load', handler);     // Página cargada
window.addEventListener('resize', handler);   // Ventana redimensionada
window.addEventListener('scroll', handler);   // Scroll
```

**Ejemplo completo: Formulario de login**
```html
<form id="loginForm">
    <input type="email" id="email" placeholder="Email" required>
    <input type="password" id="password" placeholder="Password" required>
    <button type="submit">Ingresar</button>
</form>
<div id="mensaje"></div>
```

```javascript
const form = document.querySelector('#loginForm');
const emailInput = document.querySelector('#email');
const passwordInput = document.querySelector('#password');
const mensaje = document.querySelector('#mensaje');

// Prevenir envío por defecto
form.addEventListener('submit', async (e) => {
    e.preventDefault(); // ¡Importante! Evita recargar la página
    
    // Obtener valores
    const email = emailInput.value.trim();
    const password = passwordInput.value;
    
    // Validar
    if (!email || !password) {
        mensaje.textContent = 'Por favor completa todos los campos';
        mensaje.classList.add('error');
        return;
    }
    
    // Enviar a API
    try {
        const response = await fetch('/api/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ email, password })
        });
        
        const data = await response.json();
        
        if (response.ok) {
            mensaje.textContent = '✅ Login exitoso';
            mensaje.classList.remove('error');
            mensaje.classList.add('success');
            
            // Guardar token
            localStorage.setItem('token', data.token);
            
            // Redirigir
            setTimeout(() => {
                window.location.href = '/dashboard';
            }, 1000);
        } else {
            throw new Error(data.message || 'Credenciales incorrectas');
        }
    } catch (error) {
        mensaje.textContent = '❌ ' + error.message;
        mensaje.classList.add('error');
    }
});

// Validación en tiempo real
emailInput.addEventListener('input', (e) => {
    const email = e.target.value;
    if (email && !email.includes('@')) {
        emailInput.style.borderColor = 'red';
    } else {
        emailInput.style.borderColor = '';
    }
});
```

---

### Event Object

El objeto `event` contiene información sobre el evento:

```javascript
elemento.addEventListener('click', (event) => {
    console.log('Tipo:', event.type);           // 'click'
    console.log('Target:', event.target);       // Elemento clickeado
    console.log('CurrentTarget:', event.currentTarget); // Elemento con listener
    console.log('Posición X:', event.clientX);  // Coordenada X
    console.log('Posición Y:', event.clientY);  // Coordenada Y
    
    // Prevenir comportamiento por defecto
    event.preventDefault();
    
    // Detener propagación (bubbling)
    event.stopPropagation();
});
```

---

### Event Delegation (Delegación de Eventos)

Técnica para manejar eventos en elementos dinámicos:

```javascript
// ❌ MAL: Agregar listener a cada elemento
const botones = document.querySelectorAll('.btn');
botones.forEach(btn => {
    btn.addEventListener('click', () => {
        console.log('Click');
    });
});

// ✅ BIEN: Un listener en el padre
const container = document.querySelector('#container');
container.addEventListener('click', (e) => {
    if (e.target.classList.contains('btn')) {
        console.log('Botón clickeado:', e.target.textContent);
    }
});

// Ahora funciona con botones agregados dinámicamente
const nuevoBtn = document.createElement('button');
nuevoBtn.classList.add('btn');
nuevoBtn.textContent = 'Nuevo botón';
container.appendChild(nuevoBtn); // El listener funciona automáticamente
```

---

### Traversing (Navegar el DOM)

```javascript
const elemento = document.querySelector('#miElemento');

// Padres
elemento.parentNode           // Nodo padre
elemento.parentElement        // Elemento padre
elemento.closest('.container') // Ancestro más cercano con selector

// Hijos
elemento.children             // HTMLCollection de hijos (solo elementos)
elemento.childNodes           // NodeList de todos los nodos (incluye texto)
elemento.firstElementChild    // Primer hijo (elemento)
elemento.lastElementChild     // Último hijo (elemento)

// Hermanos
elemento.nextElementSibling   // Siguiente hermano
elemento.previousElementSibling // Hermano anterior

// Ejemplo práctico
const li = document.querySelector('.active');
const lista = li.closest('ul');           // Encontrar lista padre
const siguiente = li.nextElementSibling;  // Siguiente item
const anterior = li.previousElementSibling; // Item anterior
```

---

### Ejemplo Real: Lista de Tareas (TODO App)

```html
<!DOCTYPE html>
<html>
<head>
    <title>TODO App</title>
    <style>
        .completed { text-decoration: line-through; opacity: 0.5; }
        .task { padding: 10px; border: 1px solid #ddd; margin: 5px 0; }
    </style>
</head>
<body>
    <div id="app">
        <h1>Lista de Tareas</h1>
        <form id="taskForm">
            <input type="text" id="taskInput" placeholder="Nueva tarea">
            <button type="submit">Agregar</button>
        </form>
        <ul id="taskList"></ul>
    </div>

    <script>
        const form = document.querySelector('#taskForm');
        const input = document.querySelector('#taskInput');
        const taskList = document.querySelector('#taskList');

        // Agregar tarea
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            
            const texto = input.value.trim();
            if (!texto) return;
            
            agregarTarea(texto);
            input.value = '';
        });

        function agregarTarea(texto) {
            // Crear elementos
            const li = document.createElement('li');
            li.classList.add('task');
            
            const span = document.createElement('span');
            span.textContent = texto;
            
            const btnCompletar = document.createElement('button');
            btnCompletar.textContent = '✓';
            btnCompletar.style.marginLeft = '10px';
            
            const btnEliminar = document.createElement('button');
            btnEliminar.textContent = '✗';
            btnEliminar.style.marginLeft = '5px';
            
            // Ensamblar
            li.appendChild(span);
            li.appendChild(btnCompletar);
            li.appendChild(btnEliminar);
            taskList.appendChild(li);
        }

        // Event delegation para botones
        taskList.addEventListener('click', (e) => {
            const target = e.target;
            const li = target.closest('.task');
            
            if (target.textContent === '✓') {
                // Completar tarea
                li.classList.toggle('completed');
            } else if (target.textContent === '✗') {
                // Eliminar tarea
                li.remove();
            }
        });
    </script>
</body>
</html>
```

---

### Integración DOM + Promesas + Laravel API

Ejemplo completo de CRUD con fetch y DOM:

```javascript
// Cargar posts desde API
async function cargarPosts() {
    const container = document.querySelector('#postsContainer');
    
    try {
        // Mostrar loading
        container.innerHTML = '<p>Cargando posts...</p>';
        
        // Fetch desde Laravel API
        const response = await fetch('/api/posts');
        const data = await response.json();
        const posts = data.data;
        
        // Limpiar container
        container.innerHTML = '';
        
        // Crear elementos para cada post
        posts.forEach(post => {
            const article = document.createElement('article');
            article.classList.add('post');
            article.dataset.postId = post.id;
            
            article.innerHTML = `
                <h2>${post.title}</h2>
                <p>${post.content}</p>
                <small>Por: ${post.user.name}</small>
                <button class="btn-delete" data-id="${post.id}">Eliminar</button>
            `;
            
            container.appendChild(article);
        });
        
    } catch (error) {
        container.innerHTML = `<p class="error">Error: ${error.message}</p>`;
    }
}

// Eliminar post
document.addEventListener('click', async (e) => {
    if (e.target.classList.contains('btn-delete')) {
        const postId = e.target.dataset.id;
        
        if (!confirm('¿Eliminar este post?')) return;
        
        try {
            const response = await fetch(`/api/posts/${postId}`, {
                method: 'DELETE',
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('token')}`
                }
            });
            
            if (response.ok) {
                // Eliminar del DOM
                const article = e.target.closest('.post');
                article.remove();
            } else {
                throw new Error('No se pudo eliminar');
            }
        } catch (error) {
            alert('Error: ' + error.message);
        }
    }
});

// Cargar al inicio
document.addEventListener('DOMContentLoaded', cargarPosts);
```

---

### Resumen: DOM

**¿Qué es?** Representación en árbol del documento HTML que permite manipularlo con JavaScript.

**Seleccionar elementos:**
```javascript
document.querySelector('#id')       // Un elemento
document.querySelectorAll('.class') // Múltiples elementos
```

**Manipular:**
```javascript
elemento.textContent = 'texto'
elemento.innerHTML = '<b>HTML</b>'
elemento.style.color = 'red'
elemento.classList.add('active')
```

**Crear y agregar:**
```javascript
const div = document.createElement('div')
div.textContent = 'Contenido'
container.appendChild(div)
```

**Eventos:**
```javascript
elemento.addEventListener('click', (e) => {
    console.log('Click!', e.target)
})
```

**Navegar:**
```javascript
elemento.parentElement          // Padre
elemento.children               // Hijos
elemento.nextElementSibling     // Hermano siguiente
```

---

**¡Buena suerte en tu parcial! 🚀**

### 📌 Recursos Adicionales del Proyecto

- **Tutoriales completos**: Carpeta `/tutoriales`
- **Historias de usuario**: `Historias Difexa.md`
- **Documentación técnica**: Carpeta `/docs`
- **Scripts de automatización**: `/migrate.sh`, `/mainsync.sh`
