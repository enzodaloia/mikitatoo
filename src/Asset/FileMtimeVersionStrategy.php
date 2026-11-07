<?php

namespace App\Asset;

use Symfony\Component\Asset\VersionStrategy\VersionStrategyInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

/**
 * Ajoute la date de modification du fichier aux URLs (?v=...) :
 * quand une image ou un CSS est remplacé, les navigateurs téléchargent la nouvelle version
 * au lieu de garder l'ancienne en cache.
 */
final class FileMtimeVersionStrategy implements VersionStrategyInterface
{
    public function __construct(
        #[Autowire('%kernel.project_dir%/public')]
        private readonly string $publicDir,
    ) {
    }

    public function getVersion(string $path): string
    {
        $file = $this->publicDir.'/'.ltrim(rawurldecode(parse_url($path, PHP_URL_PATH) ?? ''), '/');

        return is_file($file) ? (string) filemtime($file) : '';
    }

    public function applyVersion(string $path): string
    {
        $version = $this->getVersion($path);

        return '' === $version ? $path : $path.'?v='.$version;
    }
}
