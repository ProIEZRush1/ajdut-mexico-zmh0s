<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Navegación de capacidades
    |--------------------------------------------------------------------------
    |
    | Cada capacidad instalada agrega aquí sus entradas de menú durante la
    | integración. Una entrada tiene la forma:
    |
    |   ['label' => 'Reservas', 'href' => '/reservas', 'icon' => '📅']
    |
    | Estas entradas se comparten con Inertia mediante HandleInertiaRequests
    | (prop 'capabilities') y el layout las renderiza en la barra lateral.
    |
    */
    'nav' => [
        ['label' => 'Causas',        'href' => '/admin/causas',        'icon' => '❤️'],
        ['label' => 'Planes',        'href' => '/admin/planes',        'icon' => '🎗️'],
        ['label' => 'Donadores',     'href' => '/admin/donadores',     'icon' => '👥'],
        ['label' => 'Donaciones',    'href' => '/admin/donaciones',    'icon' => '💳'],
        ['label' => 'Noticias',      'href' => '/admin/noticias',      'icon' => '📰'],
        ['label' => 'Transparencia', 'href' => '/admin/transparencia', 'icon' => '📊'],
        ['label' => 'Mensajes',      'href' => '/admin/mensajes',      'icon' => '✉️'],
        ['label' => 'Testimonios',   'href' => '/admin/testimonios',   'icon' => '💬'],
        ['label' => 'Equipo',        'href' => '/admin/equipo',        'icon' => '🤝'],
        ['label' => 'Portal Donador', 'href' => '/portal',              'icon' => '🌐'],
        ['label' => 'Configuración', 'href' => '/ajustes',             'icon' => '⚙️'],
    ],
];
