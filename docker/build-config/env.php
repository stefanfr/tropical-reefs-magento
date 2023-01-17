<?php
return [
    'backend' => [
        'frontName' => 'storemanager',
    ],
    'store_mapping' => [
        'tropicalreefs_nl' => 'nl',
        'tropicalreefs_en' => 'en',
    ],
    'resource' => [
        'default_setup' => [
            'connection' => 'default',
        ],
    ],
    'MAGE_MODE' => 'production',
    'cache_types' => [
        'config' => 0,
        'layout' => 0,
        'block_html' => 0,
        'collections' => 0,
        'reflection' => 0,
        'db_ddl' => 0,
        'compiled_config' => 0,
        'eav' => 0,
        'customer_notification' => 0,
        'config_integration' => 0,
        'config_integration_api' => 0,
        'full_page' => 0,
        'config_webservice' => 0,
        'translate' => 0,
    ],
    'system' => [
        'default' => [
            'web' => [
                'unsecure' => [
                    'base_url' => 'https://dev.tropicalreefs.nl/',
                    'base_link_url' => '{{unsecure_base_url}}',
                    'base_static_url' => null,
                    'base_media_url' => null,
                ],
                'secure' => [
                    'base_url' => 'https://dev.tropicalreefs.nl/',
                    'base_link_url' => '{{secure_base_url}}',
                    'base_static_url' => null,
                    'base_media_url' => null,
                ],
                'default' => [
                    'front' => 'cms',
                ],
                'cookie' => [
                    'cookie_path' => null,
                    'cookie_domain' => null,
                ],
            ],
            'admin' => [
                'url' => [
                    'custom' => null,
                ],
            ],
        ],
    ],
    'downloadable_domains' => [
        'dev.tropicalreefs.nl',
    ],
];
