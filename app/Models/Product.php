<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'min_stock',
        'max_stock',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
        'min_stock' => 'integer',
        'max_stock' => 'integer',
    ];

    /**
     * Verificar si el stock está bajo
     */
    public function isLowStock()
    {
        return $this->stock <= $this->min_stock && $this->stock > 0;
    }

    /**
     * Verificar si está sin stock
     */
    public function isOutOfStock()
    {
        return $this->stock <= 0;
    }

    /**
     * Verificar si está descontinuado
     */
    public function isDiscontinued()
    {
        return $this->status === 'discontinued';
    }

    /**
     * Actualizar stock
     */
    public function updateStock($quantity)
    {
        $this->stock += $quantity;

        // Actualizar estado según el stock
        if ($this->stock <= 0) {
            $this->status = 'out_of_stock';
        } elseif ($this->stock > 0 && $this->status === 'out_of_stock') {
            $this->status = 'available';
        }

        $this->save();
        return $this;
    }

    /**
     * Reducir stock (para ventas)
     */
    public function decreaseStock($quantity)
    {
        return $this->updateStock(-$quantity);
    }

    /**
     * Aumentar stock (para compras/devoluciones)
     */
    public function increaseStock($quantity)
    {
        return $this->updateStock($quantity);
    }

    /**
     * Scope para productos con stock bajo
     */
    public function scopeLowStock($query)
    {
        return $query->whereRaw('stock <= min_stock')
                    ->where('stock', '>', 0)
                    ->where('status', '!=', 'discontinued');
    }

    /**
     * Scope para productos sin stock
     */
    public function scopeOutOfStock($query)
    {
        return $query->where('stock', '<=', 0)
                    ->orWhere('status', 'out_of_stock');
    }

    /**
     * Scope para productos disponibles
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available')
                    ->where('stock', '>', 0);
    }

    /**
     * Scope para productos descontinuados
     */
    public function scopeDiscontinued($query)
    {
        return $query->where('status', 'discontinued');
    }

    /**
     * Scope para buscar por texto (nombre o descripción)
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%");
        });
    }

    /**
     * Scope para filtrar por rango de precios
     */
    public function scopePriceRange($query, $minPrice, $maxPrice)
    {
        return $query->whereBetween('price', [$minPrice, $maxPrice]);
    }

    /**
     * Scope para ordenar por stock
     */
    public function scopeOrderByStock($query, $direction = 'asc')
    {
        return $query->orderBy('stock', $direction);
    }

    /**
     * Scope para productos más vendidos (simulación con stock bajo)
     */
    public function scopeBestSellers($query)
    {
        return $query->where('status', 'available')
                    ->whereRaw('stock < (max_stock * 0.3)')
                    ->orderBy('stock', 'asc');
    }
}
