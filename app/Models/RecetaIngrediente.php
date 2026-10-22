<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class RecetaIngrediente extends Pivot
{
    protected $table = 'receta_ingrediente';

    protected $fillable = ['receta_id', 'ingrediente_id', 'cantidad', 'unidad_medida'];
}
