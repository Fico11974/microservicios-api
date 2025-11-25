<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Customer;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpiar tabla (idempotencia)
        Customer::truncate();

        // Crear 20 clientes con direcciones completas
        Customer::factory()->count(20)->create();

        // Crear 5 clientes sin dirección
        Customer::factory()->count(5)->withoutAddress()->create();

        // Crear 10 clientes de Madrid
        Customer::factory()->count(10)->fromMadrid()->create();

        // Crear 5 clientes internacionales
        Customer::factory()->count(5)->international()->create();

        // Crear algunos clientes específicos para testing
        Customer::create([
            'name' => 'Juan Pérez',
            'email' => 'juan.perez@example.com',
            'phone' => '666 123 456',
            'address' => 'Calle Mayor 123',
            'city' => 'Madrid',
            'postal_code' => '28013',
            'country' => 'España',
        ]);

        Customer::create([
            'name' => 'María García',
            'email' => 'maria.garcia@example.com',
            'phone' => '677 234 567',
            'address' => 'Avenida Diagonal 456',
            'city' => 'Barcelona',
            'postal_code' => '08008',
            'country' => 'España',
        ]);

        Customer::create([
            'name' => 'Carlos López',
            'email' => 'carlos.lopez@example.com',
            'phone' => '688 345 678',
            'address' => null,
            'city' => null,
            'postal_code' => null,
            'country' => 'España',
        ]);

        echo "✅ Se han creado " . Customer::count() . " clientes\n";
        echo "📊 Clientes con dirección completa: " . Customer::whereNotNull('address')->count() . "\n";
        echo "📊 Clientes de Madrid: " . Customer::where('city', 'Madrid')->count() . "\n";
        echo "📊 Clientes internacionales: " . Customer::where('country', '!=', 'España')->count() . "\n";
    }
}
