<?php

namespace App\Models;

use Eloquent;

/**
 * @property int id
 * @property string $group
 * @property string $node
 * @property string $route
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property AdminMenu[]|\Illuminate\Database\Eloquent\Collection $menus
 */
class AdminNode extends Eloquent
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'node', 'route', 'group'
    ];

    /**
     * 菜单
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function menus()
    {
        return $this->hasMany(AdminMenu::class, 'node_id');
    }

}