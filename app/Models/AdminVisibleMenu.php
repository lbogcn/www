<?php

namespace App\Models;

use Eloquent;

/**
 * @property int $id
 * @property int $role_id
 * @property string $route
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property AdminRole $role
 */
class AdminVisibleMenu extends Eloquent
{

    /**
     * 关联角色
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function role()
    {
        return $this->hasOne(AdminRole::class, 'id', 'role_id');
    }

}