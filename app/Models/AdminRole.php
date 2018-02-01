<?php

namespace App\Models;

use Eloquent;

/**
 * @property int $id
 * @property string $role
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property AdminNode|\Illuminate\Database\Eloquent\Collection $permissions
 */
class AdminRole extends Eloquent
{

    protected $fillable = ['role', 'name'];

    /**
     * 权限组
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions()
    {
        return $this->belongsToMany(AdminNode::class, 'admin_permissions', 'role_id', 'node_id');
    }


}