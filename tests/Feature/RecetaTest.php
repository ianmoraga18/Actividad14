<?php

namespace Tests\Feature;

use App\Models\Ingrediente;
use App\Models\Receta;
use Illuminate\Foundation\Testing\RefreshDatabase;
use InvalidArgumentException;
use Tests\TestCase;

class RecetaTest extends TestCase
{
    use RefreshDatabase;

    public function test_una_receta_contiene_ingredientes_con_cantidad_y_unidad(): void
    {
        $harina = Ingrediente::create(['nombre' => 'Harina']);
        $azucar = Ingrediente::create(['nombre' => 'Azúcar']);

        $receta = Receta::crearConIngredientes(
            [
                'titulo' => 'Pastel de vainilla',
                'porciones' => 8,
                'instrucciones' => 'Mezclar, hornear 40 minutos a 180 °C.',
            ],
            [
                ['ingrediente' => $harina, 'cantidad' => 500, 'unidad_medida' => 'g'],
                ['ingrediente' => $azucar, 'cantidad' => 200, 'unidad_medida' => 'g'],
            ]
        );

        
        $this->assertSame('Pastel de vainilla', $receta->titulo);
        $this->assertSame(8, $receta->porciones);

        
        $this->assertCount(2, $receta->ingredientes);

        
        $enReceta = $receta->ingredientes->firstWhere('nombre', 'Harina');
        $this->assertEquals(500, $enReceta->pivot->cantidad);
        $this->assertSame('g', $enReceta->pivot->unidad_medida);
    }

    public function test_una_receta_sin_ingredientes_no_se_puede_crear(): void
    {
        $this->expectException(InvalidArgumentException::class);

        Receta::crearConIngredientes(
            ['titulo' => 'Receta vacía', 'porciones' => 1, 'instrucciones' => 'Nada.'],
            []
        );
    }
}