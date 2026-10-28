<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetSpatieTeamFromTenant
{
    public function handle(Request $request, Closure $next): Response
    {
        // Filament ya resolvió el tenant y lo inyectó en el contenedor.
        $tenant = filament()->getTenant();

        if ($tenant !== null) {
            // setPermissionsTeamId() es la función helper global de Spatie.
            setPermissionsTeamId($tenant->getKey());
        }

        return $next($request);
    }
}
