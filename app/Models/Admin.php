<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * @property int $id
 * @property string $username
 * @property string $password
 * @property string $name
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property AdminRole[]|\Illuminate\Database\Eloquent\Collection $roles
 * @property string $roles_text
 * @property array $attributes
 */
class Admin extends Authenticatable
{

    const GUARD = 'admin';

    protected $fillable = [
        'username', 'password', 'name',
    ];

    protected $hidden = [
        'password', 'remember_token'
    ];

    protected $appends = [
        'roles_text',
    ];

    /**
     * 关联角色组
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(AdminRole::class, 'admin_groups', 'admin_id', 'role_id');
    }

    /**
     * 获取所属角色
     * @return string
     */
    public function getRolesTextAttribute()
    {
        if (isset($this->roles) && ($count = $this->roles->count()) > 0) {
            $name = $this->roles->first()->name;
            if ($count == 1) {
                return $name;
            } else {
                return "{$name} 等{$count}个";
            }
        }

        return '无';
    }
}