<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class Receta extends Model
{
       protected $fillable = ['titulo', 'porciones', 'instrucciones'];

    
    public function ingredientes(): BelongsToMany
    {
        return $this->belongsToMany(Ingrediente::class, 'receta_ingrediente')
            ->using(RecetaIngrediente::class)
            ->withPivot('cantidad', 'unidad_medida');
    }

    public static function crearConIngredientes(array $datos, array $ingredientes): self
{
    if (count($ingredientes) < 1) {
        throw new InvalidArgumentException('Una receta debe tener al menos un ingrediente.');
    }

    return DB::transaction(function () use ($datos, $ingredientes) {
        $receta = self::create($datos);

        foreach ($ingredientes as $linea) {
            $receta->ingredientes()->attach($linea['ingrediente']->id, [
                'cantidad' => $linea['cantidad'],
                'unidad_medida' => $linea['unidad_medida'],
            ]);
        }

        return $receta;
    });
}
}
