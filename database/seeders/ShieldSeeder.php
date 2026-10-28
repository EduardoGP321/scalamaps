<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use BezhanSalleh\FilamentShield\Support\Utils;
use Spatie\Permission\PermissionRegistrar;

class ShieldSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $tenants = '[{"id":1,"nombre":"SCALA","logo":"empresas\\/logo\\/01KREM9E3D69R2AX68TPQ0AJ4B.webp","es_externa":0,"created_at":null,"updated_at":"2026-05-12T17:38:23.000000Z","deleted_at":"2026-05-11 14:47:03"},{"id":2,"nombre":"ROCHER INGENIER\\u00cdA S.A. DE C.V","logo":"empresas\\/logo\\/01KREME9HJSNVJH70FPGC4YR5Z.webp","es_externa":1,"created_at":"2026-05-12T17:41:02.000000Z","updated_at":"2026-05-12T17:41:02.000000Z","deleted_at":null}]';
        $users = '[]';
        $userTenantPivot = '[]';
        $rolesWithPermissions = '[{"name":"super_admin","guard_name":"web","permissions":["ViewAny:Activo","View:Activo","Create:Activo","Update:Activo","Delete:Activo","DeleteAny:Activo","Restore:Activo","ForceDelete:Activo","ForceDeleteAny:Activo","RestoreAny:Activo","Replicate:Activo","Reorder:Activo","ViewAny:Carretera","View:Carretera","Create:Carretera","Update:Carretera","Delete:Carretera","DeleteAny:Carretera","Restore:Carretera","ForceDelete:Carretera","ForceDeleteAny:Carretera","RestoreAny:Carretera","Replicate:Carretera","Reorder:Carretera","ViewAny:CatalogoConcepto","View:CatalogoConcepto","Create:CatalogoConcepto","Update:CatalogoConcepto","Delete:CatalogoConcepto","DeleteAny:CatalogoConcepto","Restore:CatalogoConcepto","ForceDelete:CatalogoConcepto","ForceDeleteAny:CatalogoConcepto","RestoreAny:CatalogoConcepto","Replicate:CatalogoConcepto","Reorder:CatalogoConcepto","ViewAny:Contrato","View:Contrato","Create:Contrato","Update:Contrato","Delete:Contrato","DeleteAny:Contrato","Restore:Contrato","ForceDelete:Contrato","ForceDeleteAny:Contrato","RestoreAny:Contrato","Replicate:Contrato","Reorder:Contrato","ViewAny:Dependencia","View:Dependencia","Create:Dependencia","Update:Dependencia","Delete:Dependencia","DeleteAny:Dependencia","Restore:Dependencia","ForceDelete:Dependencia","ForceDeleteAny:Dependencia","RestoreAny:Dependencia","Replicate:Dependencia","Reorder:Dependencia","ViewAny:Empresa","View:Empresa","Create:Empresa","Update:Empresa","Delete:Empresa","DeleteAny:Empresa","Restore:Empresa","ForceDelete:Empresa","ForceDeleteAny:Empresa","RestoreAny:Empresa","Replicate:Empresa","Reorder:Empresa","ViewAny:ParteObra","View:ParteObra","Create:ParteObra","Update:ParteObra","Delete:ParteObra","DeleteAny:ParteObra","Restore:ParteObra","ForceDelete:ParteObra","ForceDeleteAny:ParteObra","RestoreAny:ParteObra","Replicate:ParteObra","Reorder:ParteObra","ViewAny:Partida","View:Partida","Create:Partida","Update:Partida","Delete:Partida","DeleteAny:Partida","Restore:Partida","ForceDelete:Partida","ForceDeleteAny:Partida","RestoreAny:Partida","Replicate:Partida","Reorder:Partida","ViewAny:TipoActivo","View:TipoActivo","Create:TipoActivo","Update:TipoActivo","Delete:TipoActivo","DeleteAny:TipoActivo","Restore:TipoActivo","ForceDelete:TipoActivo","ForceDeleteAny:TipoActivo","RestoreAny:TipoActivo","Replicate:TipoActivo","Reorder:TipoActivo","ViewAny:Tramo","View:Tramo","Create:Tramo","Update:Tramo","Delete:Tramo","DeleteAny:Tramo","Restore:Tramo","ForceDelete:Tramo","ForceDeleteAny:Tramo","RestoreAny:Tramo","Replicate:Tramo","Reorder:Tramo","ViewAny:User","View:User","Create:User","Update:User","Delete:User","DeleteAny:User","Restore:User","ForceDelete:User","ForceDeleteAny:User","RestoreAny:User","Replicate:User","Reorder:User","ViewAny:Role","View:Role","Create:Role","Update:Role","Delete:Role","DeleteAny:Role","Restore:Role","ForceDelete:Role","ForceDeleteAny:Role","RestoreAny:Role","Replicate:Role","Reorder:Role","View:Dashboard","View:MapaGlobal","View:Reportes"],"empresa_id":1},{"name":"super_admin","guard_name":"web","permissions":["ViewAny:Activo","View:Activo","Create:Activo","Update:Activo","Delete:Activo","DeleteAny:Activo","Restore:Activo","ForceDelete:Activo","ForceDeleteAny:Activo","RestoreAny:Activo","Replicate:Activo","Reorder:Activo","ViewAny:Carretera","View:Carretera","Create:Carretera","Update:Carretera","Delete:Carretera","DeleteAny:Carretera","Restore:Carretera","ForceDelete:Carretera","ForceDeleteAny:Carretera","RestoreAny:Carretera","Replicate:Carretera","Reorder:Carretera","ViewAny:CatalogoConcepto","View:CatalogoConcepto","Create:CatalogoConcepto","Update:CatalogoConcepto","Delete:CatalogoConcepto","DeleteAny:CatalogoConcepto","Restore:CatalogoConcepto","ForceDelete:CatalogoConcepto","ForceDeleteAny:CatalogoConcepto","RestoreAny:CatalogoConcepto","Replicate:CatalogoConcepto","Reorder:CatalogoConcepto","ViewAny:Contrato","View:Contrato","Create:Contrato","Update:Contrato","Delete:Contrato","DeleteAny:Contrato","Restore:Contrato","ForceDelete:Contrato","ForceDeleteAny:Contrato","RestoreAny:Contrato","Replicate:Contrato","Reorder:Contrato","ViewAny:Dependencia","View:Dependencia","Create:Dependencia","Update:Dependencia","Delete:Dependencia","DeleteAny:Dependencia","Restore:Dependencia","ForceDelete:Dependencia","ForceDeleteAny:Dependencia","RestoreAny:Dependencia","Replicate:Dependencia","Reorder:Dependencia","ViewAny:Empresa","View:Empresa","Create:Empresa","Update:Empresa","Delete:Empresa","DeleteAny:Empresa","Restore:Empresa","ForceDelete:Empresa","ForceDeleteAny:Empresa","RestoreAny:Empresa","Replicate:Empresa","Reorder:Empresa","ViewAny:ParteObra","View:ParteObra","Create:ParteObra","Update:ParteObra","Delete:ParteObra","DeleteAny:ParteObra","Restore:ParteObra","ForceDelete:ParteObra","ForceDeleteAny:ParteObra","RestoreAny:ParteObra","Replicate:ParteObra","Reorder:ParteObra","ViewAny:Partida","View:Partida","Create:Partida","Update:Partida","Delete:Partida","DeleteAny:Partida","Restore:Partida","ForceDelete:Partida","ForceDeleteAny:Partida","RestoreAny:Partida","Replicate:Partida","Reorder:Partida","ViewAny:TipoActivo","View:TipoActivo","Create:TipoActivo","Update:TipoActivo","Delete:TipoActivo","DeleteAny:TipoActivo","Restore:TipoActivo","ForceDelete:TipoActivo","ForceDeleteAny:TipoActivo","RestoreAny:TipoActivo","Replicate:TipoActivo","Reorder:TipoActivo","ViewAny:Tramo","View:Tramo","Create:Tramo","Update:Tramo","Delete:Tramo","DeleteAny:Tramo","Restore:Tramo","ForceDelete:Tramo","ForceDeleteAny:Tramo","RestoreAny:Tramo","Replicate:Tramo","Reorder:Tramo","ViewAny:User","View:User","Create:User","Update:User","Delete:User","DeleteAny:User","Restore:User","ForceDelete:User","ForceDeleteAny:User","RestoreAny:User","Replicate:User","Reorder:User","ViewAny:Role","View:Role","Create:Role","Update:Role","Delete:Role","DeleteAny:Role","Restore:Role","ForceDelete:Role","ForceDeleteAny:Role","RestoreAny:Role","Replicate:Role","Reorder:Role","View:Dashboard","View:MapaGlobal","View:Reportes"],"empresa_id":2}]';
        $directPermissions = '[]';

        // 1. Seed tenants first (if present)
        if (! blank($tenants) && $tenants !== '[]') {
            static::seedTenants($tenants);
        }

        // 2. Seed roles with permissions
        static::makeRolesWithPermissions($rolesWithPermissions);

        // 3. Seed direct permissions
        static::makeDirectPermissions($directPermissions);

        // 4. Seed users with their roles/permissions (if present)
        if (! blank($users) && $users !== '[]') {
            static::seedUsers($users);
        }

        // 5. Seed user-tenant pivot (if present)
        if (! blank($userTenantPivot) && $userTenantPivot !== '[]') {
            static::seedUserTenantPivot($userTenantPivot);
        }

        $this->command->info('Shield Seeding Completed.');
    }

    protected static function seedTenants(string $tenants): void
    {
        if (blank($tenantData = json_decode($tenants, true))) {
            return;
        }

        $tenantModel = 'App\Models\Empresa';
        if (blank($tenantModel)) {
            return;
        }

        foreach ($tenantData as $tenant) {
            $tenantModel::firstOrCreate(
                ['id' => $tenant['id']],
                $tenant
            );
        }
    }

    protected static function seedUsers(string $users): void
    {
        if (blank($userData = json_decode($users, true))) {
            return;
        }

        $userModel = 'App\Models\User';
        $tenancyEnabled = true;

        foreach ($userData as $data) {
            // Extract role/permission data before creating user
            $roles = $data['roles'] ?? [];
            $permissions = $data['permissions'] ?? [];
            $tenantRoles = $data['tenant_roles'] ?? [];
            $tenantPermissions = $data['tenant_permissions'] ?? [];
            unset($data['roles'], $data['permissions'], $data['tenant_roles'], $data['tenant_permissions']);

            $user = $userModel::firstOrCreate(
                ['email' => $data['email']],
                $data
            );

            // Handle tenancy mode - sync roles/permissions per tenant
            if ($tenancyEnabled && (! empty($tenantRoles) || ! empty($tenantPermissions))) {
                foreach ($tenantRoles as $tenantId => $roleNames) {
                    $contextId = $tenantId === '_global' ? null : $tenantId;
                    setPermissionsTeamId($contextId);
                    $user->syncRoles($roleNames);
                }

                foreach ($tenantPermissions as $tenantId => $permissionNames) {
                    $contextId = $tenantId === '_global' ? null : $tenantId;
                    setPermissionsTeamId($contextId);
                    $user->syncPermissions($permissionNames);
                }
            } else {
                // Non-tenancy mode
                if (! empty($roles)) {
                    $user->syncRoles($roles);
                }

                if (! empty($permissions)) {
                    $user->syncPermissions($permissions);
                }
            }
        }
    }

    protected static function seedUserTenantPivot(string $pivot): void
    {
        if (blank($pivotData = json_decode($pivot, true))) {
            return;
        }

        $pivotTable = 'empresa_user';
        if (blank($pivotTable)) {
            return;
        }

        foreach ($pivotData as $row) {
            $uniqueKeys = [];

            if (isset($row['user_id'])) {
                $uniqueKeys['user_id'] = $row['user_id'];
            }

            $tenantForeignKey = 'empresa_id';
            if (! blank($tenantForeignKey) && isset($row[$tenantForeignKey])) {
                $uniqueKeys[$tenantForeignKey] = $row[$tenantForeignKey];
            }

            if (! empty($uniqueKeys)) {
                DB::table($pivotTable)->updateOrInsert($uniqueKeys, $row);
            }
        }
    }

    protected static function makeRolesWithPermissions(string $rolesWithPermissions): void
    {
        if (blank($rolePlusPermissions = json_decode($rolesWithPermissions, true))) {
            return;
        }

        /** @var \Illuminate\Database\Eloquent\Model $roleModel */
        $roleModel = Utils::getRoleModel();
        /** @var \Illuminate\Database\Eloquent\Model $permissionModel */
        $permissionModel = Utils::getPermissionModel();

        $tenancyEnabled = true;
        $teamForeignKey = 'empresa_id';

        foreach ($rolePlusPermissions as $rolePlusPermission) {
            $tenantId = $rolePlusPermission[$teamForeignKey] ?? null;

            // Set tenant context for role creation and permission sync
            if ($tenancyEnabled) {
                setPermissionsTeamId($tenantId);
            }

            $roleData = [
                'name' => $rolePlusPermission['name'],
                'guard_name' => $rolePlusPermission['guard_name'],
            ];

            // Include tenant ID in role data (can be null for global roles)
            if ($tenancyEnabled && ! blank($teamForeignKey)) {
                $roleData[$teamForeignKey] = $tenantId;
            }

            $role = $roleModel::firstOrCreate($roleData);

            if (! blank($rolePlusPermission['permissions'])) {
                $permissionModels = collect($rolePlusPermission['permissions'])
                    ->map(fn ($permission) => $permissionModel::firstOrCreate([
                        'name' => $permission,
                        'guard_name' => $rolePlusPermission['guard_name'],
                    ]))
                    ->all();

                $role->syncPermissions($permissionModels);
            }
        }
    }

    public static function makeDirectPermissions(string $directPermissions): void
    {
        if (blank($permissions = json_decode($directPermissions, true))) {
            return;
        }

        /** @var \Illuminate\Database\Eloquent\Model $permissionModel */
        $permissionModel = Utils::getPermissionModel();

        foreach ($permissions as $permission) {
            if ($permissionModel::whereName($permission['name'])->doesntExist()) {
                $permissionModel::create([
                    'name' => $permission['name'],
                    'guard_name' => $permission['guard_name'],
                ]);
            }
        }
    }
}
