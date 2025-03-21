<?php

namespace Config;

use CodeIgniter\Config\Filters as BaseFilters;
use CodeIgniter\Filters\Cors;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\ForceHTTPS;
use CodeIgniter\Filters\Honeypot;
use CodeIgniter\Filters\InvalidChars;
use CodeIgniter\Filters\PageCache;
use CodeIgniter\Filters\PerformanceMetrics;
use CodeIgniter\Filters\SecureHeaders;

class Filters extends BaseFilters
{
    public array $aliases = [
        'csrf'          => CSRF::class,
        'toolbar'       => DebugToolbar::class,
        'honeypot'      => Honeypot::class,
        'invalidchars'  => InvalidChars::class,
        'secureheaders' => SecureHeaders::class,
        'cors'          => Cors::class,
        'forcehttps'    => ForceHTTPS::class,
        'pagecache'     => PageCache::class,
        'performance'   => PerformanceMetrics::class,
        'auth'          => \App\Filters\AuthFilter::class,
    ];

    public array $required = [
        'before' => [
            'forcehttps', // Force HTTPS for all requests
            'pagecache',  // Enable web page caching
        ],
        'after' => [
            'pagecache',   // Enable web page caching
            'performance', // Track performance metrics
            'toolbar',     // Debug toolbar for development
        ],
    ];

    public array $globals = [
        'before' => [
            'honeypot',   // Protect against spam bots
            'csrf',       // Protect against CSRF attacks
        ],
        'after' => [
            'secureheaders', // Add secure headers to responses
        ],
    ];

    public array $methods = [];

    public array $filters = [
        'auth' => [
            'before' => [
                'checklist/*',          // Protect all checklist-related routes
                'checklist-item/*',     // Protect all checklist item-related routes
            ],
        ],
    ];
}