# 📝 PARCIAL - OPCIÓN MÚLTIPLE (50 PREGUNTAS)

**Instrucciones:** Selecciona la respuesta correcta para cada pregunta. Cada pregunta vale 0.8 puntos (Total: 40 puntos).

---

## SECCIÓN 1: MIGRACIONES Y BASE DE DATOS

### Pregunta 1
¿Qué comando se utiliza para crear una nueva migración en Laravel?
- A) `php artisan migration:create`
- B) `php artisan make:migration`
- C) `php artisan create:migration`
- D) `php artisan new:migration`

**Respuesta correcta: B**

---

### Pregunta 2
¿Cuál es la convención de nombres para una migración que crea la tabla `products`?
- A) `create_product_table`
- B) `products_table`
- C) `create_products_table`
- D) `new_products_table`

**Respuesta correcta: C**

---

### Pregunta 3
¿Qué método se usa en una migración para definir una columna de tipo string?
- A) `$table->text('name')`
- B) `$table->varchar('name')`
- C) `$table->string('name')`
- D) `$table->char('name')`

**Respuesta correcta: C**

---

### Pregunta 4
¿Cómo se define una columna que puede ser NULL en una migración?
- A) `$table->string('email')->null()`
- B) `$table->string('email')->nullable()`
- C) `$table->string('email')->allowNull()`
- D) `$table->string('email')->canBeNull()`

**Respuesta correcta: B**

---

### Pregunta 5
¿Qué comando ejecuta todas las migraciones pendientes?
- A) `php artisan migrate:run`
- B) `php artisan migrate:execute`
- C) `php artisan migrate`
- D) `php artisan db:migrate`

**Respuesta correcta: C**

---

### Pregunta 6
¿Qué hace el comando `php artisan migrate:fresh`?
- A) Ejecuta solo las nuevas migraciones
- B) Revierte la última migración
- C) Elimina todas las tablas y vuelve a ejecutar todas las migraciones
- D) Refresca solo la última migración

**Respuesta correcta: C**

---

### Pregunta 7
¿Cómo se define una clave foránea en una migración?
- A) `$table->foreign('user_id')`
- B) `$table->foreignKey('user_id')`
- C) `$table->foreignId('user_id')->constrained()`
- D) `$table->reference('user_id')`

**Respuesta correcta: C**

---

### Pregunta 8
¿Qué tipo de dato se usa para definir un ENUM en una migración?
- A) `$table->enum('status', ['active', 'inactive'])`
- B) `$table->set('status', ['active', 'inactive'])`
- C) `$table->list('status', ['active', 'inactive'])`
- D) `$table->options('status', ['active', 'inactive'])`

**Respuesta correcta: A**

---

### Pregunta 9
¿Qué método añade las columnas `created_at` y `updated_at` automáticamente?
- A) `$table->dates()`
- B) `$table->timestamps()`
- C) `$table->datetime()`
- D) `$table->createdUpdated()`

**Respuesta correcta: B**

---

### Pregunta 10
¿Cómo se revierte la última migración ejecutada?
- A) `php artisan migrate:undo`
- B) `php artisan migrate:back`
- C) `php artisan migrate:rollback`
- D) `php artisan migrate:revert`

**Respuesta correcta: C**

---

## SECCIÓN 2: MODELOS ELOQUENT

### Pregunta 11
¿Qué comando crea un modelo llamado `Post`?
- A) `php artisan create:model Post`
- B) `php artisan make:model Post`
- C) `php artisan new:model Post`
- D) `php artisan model:create Post`

**Respuesta correcta: B**

---

### Pregunta 12
¿Qué propiedad del modelo define los campos que pueden ser asignados masivamente?
- A) `$protected`
- B) `$fillable`
- C) `$guarded`
- D) `$allowed`

**Respuesta correcta: B**

---

### Pregunta 13
Por convención, ¿qué nombre de tabla busca Eloquent para el modelo `Product`?
- A) `product`
- B) `Product`
- C) `products`
- D) `Products`

**Respuesta correcta: C**

---

### Pregunta 14
¿Cómo se obtienen todos los registros de un modelo?
- A) `Product::getAll()`
- B) `Product::findAll()`
- C) `Product::all()`
- D) `Product::select()`

**Respuesta correcta: C**

---

### Pregunta 15
¿Qué método encuentra un registro por su ID?
- A) `Product::get(1)`
- B) `Product::find(1)`
- C) `Product::findById(1)`
- D) `Product::where('id', 1)`

**Respuesta correcta: B**

---

### Pregunta 16
¿Cuál es la diferencia entre `find()` y `findOrFail()`?
- A) No hay diferencia
- B) `findOrFail()` lanza una excepción si no encuentra el registro
- C) `find()` es más rápido
- D) `findOrFail()` devuelve null si no encuentra el registro

**Respuesta correcta: B**

---

### Pregunta 17
¿Qué método se usa para crear un nuevo registro en la base de datos?
- A) `Product::insert([...])`
- B) `Product::new([...])`
- C) `Product::create([...])`
- D) `Product::add([...])`

**Respuesta correcta: C**

---

### Pregunta 18
¿Cómo se actualiza un registro existente?
- A) `$product->save()`
- B) `$product->update([...])`
- C) Ambas A y B son correctas
- D) `$product->modify([...])`

**Respuesta correcta: C**

---

### Pregunta 19
¿Qué método elimina un registro del modelo?
- A) `$product->remove()`
- B) `$product->destroy()`
- C) `$product->delete()`
- D) `$product->erase()`

**Respuesta correcta: C**

---

### Pregunta 20
¿Qué propiedad define los campos que NO pueden ser asignados masivamente?
- A) `$fillable`
- B) `$protected`
- C) `$guarded`
- D) `$hidden`

**Respuesta correcta: C**

---

## SECCIÓN 3: SEEDERS

### Pregunta 21
¿Qué comando crea un nuevo seeder?
- A) `php artisan create:seeder UserSeeder`
- B) `php artisan make:seeder UserSeeder`
- C) `php artisan new:seeder UserSeeder`
- D) `php artisan seeder:make UserSeeder`

**Respuesta correcta: B**

---

### Pregunta 22
¿Cómo se ejecuta un seeder específico?
- A) `php artisan db:seed UserSeeder`
- B) `php artisan seed UserSeeder`
- C) `php artisan db:seed --class=UserSeeder`
- D) `php artisan run:seeder UserSeeder`

**Respuesta correcta: C**

---

### Pregunta 23
¿En qué método se coloca el código de un seeder?
- A) `execute()`
- B) `run()`
- C) `seed()`
- D) `populate()`

**Respuesta correcta: B**

---

### Pregunta 24
¿Qué archivo se usa para orquestar múltiples seeders?
- A) `MainSeeder.php`
- B) `MasterSeeder.php`
- C) `DatabaseSeeder.php`
- D) `RootSeeder.php`

**Respuesta correcta: C**

---

### Pregunta 25
¿Cómo se llama a otros seeders desde `DatabaseSeeder`?
- A) `$this->seed([UserSeeder::class])`
- B) `$this->call([UserSeeder::class])`
- C) `$this->run([UserSeeder::class])`
- D) `$this->execute([UserSeeder::class])`

**Respuesta correcta: B**

---

### Pregunta 26
¿Qué comando ejecuta las migraciones y seeders juntos?
- A) `php artisan migrate --seed`
- B) `php artisan migrate:fresh --seed`
- C) Ambas A y B son correctas
- D) `php artisan db:setup`

**Respuesta correcta: C**

---

### Pregunta 27
¿Qué se recomienda hacer al inicio de un seeder para asegurar idempotencia?
- A) `Model::delete()`
- B) `Model::truncate()`
- C) `Model::clear()`
- D) `Model::reset()`

**Respuesta correcta: B**

---

### Pregunta 28
¿Cómo se usa un Factory dentro de un Seeder?
- A) `User::factory()->create()`
- B) `UserFactory::create()`
- C) `Factory::make('User')`
- D) `factory(User::class)->create()`

**Respuesta correcta: A**

---

## SECCIÓN 4: ENUMS EN PHP 8.1+

### Pregunta 29
¿En qué versión de PHP se introdujeron los Enums nativos?
- A) PHP 7.4
- B) PHP 8.0
- C) PHP 8.1
- D) PHP 8.2

**Respuesta correcta: C**

---

### Pregunta 30
¿Cómo se declara un Enum básico en PHP?
- A) `class Status extends Enum`
- B) `enum Status`
- C) `enum Status: string`
- D) Ambas B y C son correctas

**Respuesta correcta: D**

---

### Pregunta 31
¿Qué tipo de Enum tiene valores asociados (string o int)?
- A) Simple Enum
- B) Backed Enum
- C) Valued Enum
- D) Associated Enum

**Respuesta correcta: B**

---

### Pregunta 32
¿Cómo se accede a un caso específico de un Enum?
- A) `Status->ACTIVE`
- B) `Status['ACTIVE']`
- C) `Status::ACTIVE`
- D) `Status.ACTIVE`

**Respuesta correcta: C**

---

### Pregunta 33
¿Cómo se define un Enum con valores string en una migración?
- A) `$table->enum('status', Status::cases())`
- B) `$table->enum('status', ['draft', 'published'])`
- C) `$table->string('status')->enum(Status::class)`
- D) `$table->enumType('status', Status::class)`

**Respuesta correcta: B**

---

### Pregunta 34
¿Qué propiedad del modelo se usa para castear un campo a Enum?
- A) `$types`
- B) `$enums`
- C) `$casts`
- D) `$converts`

**Respuesta correcta: C**

---

## SECCIÓN 5: RUTAS Y CONTROLADORES

### Pregunta 35
¿Qué archivo contiene las rutas de la API en Laravel?
- A) `routes/web.php`
- B) `routes/api.php`
- C) `routes/routes.php`
- D) `app/routes.php`

**Respuesta correcta: B**

---

### Pregunta 36
¿Cómo se define una ruta GET básica?
- A) `Route::get('/posts', function() {...})`
- B) `Route::GET('/posts', function() {...})`
- C) `Route->get('/posts', function() {...})`
- D) `get('/posts', function() {...})`

**Respuesta correcta: A**

---

### Pregunta 37
¿Qué comando crea un controlador con métodos REST?
- A) `php artisan make:controller PostController`
- B) `php artisan make:controller PostController --api`
- C) `php artisan make:controller PostController --resource`
- D) Ambas B y C son correctas

**Respuesta correcta: D**

---

### Pregunta 38
¿Cómo se define una ruta que apunta a un controlador?
- A) `Route::get('/posts', 'PostController@index')`
- B) `Route::get('/posts', [PostController::class, 'index'])`
- C) `Route::get('/posts', PostController::index)`
- D) `Route::get('/posts', 'PostController::index')`

**Respuesta correcta: B**

---

### Pregunta 39
¿Qué comando lista todas las rutas registradas?
- A) `php artisan routes`
- B) `php artisan route:list`
- C) `php artisan list:routes`
- D) `php artisan show:routes`

**Respuesta correcta: B**

---

### Pregunta 40
¿Cómo se define un grupo de rutas con un prefijo?
- A) `Route::prefix('api')->group(function() {...})`
- B) `Route::group(['prefix' => 'api'], function() {...})`
- C) Ambas A y B son correctas
- D) `Route::withPrefix('api')->group(function() {...})`

**Respuesta correcta: C**

---

### Pregunta 41
¿Qué método HTTP se usa para crear un nuevo recurso en REST?
- A) GET
- B) PUT
- C) POST
- D) PATCH

**Respuesta correcta: C**

---

### Pregunta 42
¿Qué método define todas las rutas REST automáticamente?
- A) `Route::rest('posts', PostController::class)`
- B) `Route::resource('posts', PostController::class)`
- C) `Route::crud('posts', PostController::class)`
- D) `Route::api('posts', PostController::class)`

**Respuesta correcta: B**

---

## SECCIÓN 6: API REST Y JSON

### Pregunta 43
¿Qué código de estado HTTP indica que un recurso fue creado exitosamente?
- A) 200
- B) 201
- C) 202
- D) 204

**Respuesta correcta: B**

---

### Pregunta 44
¿Cómo se devuelve una respuesta JSON en Laravel?
- A) `return json($data)`
- B) `return response()->json($data)`
- C) `return Response::json($data)`
- D) Ambas B y C son correctas

**Respuesta correcta: B**

---

### Pregunta 45
¿Qué código de estado indica un error de validación?
- A) 400
- B) 404
- C) 422
- D) 500

**Respuesta correcta: C**

---

### Pregunta 46
¿Qué encabezado se debe enviar para indicar que se espera JSON?
- A) `Content-Type: application/json`
- B) `Accept: application/json`
- C) Ambos son correctos
- D) `Response-Type: json`

**Respuesta correcta: C**

---

### Pregunta 47
¿Qué método HTTP se usa para actualizar un recurso completamente?
- A) POST
- B) PUT
- C) PATCH
- D) UPDATE

**Respuesta correcta: B**

---

### Pregunta 48
¿Qué método HTTP se usa para eliminar un recurso?
- A) REMOVE
- B) DELETE
- C) DESTROY
- D) DROP

**Respuesta correcta: B**

---

## SECCIÓN 7: COMANDOS ARTISAN

### Pregunta 49
¿Qué hace el comando `php artisan tinker`?
- A) Ejecuta tests
- B) Abre una consola interactiva de PHP con Laravel cargado
- C) Limpia la caché
- D) Optimiza la aplicación

**Respuesta correcta: B**

---

### Pregunta 50
¿Qué comando crea un modelo, migración y factory juntos?
- A) `php artisan make:model Post -mf`
- B) `php artisan make:model Post --all`
- C) `php artisan make:model Post -a`
- D) Ambas A y C son correctas

**Respuesta correcta: D**

---

## HOJA DE RESPUESTAS RÁPIDAS

```
1.  B    11. B    21. B    31. B    41. C
2.  C    12. B    22. C    32. C    42. B
3.  C    13. C    23. B    33. B    43. B
4.  B    14. C    24. C    34. C    44. B
5.  C    15. B    25. B    35. B    45. C
6.  C    16. B    26. C    36. A    46. C
7.  C    17. C    27. B    37. D    47. B
8.  A    18. C    28. A    38. B    48. B
9.  B    19. C    29. C    39. B    49. B
10. C    20. C    30. D    40. C    50. D
```

---

**Total: 50 preguntas × 0.8 puntos = 40 puntos**
