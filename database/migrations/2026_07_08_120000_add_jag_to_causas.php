<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('causas', 'jag')) {
            Schema::table('causas', function (Blueprint $table) {
                $table->string('jag')->nullable()->after('categoria');
            });
        }

        // Seed example holiday (jaguim) campaigns — idempotent by slug, additive.
        $now = now();
        $campanas = [
            [
                'slug' => 'jag-pesaj', 'titulo' => 'Pesaj', 'jag' => 'Pesaj',
                'corta' => 'Ayuda a las familias de la comunidad a celebrar Pesaj con dignidad: alimentos kasher, matzá y lo necesario para el Seder.',
                'meta' => 150000,
            ],
            [
                'slug' => 'jag-rosh-hashana', 'titulo' => 'Rosh Hashaná', 'jag' => 'Rosh Hashaná',
                'corta' => 'Recibamos juntos el Año Nuevo: apoyo a familias necesitadas para las cenas festivas y las fiestas de Tishrei.',
                'meta' => 150000,
            ],
        ];
        foreach ($campanas as $c) {
            if (! DB::table('causas')->where('slug', $c['slug'])->exists()) {
                DB::table('causas')->insert([
                    'titulo' => $c['titulo'],
                    'slug' => $c['slug'],
                    'descripcion_corta' => $c['corta'],
                    'descripcion' => $c['corta'].' (Puedes personalizar el texto e imagen de esta campaña desde el panel de administración › Causas.)',
                    'imagen' => null,
                    'meta_recaudacion' => $c['meta'],
                    'recaudado' => 0,
                    'categoria' => 'jaguim',
                    'jag' => $c['jag'],
                    'activa' => true,
                    'destacada' => false,
                    'beneficiarios' => 0,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('causas', 'jag')) {
            Schema::table('causas', function (Blueprint $table) {
                $table->dropColumn('jag');
            });
        }
    }
};
