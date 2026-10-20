<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class LandingSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'label',
    ];

    /**
     * Cache key for settings.
     */
    public const CACHE_KEY = 'aft_landing_settings_all';

    /**
     * Get all default key-value pairs with metadata.
     */
    public static function defaults(): array
    {
        return [
            // SEO & General
            'site_title' => [
                'value' => 'AF Training | Plataforma Definitiva de Entrenamiento, Nutrición & Gestión Deportiva',
                'type' => 'text',
                'group' => 'seo',
                'label' => 'Título del Sitio (Meta Title)',
            ],
            'meta_description' => [
                'value' => 'AF Training - La plataforma de alto rendimiento para Head Coaches, gimnasios, atletas independientes y clientes. TDEE automatizado, periodización y biometría 360°.',
                'type' => 'textarea',
                'group' => 'seo',
                'label' => 'Descripción SEO (Meta Description)',
            ],
            'brand_name' => [
                'value' => 'AF Training',
                'type' => 'text',
                'group' => 'seo',
                'label' => 'Nombre de Marca',
            ],

            // Hero Section
            'hero_badge' => [
                'value' => 'Plataforma Todo-en-Uno • Versión 3.0',
                'type' => 'text',
                'group' => 'hero',
                'label' => 'Insignia Superior (Badge)',
            ],
            'hero_title_prefix' => [
                'value' => 'La Plataforma Definitiva de',
                'type' => 'text',
                'group' => 'hero',
                'label' => 'Título Hero (Texto inicial)',
            ],
            'hero_title_highlight' => [
                'value' => 'Entrenamiento, Nutrición',
                'type' => 'text',
                'group' => 'hero',
                'label' => 'Título Hero (Texto con gradiente)',
            ],
            'hero_title_suffix' => [
                'value' => '& Gestión Deportiva',
                'type' => 'text',
                'group' => 'hero',
                'label' => 'Título Hero (Texto final)',
            ],
            'hero_subtitle' => [
                'value' => 'Diseñada para Head Coaches de alto rendimiento, gimnasios comerciales, atletas independientes y sus clientes. Integra prescripción de rutinas, bioimpedancia, ajuste de macros y control de staff en un ecosistema quirúrgico.',
                'type' => 'textarea',
                'group' => 'hero',
                'label' => 'Descripción / Subtítulo Principal',
            ],
            'hero_cta_primary_text' => [
                'value' => 'Comenzar Prueba Gratuita',
                'type' => 'text',
                'group' => 'hero',
                'label' => 'Botón Primario (Texto)',
            ],
            'hero_cta_primary_url' => [
                'value' => '#roles',
                'type' => 'text',
                'group' => 'hero',
                'label' => 'Botón Primario (Enlace/URL)',
            ],
            'hero_cta_secondary_text' => [
                'value' => 'Explorar Ecosistema',
                'type' => 'text',
                'group' => 'hero',
                'label' => 'Botón Secundario (Texto)',
            ],
            'hero_cta_secondary_url' => [
                'value' => '#modulos',
                'type' => 'text',
                'group' => 'hero',
                'label' => 'Botón Secundario (Enlace/URL)',
            ],
            'hero_proof_text' => [
                'value' => 'Usado por entrenadores certificados NSCA, IFBB Pro y centros élite.',
                'type' => 'text',
                'group' => 'hero',
                'label' => 'Prueba Social (Texto al pie del Hero)',
            ],
            'hero_image_1' => [
                'value' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuD-qYG8vfXEAYGPCa2ECcMFu7pkAHmAYwPtuCkmyLsg4ruvNnm9Z0eeeTeRYREjy4oV5zeE0SPX2Rc0hwRKZM2CxzmfIimTwdHaGgqZoJekJ4uEkOgZE8P1yKlfvu5EMBkouRRqtCZhzAJGiIqLXdxC0aAQtdcmQu3GHykN_k_D-vRBnuOhlf7O8ZB0SdRoSXL3SoxhRtJjQoOFPxC30cdkiHZXZIl-lg_hNIYKR5IWEAPltEnnwsWk2IkDppEdHyXx5BA',
                'type' => 'image',
                'group' => 'hero',
                'label' => 'Imagen Frontal (Mockup Pantalla Principal)',
            ],
            'hero_image_2' => [
                'value' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBLj6DXqEIz4H8L3cblxP2KF4DYgtYeMgH53WgTQofY8Ba5OwaxXgmFcN1XWb1uS2CiLUUgsl6go-HKftCm-oT9T7xJix9x78hMlmHHuveiMsAJOjpa-ENg2aXulO9wLlSzURtMDoYe50IWMxZNfSOPVd75xJrc5TpkfkkW9XJatJk0pLNCljEdgewUFNpI8W0zBd4xkeEsaO6uogHkKDXSaEyClWGwDXho0dlTMXNawfE4YoJKeDmegvY1I_p2bB9Ndgk',
                'type' => 'image',
                'group' => 'hero',
                'label' => 'Imagen Posterior (Mockup Pantalla Secundaria)',
            ],

            // Hero Metrics
            'metric_1_value' => [
                'value' => '+5,000',
                'type' => 'text',
                'group' => 'metrics',
                'label' => 'Métrica 1 (Valor)',
            ],
            'metric_1_label' => [
                'value' => 'Planes Activos',
                'type' => 'text',
                'group' => 'metrics',
                'label' => 'Métrica 1 (Etiqueta)',
            ],
            'metric_2_value' => [
                'value' => '100%',
                'type' => 'text',
                'group' => 'metrics',
                'label' => 'Métrica 2 (Valor)',
            ],
            'metric_2_label' => [
                'value' => 'TDEE Auto',
                'type' => 'text',
                'group' => 'metrics',
                'label' => 'Métrica 2 (Etiqueta)',
            ],
            'metric_3_value' => [
                'value' => '360°',
                'type' => 'text',
                'group' => 'metrics',
                'label' => 'Métrica 3 (Valor)',
            ],
            'metric_3_label' => [
                'value' => 'Biometría',
                'type' => 'text',
                'group' => 'metrics',
                'label' => 'Métrica 3 (Etiqueta)',
            ],

            // Contact & Social
            'contact_whatsapp' => [
                'value' => '',
                'type' => 'text',
                'group' => 'contact',
                'label' => 'Número de WhatsApp (con código de país ej: 521XXXXXXXXXX)',
            ],
            'contact_whatsapp_message' => [
                'value' => 'Hola, me interesa conocer más sobre AF Training',
                'type' => 'text',
                'group' => 'contact',
                'label' => 'Mensaje inicial de WhatsApp',
            ],
            'contact_email' => [
                'value' => 'contacto@aftraining.com',
                'type' => 'text',
                'group' => 'contact',
                'label' => 'Correo Electrónico de Contacto',
            ],
            'social_instagram' => [
                'value' => 'https://instagram.com',
                'type' => 'url',
                'group' => 'contact',
                'label' => 'Instagram URL',
            ],
            'social_facebook' => [
                'value' => 'https://facebook.com',
                'type' => 'url',
                'group' => 'contact',
                'label' => 'Facebook URL',
            ],
            'social_youtube' => [
                'value' => 'https://youtube.com',
                'type' => 'url',
                'group' => 'contact',
                'label' => 'YouTube URL',
            ],
            'social_tiktok' => [
                'value' => 'https://tiktok.com',
                'type' => 'url',
                'group' => 'contact',
                'label' => 'TikTok URL',
            ],

            // App Links
            'app_store_url' => [
                'value' => '#',
                'type' => 'url',
                'group' => 'apps',
                'label' => 'Enlace App Store (iOS)',
            ],
            'play_store_url' => [
                'value' => '#',
                'type' => 'url',
                'group' => 'apps',
                'label' => 'Enlace Google Play (Android)',
            ],
            'show_app_downloads' => [
                'value' => '1',
                'type' => 'boolean',
                'group' => 'apps',
                'label' => 'Mostrar Botones de Descarga de App',
            ],

            // Philosophy & Brand Story
            'philosophy_badge' => [
                'value' => 'Filosofía de Rendimiento',
                'type' => 'text',
                'group' => 'philosophy',
                'label' => 'Insignia de Filosofía',
            ],
            'philosophy_title' => [
                'value' => 'Elevate Your Fitness',
                'type' => 'text',
                'group' => 'philosophy',
                'label' => 'Título de Filosofía',
            ],
            'philosophy_description' => [
                'value' => 'La llama naranja y la onda azul de nuestro isotipo representan la combustión metabólica y la precisión metódica. Convertimos el entrenamiento de fuerza en ciencia medible.',
                'type' => 'textarea',
                'group' => 'philosophy',
                'label' => 'Descripción de Filosofía de Marca',
            ],

            // Final CTA Banner
            'final_cta_badge' => [
                'value' => '14 Días de Prueba Ilimitada',
                'type' => 'text',
                'group' => 'final_cta',
                'label' => 'Insignia Banner Final',
            ],
            'final_cta_title' => [
                'value' => '¿Listo para elevar tu estándar de entrenamiento?',
                'type' => 'text',
                'group' => 'final_cta',
                'label' => 'Título Banner Final',
            ],
            'final_cta_subtitle' => [
                'value' => 'Únete a la nueva era del fitness inteligente. Gestiona clientes, automatiza el cálculo de calorías y lleva el control absoluto de tus sesiones en una sola pantalla.',
                'type' => 'textarea',
                'group' => 'final_cta',
                'label' => 'Subtítulo Banner Final',
            ],
            'final_cta_btn1_text' => [
                'value' => 'Crear Cuenta Gratis',
                'type' => 'text',
                'group' => 'final_cta',
                'label' => 'Botón Primario (Texto)',
            ],
            'final_cta_btn1_url' => [
                'value' => '/login',
                'type' => 'text',
                'group' => 'final_cta',
                'label' => 'Botón Primario (URL)',
            ],
            'final_cta_btn2_text' => [
                'value' => 'Agendar Demo de Gimnasio',
                'type' => 'text',
                'group' => 'final_cta',
                'label' => 'Botón Secundario (Texto)',
            ],
            'final_cta_btn2_url' => [
                'value' => '#roles',
                'type' => 'text',
                'group' => 'final_cta',
                'label' => 'Botón Secundario (URL)',
            ],

            // Section Visibility Toggles
            'section_roles_enabled' => [
                'value' => '1',
                'type' => 'boolean',
                'group' => 'visibility',
                'label' => 'Mostrar Sección de Roles (Coach, Gimnasio, Atleta)',
            ],
            'section_modules_enabled' => [
                'value' => '1',
                'type' => 'boolean',
                'group' => 'visibility',
                'label' => 'Mostrar Sección de Módulos (TDEE, Rutinas, QR, Biometría)',
            ],
            'section_workflow_enabled' => [
                'value' => '1',
                'type' => 'boolean',
                'group' => 'visibility',
                'label' => 'Mostrar Sección de Flujo de Trabajo (Workflow)',
            ],
            'section_philosophy_enabled' => [
                'value' => '1',
                'type' => 'boolean',
                'group' => 'visibility',
                'label' => 'Mostrar Sección de Filosofía & Logo Oficial',
            ],
            'section_final_cta_enabled' => [
                'value' => '1',
                'type' => 'boolean',
                'group' => 'visibility',
                'label' => 'Mostrar Banner de Conversión Final',
            ],
        ];
    }

    /**
     * Retrieve all settings as key => value dictionary with defaults merged.
     */
    public static function getAllSettings(): array
    {
        return Cache::remember(self::CACHE_KEY, 3600, function () {
            $defaults = self::defaults();
            $dbRows = self::all()->keyBy('key');

            $result = [];
            foreach ($defaults as $key => $meta) {
                if (isset($dbRows[$key]) && $dbRows[$key]->value !== null) {
                    $result[$key] = $dbRows[$key]->value;
                } else {
                    $result[$key] = $meta['value'];
                }
            }

            // Also include any custom keys that might exist in DB
            foreach ($dbRows as $key => $row) {
                if (!isset($result[$key])) {
                    $result[$key] = $row->value;
                }
            }

            return $result;
        });
    }

    /**
     * Get a single setting by key with fallback.
     */
    public static function getValue(string $key, $fallback = null)
    {
        $all = self::getAllSettings();
        if (array_key_exists($key, $all)) {
            return $all[$key];
        }

        $defaults = self::defaults();
        if (isset($defaults[$key])) {
            return $defaults[$key]['value'];
        }

        return $fallback;
    }

    /**
     * Clear settings cache.
     */
    public static function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}
