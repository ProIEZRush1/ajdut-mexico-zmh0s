<?php

namespace Database\Seeders;

use App\Models\CategoriaNoticias;
use App\Models\Causa;
use App\Models\PlanDonacion;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Usuarios administradores.
        // firstOrCreate: sólo se crean si no existen; NO se pisa la contraseña
        // en cada deploy, así el admin puede cambiarla y se conserva.
        User::firstOrCreate(
            ['email' => 'edumaucherni@gmail.com'],
            ['name' => 'Eduardo', 'password' => Hash::make('Eduardo2006!'), 'email_verified_at' => now()],
        );

        User::firstOrCreate(
            ['email' => 'ajdut-mexico@overcloud.us'],
            ['name' => 'Admin AJDUT Mexico', 'password' => Hash::make('AjdutMexico2026'), 'email_verified_at' => now()],
        );

        // Planes de donación.
        $planes = [
            [
                'nombre' => 'Amigo',
                'slug' => 'amigo',
                'descripcion' => 'Tu aportación mensual ayuda a cubrir necesidades básicas de nuestros beneficiarios.',
                'monto_sugerido' => 200.00,
                'monto_libre' => false,
                'frecuencia' => 'mensual',
                'beneficios' => ['Boletín mensual', 'Constancia de donador', 'Acceso al portal donador'],
                'color' => '#0d9488',
                'icono' => '❤️',
                'activo' => true,
                'orden' => 1,
            ],
            [
                'nombre' => 'Padrino',
                'slug' => 'padrino',
                'descripcion' => 'Apadrina a un beneficiario y recibe actualizaciones directas de su progreso.',
                'monto_sugerido' => 500.00,
                'monto_libre' => false,
                'frecuencia' => 'mensual',
                'beneficios' => ['Todo lo de Amigo', 'Informe personalizado trimestral', 'Carta de agradecimiento', 'Recibo fiscal'],
                'color' => '#d97706',
                'icono' => '🌟',
                'activo' => true,
                'orden' => 2,
            ],
            [
                'nombre' => 'Benefactor',
                'slug' => 'benefactor',
                'descripcion' => 'Conviértete en pilar de nuestra institución con el mayor nivel de compromiso.',
                'monto_sugerido' => 1500.00,
                'monto_libre' => true,
                'frecuencia' => 'mensual',
                'beneficios' => ['Todo lo de Padrino', 'Mención en reportes anuales', 'Invitación a eventos exclusivos', 'Visita a proyectos', 'Recibo fiscal deducible'],
                'color' => '#7c3aed',
                'icono' => '💎',
                'activo' => true,
                'orden' => 3,
            ],
        ];

        foreach ($planes as $plan) {
            PlanDonacion::updateOrCreate(['slug' => $plan['slug']], $plan);
        }

        // Causas reales de AJDUT (campañas por festividad).
        // firstOrCreate: no se pisa lo ya recaudado en cada deploy.
        $causas = [
            [
                'slug' => 'jag-rosh-hashana',
                'titulo' => 'Rosh Hashaná',
                'descripcion_corta' => 'Recibamos juntos el Año Nuevo: apoyo a familias necesitadas para las cenas festivas y las fiestas de Tishrei.',
                'descripcion' => 'En Rosh Hashaná ayudamos a familias de la comunidad a recibir el Año Nuevo con dignidad: apoyo para las cenas festivas y lo necesario durante las fiestas de Tishrei.',
                'meta_recaudacion' => 150000.00,
                'recaudado' => 0.00,
                'categoria' => 'Rosh Hashaná',
                'jag' => 'Rosh Hashaná',
                'activa' => true,
                'destacada' => true,
                'beneficiarios' => 0,
            ],
            [
                'slug' => 'jag-pesaj',
                'titulo' => 'Pesaj',
                'descripcion_corta' => 'Ayuda a las familias de la comunidad a celebrar Pesaj con dignidad: alimentos kasher, matzá y lo necesario para el Seder.',
                'descripcion' => 'En Pesaj apoyamos a las familias para que celebren la festividad con dignidad: alimentos kasher, matzá y todo lo necesario para el Seder.',
                'meta_recaudacion' => 200000.00,
                'recaudado' => 0.00,
                'categoria' => 'Pesaj',
                'jag' => 'Pesaj',
                'activa' => true,
                'destacada' => true,
                'beneficiarios' => 0,
            ],
        ];

        foreach ($causas as $causa) {
            Causa::firstOrCreate(['slug' => $causa['slug']], $causa);
        }

        // Categorías de noticias (taxonomía base del panel).
        $categorias = [
            ['nombre' => 'Noticias', 'slug' => 'noticias', 'color' => '#0d9488'],
            ['nombre' => 'Historias de Impacto', 'slug' => 'historias-impacto', 'color' => '#d97706'],
            ['nombre' => 'Eventos', 'slug' => 'eventos', 'color' => '#7c3aed'],
            ['nombre' => 'Transparencia', 'slug' => 'transparencia', 'color' => '#059669'],
        ];

        foreach ($categorias as $cat) {
            CategoriaNoticias::updateOrCreate(['slug' => $cat['slug']], $cat);
        }
    }
}
