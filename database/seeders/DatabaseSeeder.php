<?php

namespace Database\Seeders;

use App\Models\CategoriaNoticias;
use App\Models\Causa;
use App\Models\Donacion;
use App\Models\Donador;
use App\Models\MiembroEquipo;
use App\Models\Noticia;
use App\Models\PlanDonacion;
use App\Models\ReporteTransparencia;
use App\Models\Testimonio;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Overcloud MASTER — nunca eliminar
        User::updateOrCreate(
            ['email' => 'edumaucherni@gmail.com'],
            ['name' => 'Eduardo', 'password' => 'Eduardo2006!', 'email_verified_at' => now()],
        );

        // Admin AJDUT Mexico
        User::updateOrCreate(
            ['email' => 'ajdut-mexico@overcloud.us'],
            ['name' => 'Admin AJDUT Mexico', 'password' => 'w3PyUKwrPek4', 'email_verified_at' => now()],
        );

        // Planes de donación
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

        // Causas activas
        $causas = [
            [
                'titulo' => 'Educación para Niños en Zonas Vulnerables',
                'slug' => 'educacion-ninos-zonas-vulnerables',
                'descripcion_corta' => 'Dotamos de útiles, uniformes y becas a niños de comunidades marginadas para que no abandonen la escuela.',
                'descripcion' => 'En AJDUT Mexico creemos que la educación es el camino más poderoso para romper el ciclo de la pobreza. A través de este programa, proporcionamos útiles escolares, uniformes, becas y acompañamiento académico a niños y niñas de comunidades vulnerables en todo México.',
                'meta_recaudacion' => 250000.00,
                'recaudado' => 87500.00,
                'categoria' => 'Educación',
                'activa' => true,
                'destacada' => true,
                'beneficiarios' => 450,
                'ubicacion' => 'Oaxaca, Chiapas, Guerrero',
            ],
            [
                'titulo' => 'Alimentación y Nutrición Infantil',
                'slug' => 'alimentacion-nutricion-infantil',
                'descripcion_corta' => 'Combatimos la desnutrición infantil con comedores comunitarios y despensas mensuales.',
                'descripcion' => 'La desnutrición infantil es una de las problemáticas más urgentes en México. Con tu donación, operamos comedores comunitarios que sirven más de 500 comidas diarias y entregamos despensas mensuales a familias en situación de vulnerabilidad.',
                'meta_recaudacion' => 180000.00,
                'recaudado' => 124000.00,
                'categoria' => 'Salud',
                'activa' => true,
                'destacada' => true,
                'beneficiarios' => 320,
                'ubicacion' => 'Ciudad de México, Estado de México',
            ],
            [
                'titulo' => 'Mujeres Emprendedoras',
                'slug' => 'mujeres-emprendedoras',
                'descripcion_corta' => 'Capacitación y microcréditos para mujeres jefas de familia que desean iniciar su negocio.',
                'descripcion' => 'Apoyamos a mujeres jefas de familia con capacitación en habilidades empresariales, mentoría personalizada y microcréditos de arranque para que puedan generar ingresos propios y mejorar la calidad de vida de sus familias.',
                'meta_recaudacion' => 120000.00,
                'recaudado' => 45000.00,
                'categoria' => 'Desarrollo Comunitario',
                'activa' => true,
                'destacada' => false,
                'beneficiarios' => 85,
                'ubicacion' => 'Jalisco, Puebla',
            ],
            [
                'titulo' => 'Atención Médica en Comunidades Rurales',
                'slug' => 'atencion-medica-comunidades-rurales',
                'descripcion_corta' => 'Brigadas médicas que llevan atención primaria a comunidades sin acceso a servicios de salud.',
                'descripcion' => 'Organizamos brigadas médicas mensuales que visitan comunidades rurales sin acceso a servicios de salud. Cada brigada ofrece consultas generales, odontología, optometría y entrega de medicamentos sin costo.',
                'meta_recaudacion' => 200000.00,
                'recaudado' => 78000.00,
                'categoria' => 'Salud',
                'activa' => true,
                'destacada' => false,
                'beneficiarios' => 1200,
                'ubicacion' => 'Veracruz, Hidalgo, San Luis Potosí',
            ],
        ];

        foreach ($causas as $causa) {
            Causa::updateOrCreate(['slug' => $causa['slug']], $causa);
        }

        // Donadores de ejemplo
        $donador1 = Donador::updateOrCreate(
            ['email' => 'maria.garcia@ejemplo.com'],
            [
                'nombre' => 'María',
                'apellido' => 'García López',
                'telefono' => '5512345678',
                'rfc' => 'GALM820315AB3',
                'plan_actual' => 'Padrino',
                'estado' => 'activo',
                'total_donado' => 3000.00,
                'fecha_primer_donacion' => now()->subMonths(6),
            ]
        );

        $donador2 = Donador::updateOrCreate(
            ['email' => 'carlos.mendez@empresa.mx'],
            [
                'nombre' => 'Carlos',
                'apellido' => 'Méndez Ruiz',
                'telefono' => '5598765432',
                'razon_social' => 'Grupo Méndez SA de CV',
                'rfc' => 'GMR910720XY1',
                'plan_actual' => 'Benefactor',
                'estado' => 'activo',
                'total_donado' => 15000.00,
                'fecha_primer_donacion' => now()->subYear(),
            ]
        );

        $donador3 = Donador::updateOrCreate(
            ['email' => 'ana.torres@correo.com'],
            [
                'nombre' => 'Ana',
                'apellido' => 'Torres Vega',
                'telefono' => '5534567890',
                'plan_actual' => 'Amigo',
                'estado' => 'activo',
                'total_donado' => 1200.00,
                'fecha_primer_donacion' => now()->subMonths(3),
            ]
        );

        // Donaciones de ejemplo
        $causaPrincipal = Causa::where('slug', 'educacion-ninos-zonas-vulnerables')->first();

        if ($donador1->donaciones()->count() === 0 && $causaPrincipal) {
            Donacion::create([
                'folio' => 'DON-' . strtoupper(\Illuminate\Support\Str::random(8)),
                'donador_id' => $donador1->id,
                'causa_id' => $causaPrincipal->id,
                'monto' => 500.00,
                'frecuencia' => 'mensual',
                'estado' => 'completada',
                'metodo_pago' => 'stripe',
                'fecha_pago' => now()->subMonths(2),
            ]);
            Donacion::create([
                'folio' => 'DON-' . strtoupper(\Illuminate\Support\Str::random(8)),
                'donador_id' => $donador1->id,
                'causa_id' => $causaPrincipal->id,
                'monto' => 500.00,
                'frecuencia' => 'mensual',
                'estado' => 'completada',
                'metodo_pago' => 'stripe',
                'fecha_pago' => now()->subMonth(),
            ]);
        }

        if ($donador2->donaciones()->count() === 0) {
            Donacion::create([
                'folio' => 'DON-' . strtoupper(\Illuminate\Support\Str::random(8)),
                'donador_id' => $donador2->id,
                'monto' => 5000.00,
                'frecuencia' => 'unica',
                'estado' => 'completada',
                'metodo_pago' => 'transferencia',
                'fecha_pago' => now()->subMonths(3),
            ]);
        }

        if ($donador3->donaciones()->count() === 0 && $causaPrincipal) {
            Donacion::create([
                'folio' => 'DON-' . strtoupper(\Illuminate\Support\Str::random(8)),
                'donador_id' => $donador3->id,
                'causa_id' => $causaPrincipal->id,
                'monto' => 200.00,
                'frecuencia' => 'mensual',
                'estado' => 'completada',
                'metodo_pago' => 'stripe',
                'fecha_pago' => now()->subMonths(1),
            ]);
        }

        // Testimonios
        $testimonios = [
            [
                'nombre' => 'Sofía Hernández',
                'cargo' => 'Mamá beneficiaria',
                'organizacion' => 'Comunidad San Marcos, Oaxaca',
                'testimonio' => 'Gracias a AJDUT Mexico, mis hijos pudieron terminar la primaria sin preocupaciones. Nunca imaginé que habría personas dispuestas a ayudarnos así. Es un regalo enorme.',
                'estrellas' => 5,
                'activo' => true,
                'orden' => 1,
            ],
            [
                'nombre' => 'Roberto Villalobos',
                'cargo' => 'Director Regional',
                'organizacion' => 'CONAFE Chiapas',
                'testimonio' => 'La colaboración con AJDUT Mexico ha sido fundamental para llegar a comunidades donde el gobierno no alcanza. Su compromiso y transparencia son ejemplares.',
                'estrellas' => 5,
                'activo' => true,
                'orden' => 2,
            ],
            [
                'nombre' => 'Laura Jimeno',
                'cargo' => 'Donadora desde 2021',
                'organizacion' => null,
                'testimonio' => 'Dono mensualmente y cada trimestre recibo un informe detallado de cómo se usa mi dinero. Eso me da mucha confianza. Sé que cada peso tiene un impacto real.',
                'estrellas' => 5,
                'activo' => true,
                'orden' => 3,
            ],
        ];

        foreach ($testimonios as $idx => $t) {
            if (Testimonio::count() <= $idx) {
                Testimonio::create($t);
            }
        }

        // Miembros del equipo
        $equipo = [
            [
                'nombre' => 'Dra. Isabel Montoya',
                'cargo' => 'Directora General',
                'area' => 'Dirección',
                'biografia' => 'Con más de 20 años de experiencia en el sector social, Isabel fundó AJDUT Mexico con la visión de crear impacto sostenible en comunidades vulnerables.',
                'email' => 'direccion@ajdut-mexico.org',
                'activo' => true,
                'orden' => 1,
            ],
            [
                'nombre' => 'Lic. Fernando Ochoa',
                'cargo' => 'Director de Programas',
                'area' => 'Operaciones',
                'biografia' => 'Fernando coordina todos los programas de campo, asegurando que cada causa alcance sus objetivos con eficiencia y transparencia.',
                'email' => 'programas@ajdut-mexico.org',
                'activo' => true,
                'orden' => 2,
            ],
            [
                'nombre' => 'CP. Valentina Reyes',
                'cargo' => 'Directora Financiera',
                'area' => 'Finanzas',
                'biografia' => 'Valentina garantiza que cada peso donado sea administrado con absoluta transparencia y eficiencia, manteniendo los más altos estándares de rendición de cuentas.',
                'email' => 'finanzas@ajdut-mexico.org',
                'activo' => true,
                'orden' => 3,
            ],
            [
                'nombre' => 'Psic. Marco Antonio Ríos',
                'cargo' => 'Coordinador de Impacto Social',
                'area' => 'Programas',
                'biografia' => 'Marco diseña y evalúa el impacto de todos nuestros programas, asegurando que las intervenciones generen cambios reales y medibles en la vida de los beneficiarios.',
                'email' => 'impacto@ajdut-mexico.org',
                'activo' => true,
                'orden' => 4,
            ],
        ];

        foreach ($equipo as $idx => $m) {
            if (MiembroEquipo::count() <= $idx) {
                MiembroEquipo::create($m);
            }
        }

        // Categorías de noticias
        $categorias = [
            ['nombre' => 'Noticias', 'slug' => 'noticias', 'color' => '#0d9488'],
            ['nombre' => 'Historias de Impacto', 'slug' => 'historias-impacto', 'color' => '#d97706'],
            ['nombre' => 'Eventos', 'slug' => 'eventos', 'color' => '#7c3aed'],
            ['nombre' => 'Transparencia', 'slug' => 'transparencia', 'color' => '#059669'],
        ];

        foreach ($categorias as $cat) {
            CategoriaNoticias::updateOrCreate(['slug' => $cat['slug']], $cat);
        }

        // Noticias de ejemplo
        $catNoticias = CategoriaNoticias::where('slug', 'noticias')->first();
        $catHistorias = CategoriaNoticias::where('slug', 'historias-impacto')->first();

        if (Noticia::count() === 0) {
            Noticia::create([
                'categoria_id' => $catNoticias?->id,
                'titulo' => 'AJDUT Mexico alcanza 500 beneficiarios en su primer año',
                'slug' => 'ajdut-mexico-alcanza-500-beneficiarios',
                'resumen' => 'Celebramos un hito histórico: más de 500 personas han mejorado su calidad de vida gracias a los programas de AJDUT Mexico.',
                'contenido' => 'Con orgullo anunciamos que en el primer año de operaciones, AJDUT Mexico ha logrado impactar positivamente la vida de más de 500 personas en comunidades vulnerables de México...',
                'autor' => 'Equipo AJDUT Mexico',
                'publicada' => true,
                'fecha_publicacion' => now()->subDays(15),
            ]);

            Noticia::create([
                'categoria_id' => $catHistorias?->id,
                'titulo' => 'La historia de Carmen: cómo una beca cambió su futuro',
                'slug' => 'historia-carmen-beca-cambio-futuro',
                'resumen' => 'Carmen tenía 12 años y estaba a punto de dejar la escuela. Hoy, gracias a una beca de AJDUT Mexico, está en preparatoria con sueños de ser maestra.',
                'contenido' => 'Carmen García creció en una pequeña comunidad de Oaxaca donde la educación secundaria parecía un lujo inalcanzable. Cuando AJDUT Mexico llegó a su comunidad...',
                'autor' => 'Equipo de Comunicación',
                'publicada' => true,
                'fecha_publicacion' => now()->subDays(5),
            ]);
        }

        // Reporte de transparencia
        if (ReporteTransparencia::count() === 0) {
            ReporteTransparencia::create([
                'titulo' => 'Informe Anual de Transparencia 2025',
                'tipo' => 'anual',
                'anio' => 2025,
                'descripcion' => 'Informe completo de la gestión y uso de fondos recibidos durante el ejercicio 2025.',
                'total_recaudado' => 456780.00,
                'total_gastado' => 389450.00,
                'donadores_activos' => 234,
                'beneficiarios' => 1892,
                'publicado' => true,
            ]);
        }
    }
}
