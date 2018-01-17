<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * @property int $id
 * @property string $username
 * @property string $password
 * @property string $name
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 * @property AdminRole[]|\Illuminate\Database\Eloquent\Collection $roles
 */
class Admin extends Authenticatable
{

    const GUARD = 'admin';

    use SoftDeletes;

    protected $fillable = [
        'username', 'password', 'name',
    ];

    protected $hidden = [
        'password', 'remember_token', 'deleted_at'
    ];

    /**
     * 关联角色组
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(AdminRole::class, 'admin_groups', 'admin_id', 'role_id');
    }

}