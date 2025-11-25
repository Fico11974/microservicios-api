# ✅❌ PARCIAL - VERDADERO/FALSO (30 PREGUNTAS)

**Instrucciones:** Indica si cada afirmación es Verdadera (V) o Falsa (F). Cada pregunta vale ~0.33 puntos (Total: 10 puntos).

---

## SECCIÓN 1: MIGRACIONES

### Pregunta 1
Las migraciones permiten versionar la estructura de la base de datos.

**Respuesta: VERDADERO**

---

### Pregunta 2
El método `down()` en una migración se ejecuta automáticamente cuando hay un error.

**Respuesta: FALSO** (Se ejecuta con `migrate:rollback`, no automáticamente)

---

### Pregunta 3
`php artisan migrate:fresh` ejecuta las migraciones sin eliminar datos existentes.

**Respuesta: FALSO** (Elimina todas las tablas primero)

---

### Pregunta 4
En una migración, `$table->timestamps()` crea las columnas `created_at` y `updated_at`.

**Respuesta: VERDADERO**

---

### Pregunta 5
Una migración puede crear múltiples tablas en el mismo archivo.

**Respuesta: VERDADERO** (Aunque no es recomendado)

---

## SECCIÓN 2: MODELOS ELOQUENT

### Pregunta 6
Por convención, un modelo llamado `User` buscará la tabla `user` en la base de datos.

**Respuesta: FALSO** (Buscará `users` en plural)

---

### Pregunta 7
La propiedad `$fillable` protege contra asignación masiva definiendo campos permitidos.

**Respuesta: VERDADERO**

---

### Pregunta 8
`Model::find(1)` y `Model::findOrFail(1)` devuelven lo mismo si el registro existe.

**Respuesta: VERDADERO**

---

### Pregunta 9
Es obligatorio definir `$fillable` o `$guarded` en todos los modelos.

**Respuesta: FALSO** (No es obligatorio, pero es una buena práctica de seguridad)

---

### Pregunta 10
El método `create()` requiere que el modelo tenga definido `$fillable` o `$guarded`.

**Respuesta: VERDADERO** (Para protección contra asignación masiva)

---

### Pregunta 11
`$product->save()` puede usarse tanto para crear como para actualizar registros.

**Respuesta: VERDADERO**

---

### Pregunta 12
La propiedad `$hidden` oculta campos de las respuestas JSON automáticamente.

**Respuesta: VERDADERO**

---

## SECCIÓN 3: RELACIONES

### Pregunta 13
En una relación 1:N, el método en el modelo "uno" se llama `hasMany()`.

**Respuesta: VERDADERO**

---

### Pregunta 14
En una relación N:M, es necesaria una tabla pivote.

**Respuesta: VERDADERO**

---

### Pregunta 15
El método `belongsTo()` siempre va en el modelo que tiene la clave foránea.

**Respuesta: VERDADERO**

---

### Pregunta 16
En una relación N:M, el nombre de la tabla pivote debe ser singular.

**Respuesta: FALSO** (Debe ser plural y en orden alfabético: `post_user`, no `user_post`)

---

### Pregunta 17
El método `with()` se usa para hacer eager loading y evitar el problema N+1.

**Respuesta: VERDADERO**

---

## SECCIÓN 4: SEEDERS

### Pregunta 18
Los seeders solo pueden crear datos usando Eloquent.

**Respuesta: FALSO** (También pueden usar DB facade, SQL raw, factories, etc.)

---

### Pregunta 19
`Model::truncate()` elimina todos los registros y resetea el auto-increment.

**Respuesta: VERDADERO**

---

### Pregunta 20
Los seeders se ejecutan automáticamente después de cada migración.

**Respuesta: FALSO** (Deben ejecutarse explícitamente o con --seed)

---

### Pregunta 21
El método `run()` es obligatorio en todo seeder.

**Respuesta: VERDADERO**

---

## SECCIÓN 5: RUTAS Y CONTROLADORES

### Pregunta 22
Las rutas en `routes/api.php` tienen automáticamente el prefijo `/api`.

**Respuesta: VERDADERO**

---

### Pregunta 23
Un controlador resource tiene 7 métodos predefinidos por convención REST.

**Respuesta: VERDADERO** (index, create, store, show, edit, update, destroy)

---

### Pregunta 24
`Route::resource()` crea automáticamente todas las rutas REST para un controlador.

**Respuesta: VERDADERO**

---

### Pregunta 25
El método `store()` en un controlador resource maneja peticiones POST.

**Respuesta: VERDADERO**

---

## SECCIÓN 6: API REST

### Pregunta 26
El código HTTP 404 indica que un recurso no fue encontrado.

**Respuesta: VERDADERO**

---

### Pregunta 27
Una API REST debe usar siempre el método POST para todas las operaciones.

**Respuesta: FALSO** (Debe usar GET, POST, PUT/PATCH, DELETE según la operación)

---

### Pregunta 28
El código HTTP 201 indica que un recurso fue creado exitosamente.

**Respuesta: VERDADERO**

---

### Pregunta 29
En REST, el método PUT se usa para actualizaciones parciales.

**Respuesta: FALSO** (PUT es para actualización completa, PATCH para parcial)

---

### Pregunta 30
`response()->json()` devuelve automáticamente el header `Content-Type: application/json`.

**Respuesta: VERDADERO**

---

## HOJA DE RESPUESTAS RÁPIDAS

```
1.  V     11. V     21. V
2.  F     12. V     22. V
3.  F     13. V     23. V
4.  V     14. V     24. V
5.  V     15. V     25. V
6.  F     16. F     26. V
7.  V     17. V     27. F
8.  V     18. F     28. V
9.  F     19. V     29. F
10. V     20. F     30. V
```

---

## JUSTIFICACIONES PARA RESPUESTAS FALSAS

**Pregunta 2:** El método `down()` solo se ejecuta cuando se hace rollback manual con `php artisan migrate:rollback`.

**Pregunta 3:** `migrate:fresh` elimina TODAS las tablas antes de ejecutar las migraciones, perdiendo todos los datos.

**Pregunta 6:** Por convención Eloquent pluraliza: Modelo `User` → tabla `users`.

**Pregunta 9:** No es obligatorio, pero sin definir alguno de los dos, no se puede usar asignación masiva (`create()`, `update()`).

**Pregunta 16:** La tabla pivote debe ser plural y en orden alfabético: `channel_post` (no `post_channel`).

**Pregunta 18:** También pueden usar `DB::table()`, `DB::insert()`, factories, e incluso SQL raw.

**Pregunta 20:** Los seeders se ejecutan solo con `php artisan db:seed` o con flag `--seed`.

**Pregunta 27:** REST usa diferentes métodos HTTP: GET (leer), POST (crear), PUT/PATCH (actualizar), DELETE (eliminar).

**Pregunta 29:** PUT actualiza el recurso completo, PATCH solo los campos especificados.

---

**Total: 30 preguntas × 0.33 puntos ≈ 10 puntos**
