<?php

use Illuminate\Support\Arr;

if (!function_exists('getSiteConfig')) {
    /**
     * Get site configuration by project source key (e.g. L0, L1, lpc_en, etc.)
     *
     * @param  string  $projectSource
     * @return array
     * @throws \Exception
     */
    function getSiteConfig(string $projectSource): array
    {
        $sites = config('services.sites');

        if (isset($sites[$projectSource])) {
            return $sites[$projectSource];
        }
        $site = collect($sites)->firstWhere('project', $projectSource);

        if (!$site) {
            throw new \Exception("Site configuration not found for project source: {$projectSource}");
        }

        return $site;
    }
}
