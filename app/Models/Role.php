<?php

namespace App\Models;

use Spatie\Permission\Guard;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Exceptions\RoleDoesNotExist;
use Spatie\Permission\Exceptions\GuardDoesNotMatch;
use Spatie\Permission\Exceptions\RoleAlreadyExists;
use Spatie\Permission\Contracts\Role as RoleContract;
use Spatie\Permission\Traits\RefreshesPermissionCache;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model implements RoleContract
{
    use HasPermissions;
    use RefreshesPermissionCache;

    protected $guarded = ['id'];

    public function __construct(array $attributes = [])
    {
        $attributes['guard_name'] = $attributes['guard_name'] ?? config('auth.defaults.guard');

        parent::__construct($attributes);

        $this->setTable(config('permission.table_names.roles'));
    }

    public static function create(array $attributes = [])
    {
        $attributes['guard_name'] = $attributes['guard_name'] ?? Guard::getDefaultName(static::class);

        if (static::where('name', $attributes['name'])->where('guard_name', $attributes['guard_name'])->where('deleted_at', '=', null)->first()) {
            throw RoleAlreadyExists::create($attributes['name'], $attributes['guard_name']);
        }

        if (app()::VERSION < '5.4') {
            return parent::create($attributes);
        }

        return static::query()->create($attributes);
    }

    /**
     * A role may be given various permissions.
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(
            config('permission.models.permission'),
            config('permission.table_names.role_has_permissions'),
            'role_id',
            'permission_id'
        );
    }

    /**
     * A role belongs to some users of the model associated with its guard.
     */
    public function users(): MorphToMany
    {
        return $this->morphedByMany(
            getModelForGuard($this->attributes['guard_name']),
            'model',
            config('permission.table_names.model_has_roles'),
            'role_id',
            config('permission.column_names.model_morph_key')
        );
    }

    /**
     * Find a role by its name and guard name.
     *
     * @param string $name
     * @param string|null $guardName
     *
     * @return \Spatie\Permission\Contracts\Role|\Spatie\Permission\Models\Role
     *
     * @throws \Spatie\Permission\Exceptions\RoleDoesNotExist
     */
    public static function findByName(string $name, $guardName = null): RoleContract
    {
        $guardName = $guardName ?? Guard::getDefaultName(static::class);

        $role = static::where('name', $name)->where('guard_name', $guardName)->first();

        if (! $role) {
            throw RoleDoesNotExist::named($name);
        }

        return $role;
    }

    public static function findById(int $id, $guardName = null): RoleContract
    {
        $guardName = $guardName ?? Guard::getDefaultName(static::class);

        $role = static::where('id', $id)->where('guard_name', $guardName)->first();

        if (! $role) {
            throw RoleDoesNotExist::withId($id);
        }

        return $role;
    }

    /**
     * Find or create role by its name (and optionally guardName).
     *
     * @param string $name
     * @param string|null $guardName
     *
     * @return \Spatie\Permission\Contracts\Role
     */
    public static function findOrCreate(string $name, $guardName = null): RoleContract
    {
        $guardName = $guardName ?? Guard::getDefaultName(static::class);

        $role = static::where('name', $name)->where('guard_name', $guardName)->first();

        if (! $role) {
            return static::query()->create(['name' => $name, 'guard_name' => $guardName]);
        }

        return $role;
    }

    /**
     * Determine if the user may perform the given permission.
     *
     * @param string|Permission $permission
     *
     * @return bool
     *
     * @throws \Spatie\Permission\Exceptions\GuardDoesNotMatch
     */
    public function hasPermissionTo($permission): bool
    {
        $permissionClass = $this->getPermissionClass();

        if (is_string($permission)) {
            $permission = $permissionClass->findByName($permission, $this->getDefaultGuardName());
        }

        if (is_int($permission)) {
            $permission = $permissionClass->findById($permission, $this->getDefaultGuardName());
        }

        if (! $this->getGuardNames()->contains($permission->guard_name)) {
            throw GuardDoesNotMatch::create($permission->guard_name, $this->getGuardNames());
        }

        return $this->permissions->contains('id', $permission->id);
    }

    public function scopeGetRoles($model, $limit = null, $offset = null, $search = null, $filter = array(), $sort = array())
    {
        // Get only Roles list
        $roles = Role::select('id', 'name', 'is_default', 'type', 'roles.status')
        ->where(function ($query) use ($filter) {
            if (!empty($filter) && !empty($filter['name'])) 
            {
                $query->whereRaw('lower(name) LIKE ? ', [trim(strtolower($filter['name'])) . '%']);
            }
        });
        if(!(empty($search)))
        {
            $search = strtolower($search);
            $roles->whereRaw('(lower(roles.name) LIKE \'%'.$search.'%\')');
        }
        // ->where('deleted_at', '=', NULL)
        $roles->with('permissions');
        if(!(empty($sort)))
        {
            $arr_fields = array("name", "status","");
            for($field = 0; $field < count($arr_fields); $field++)
            {
                $sfield = $field+1;
                if($sort['column'] == $sfield && $arr_fields[$field] != "")
                {
                    $roles->orderBy($arr_fields[$field], $sort['dir']);
                }
            }
        }
        else
        {
            $roles->orderBy('id', 'DESC');
        }
        if(!empty($limit))
        {
            $roles->skip($offset);
            $roles->take($limit);
            return $roles->get()->toArray();
        }
        else
        {
            return $roles->get()->count();
        }
    }
}
