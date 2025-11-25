# 📝 PARCIAL - DESARROLLO CORTO (5 PREGUNTAS)

**Instrucciones:** Responde de forma clara y concisa cada pregunta. Cada pregunta vale 3 puntos (Total: 15 puntos).

---

## PREGUNTA 1: Diferencia entre Migraciones y Seeders (3 puntos)

**Explica cuál es la diferencia principal entre las Migraciones y los Seeders en Laravel. Menciona para qué sirve cada uno y cuándo se utilizan.**

### RESPUESTA ESPERADA:

Las **Migraciones** definen la **estructura de la base de datos** (tablas, columnas, índices, relaciones). Son como "control de versiones" para el esquema de la base de datos y se ejecutan con `php artisan migrate`.

Los **Seeders** sirven para **poblar las tablas con datos iniciales** o de prueba. Insertan registros en la base de datos y se ejecutan con `php artisan db:seed`.

**Cuándo usar cada uno:**
- **Migraciones**: Al crear/modificar/eliminar tablas o columnas
- **Seeders**: Al necesitar datos iniciales, datos de prueba, o restaurar información

**Ejemplo:**
```php
// Migración: crear estructura
Schema::create('products', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->decimal('price', 10, 2);
});

// Seeder: insertar datos
Product::create([
    'name' => 'Laptop HP',
    'price' => 999.99
]);
```

**Puntos clave para evaluación (3 pts):**
- ✅ Explica que migraciones = estructura (1 pt)
- ✅ Explica que seeders = datos (1 pt)
- ✅ Menciona comandos artisan correspondientes (1 pt)

---

## PREGUNTA 2: Relación 1:N vs N:M (3 puntos)

**Explica la diferencia entre una relación 1:N (uno a muchos) y una relación N:M (muchos a muchos) en Laravel. Proporciona un ejemplo de cada una.**

### RESPUESTA ESPERADA:

**Relación 1:N (Uno a Muchos):**
Un registro de una tabla puede tener **múltiples registros relacionados** en otra tabla, pero cada registro relacionado pertenece a **solo uno** en la primera tabla. Se implementa con `hasMany` y `belongsTo`.

**Ejemplo:** Un `User` puede tener muchos `Posts`, pero cada `Post` pertenece a un solo `User`.

```php
// User
public function posts() {
    return $this->hasMany(Post::class);
}

// Post
public function user() {
    return $this->belongsTo(User::class);
}
```

**Relación N:M (Muchos a Muchos):**
Múltiples registros de una tabla pueden relacionarse con **múltiples registros** de otra tabla. Requiere una **tabla intermedia (pivote)** y se implementa con `belongsToMany`.

**Ejemplo:** Un `Post` puede tener muchos `Tags`, y un `Tag` puede estar en muchos `Posts`.

```php
// Post
public function tags() {
    return $this->belongsToMany(Tag::class);
}

// Tag
public function posts() {
    return $this->belongsToMany(Post::class);
}

// Tabla pivote: post_tag (post_id, tag_id)
```

**Puntos clave para evaluación (3 pts):**
- ✅ Explica correctamente 1:N con ejemplo (1.5 pts)
- ✅ Explica correctamente N:M con tabla pivote (1.5 pts)

---

## PREGUNTA 3: ¿Qué es un Enum y para qué sirve? (3 puntos)

**Explica qué es un Enum en PHP 8.1+ y Laravel. Menciona sus ventajas sobre el uso de strings o constantes. Proporciona un ejemplo de uso.**

### RESPUESTA ESPERADA:

Un **Enum** (Enumeración) es un tipo de dato especial introducido en PHP 8.1 que representa un **conjunto fijo de valores posibles**. Garantiza que una variable solo pueda tener uno de los valores predefinidos.

**Ventajas sobre strings o constantes:**
1. **Type Safety**: Previene errores de tipeo (no acepta valores inválidos)
2. **Autocompletado**: Los IDEs sugieren los valores posibles
3. **Legibilidad**: Código más claro y mantenible
4. **Métodos propios**: Pueden incluir lógica adicional

**Ejemplo:**
```php
enum OrderStatus: string
{
    case PENDING = 'pending';
    case PROCESSING = 'processing';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';

    public function color(): string
    {
        return match($this) {
            self::PENDING => 'yellow',
            self::PROCESSING => 'blue',
            self::COMPLETED => 'green',
            self::CANCELLED => 'red',
        };
    }
}

// Uso
$order->status = OrderStatus::PENDING;  // ✅ Válido
$order->status = 'invalid';             // ❌ Error de tipo
echo $order->status->color();           // "yellow"
```

**Comparación:**
```php
// ❌ Antes (strings): propenso a errores
$order->status = 'pendingg';  // Typo no detectado

// ✅ Ahora (Enum): error detectado en tiempo de desarrollo
$order->status = OrderStatus::PENDING;
```

**Puntos clave para evaluación (3 pts):**
- ✅ Define correctamente qué es un Enum (1 pt)
- ✅ Menciona ventajas (type safety, autocompletado) (1 pt)
- ✅ Proporciona ejemplo válido (1 pt)

---

## PREGUNTA 4: API REST - Métodos HTTP y Códigos de Respuesta (3 puntos)

**Explica qué método HTTP (GET, POST, PUT, DELETE) se debe usar para cada operación CRUD. Además, menciona qué código de respuesta HTTP se debe retornar en caso de éxito para cada operación.**

### RESPUESTA ESPERADA:

| Operación CRUD | Método HTTP | Código HTTP (Éxito) | Descripción |
|----------------|-------------|---------------------|-------------|
| **Create** | POST | `201 Created` | Crear un nuevo recurso |
| **Read** (listar) | GET | `200 OK` | Obtener lista de recursos |
| **Read** (uno) | GET | `200 OK` | Obtener un recurso específico |
| **Update** | PUT/PATCH | `200 OK` | Actualizar un recurso existente |
| **Delete** | DELETE | `204 No Content` | Eliminar un recurso |

**Ejemplo de rutas API:**
```php
Route::get('/products', [ProductController::class, 'index']);        // 200 OK
Route::post('/products', [ProductController::class, 'store']);       // 201 Created
Route::get('/products/{id}', [ProductController::class, 'show']);    // 200 OK
Route::put('/products/{id}', [ProductController::class, 'update']);  // 200 OK
Route::delete('/products/{id}', [ProductController::class, 'destroy']); // 204 No Content
```

**Otros códigos HTTP importantes:**
- `400 Bad Request`: Error de validación
- `404 Not Found`: Recurso no encontrado
- `500 Internal Server Error`: Error del servidor

**Puntos clave para evaluación (3 pts):**
- ✅ Menciona correctamente métodos HTTP para CRUD (1.5 pts)
- ✅ Especifica códigos de respuesta correctos (1.5 pts)

---

## PREGUNTA 5: Comandos Artisan Esenciales (3 puntos)

**Enumera y explica brevemente para qué sirven 6 comandos de Artisan que consideres esenciales en el desarrollo con Laravel.**

### RESPUESTA ESPERADA:

1. **`php artisan migrate`**
   - Ejecuta todas las migraciones pendientes
   - Crea/modifica la estructura de la base de datos

2. **`php artisan migrate:fresh --seed`**
   - Elimina todas las tablas y las recrea
   - Ejecuta los seeders para poblar con datos
   - Útil para reiniciar la BD en desarrollo

3. **`php artisan db:seed`**
   - Ejecuta los seeders para poblar tablas con datos
   - Puede especificar un seeder: `--class=UserSeeder`

4. **`php artisan make:model Product -mfs`**
   - Crea un modelo con sus archivos asociados:
     - `-m`: Migración
     - `-f`: Factory
     - `-s`: Seeder

5. **`php artisan make:controller ProductController --api`**
   - Crea un controlador con métodos para API REST
   - Incluye: index, store, show, update, destroy

6. **`php artisan route:list`**
   - Lista todas las rutas registradas en la aplicación
   - Muestra: método HTTP, URI, nombre, controlador

**Otros comandos útiles:**
- `php artisan tinker`: Interactuar con la aplicación en consola
- `php artisan serve`: Iniciar servidor de desarrollo
- `php artisan make:seeder`: Crear un nuevo seeder
- `php artisan migrate:rollback`: Revertir última migración

**Puntos clave para evaluación (3 pts):**
- ✅ Lista 6 comandos correctos (1.5 pts)
- ✅ Explica correctamente para qué sirve cada uno (1.5 pts)

---

## CRITERIOS DE EVALUACIÓN GENERALES

### Excelente (3 puntos)
- Respuesta completa y precisa
- Incluye ejemplos de código correctos
- Demuestra comprensión profunda del concepto

### Bueno (2 puntos)
- Respuesta correcta pero incompleta
- Ejemplos básicos o con errores menores
- Comprensión adecuada del concepto

### Regular (1 punto)
- Respuesta parcialmente correcta
- Conceptos confusos o mezclados
- Ejemplos incorrectos o faltantes

### Insuficiente (0 puntos)
- Respuesta incorrecta o no relacionada
- No demuestra comprensión del tema

---

## RESUMEN DE CONCEPTOS POR PREGUNTA

| # | Tema Principal | Conceptos Clave |
|---|----------------|-----------------|
| 1 | Migraciones vs Seeders | `migrate`, `db:seed`, estructura vs datos |
| 2 | Relaciones Eloquent | `hasMany`, `belongsTo`, `belongsToMany`, tabla pivote |
| 3 | Enums PHP 8.1+ | Type safety, valores fijos, `match`, ventajas |
| 4 | API REST | GET, POST, PUT, DELETE, códigos 200/201/204 |
| 5 | Comandos Artisan | `migrate`, `make:model`, `db:seed`, `route:list` |

---

**Total: 5 preguntas × 3 puntos = 15 puntos**
