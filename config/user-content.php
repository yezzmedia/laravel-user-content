<?php

declare(strict_types=1);

return [

    'panel' => [
        'id' => 'content',
        'path' => 'hub',
    ],

    'pages' => [
        'per_page' => 25,
        'content_sanitize' => true,
        'allowed_html_tags' => 'h1,h2,h3,h4,h5,h6,p,a,img,ul,ol,li,strong,em,br,blockquote,pre,code,hr,table,thead,tbody,tr,th,td,figure,figcaption',
    ],

    'navigation' => [
        'sections' => ['header', 'footer'],
    ],

    'redirects' => [
        'max_chain_hops' => 5,
    ],

    'forms' => [
        'field_types' => ['text', 'textarea', 'email', 'tel', 'select', 'checkbox', 'radio'],
        'rate_limit_period' => 60,
        'default_rate_limit' => 5,
        'honeypot_enabled' => true,
    ],

    'cache' => [
        'enabled' => false,
        'ttl' => 3600,
    ],

];
