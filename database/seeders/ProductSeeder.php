<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpiar tabla (idempotencia)
        Product::truncate();

        // Crear 30 productos normales
        Product::factory()->count(30)->create();

        // Crear 10 productos con stock bajo
        Product::factory()->count(10)->lowStock()->create();

        // Crear 5 productos sin stock
        Product::factory()->count(5)->outOfStock()->create();

        // Crear 3 productos descontinuados
        Product::factory()->count(3)->discontinued()->create();

        // Crear 8 productos más vendidos
        Product::factory()->count(8)->bestSeller()->create();

        // Crear 5 productos económicos
        Product::factory()->count(5)->cheap()->create();

        // Crear 4 productos premium
        Product::factory()->count(4)->premium()->create();

        // Crear algunos productos específicos para testing
        Product::create([
            'name' => 'Laptop HP ProBook',
            'description' => 'Laptop profesional con procesador Intel i7, 16GB RAM, SSD 512GB',
            'price' => 899.99,
            'stock' => 15,
            'min_stock' => 5,
            'max_stock' => 50,
            'status' => 'available',
        ]);

        Product::create([
            'name' => 'Mouse Logitech MX Master',
            'description' => 'Mouse ergonómico inalámbrico de alta precisión',
            'price' => 89.99,
            'stock' => 3,
            'min_stock' => 10,
            'max_stock' => 100,
            'status' => 'available',
        ]);

        Product::create([
            'name' => 'Teclado Mecánico Corsair',
            'description' => 'Teclado mecánico RGB con switches Cherry MX',
            'price' => 149.99,
            'stock' => 0,
            'min_stock' => 5,
            'max_stock' => 50,
            'status' => 'out_of_stock',
        ]);

        Product::create([
            'name' => 'Monitor Dell UltraSharp',
            'description' => 'Monitor 27 pulgadas 4K IPS',
            'price' => 499.99,
            'stock' => 8,
            'min_stock' => 3,
            'max_stock' => 30,
            'status' => 'available',
        ]);

        Product::create([
            'name' => 'Webcam Antigua',
            'description' => 'Webcam descontinuada de baja resolución',
            'price' => 29.99,
            'stock' => 0,
            'min_stock' => 0,
            'max_stock' => 10,
            'status' => 'discontinued',
        ]);

        echo "✅ Se han creado " . Product::count() . " productos\n";
        echo "📊 Productos disponibles: " . Product::where('status', 'available')->count() . "\n";
        echo "📊 Productos con stock bajo: " . Product::whereRaw('stock <= min_stock')->where('stock', '>', 0)->count() . "\n";
        echo "📊 Productos sin stock: " . Product::where('stock', '<=', 0)->count() . "\n";
        echo "📊 Productos descontinuados: " . Product::where('status', 'discontinued')->count() . "\n";
    }
}
