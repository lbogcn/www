<?php

namespace App\Console\Commands;

use App\Components\RbacNode;
use App\Models\Admin;
use App\Models\AdminNode;
use App\Models\AdminRole;
use Illuminate\Console\Command;

class AdminInstall extends Command
{

    protected $signature = 'admin:install';

    protected $description = '管理后台-初始化安装';

    /** @var RbacNode */
    private $rbacNode;

    /**
     * AdminInstall constructor.
     * @param RbacNode $rbacNode
     */
    public function __construct(RbacNode $rbacNode)
    {
        parent::__construct();

        $this->rbacNode = $rbacNode;
    }

    /**
     * 处理
     * @throws \Exception
     * @throws \Throwable
     */
    public function handle()
    {
        $this->rbacNode->update();
        $this->info('同步节点');

        $role = $this->createRole();
        $this->info('创建角色');
        dump($role->toArray());

        $admin = $this->createAdmin($role);
        $this->info('创建用户');
        dump($admin->toArray());
    }

    /**
     * 创建用户
     * @param AdminRole $role
     * @return Admin
     */
    private function createAdmin(AdminRole $role)
    {
        $data = array(
            'username' => 'admin',
            'name' => 'admin用户',
            'password' => 'admin',
        );
        $data['password'] = bcrypt($data['password']);

        /** @var Admin $user */
        $user = Admin::create($data);

        $user->roles()->attach([$role->id]);

        return $user;
    }

    /**
     * 创建角色
     * @return AdminRole
     */
    private function createRole()
    {
        $data = array(
            'role' => 'admin',
            'name' => '超级管理员',
        );

        $role = AdminRole::create($data);
        $nodes = AdminNode::select(['id'])->get()->toArray();
        $nodeIds = array_column($nodes, 'id');
        $role->permissions()->attach($nodeIds);

        return $role;
    }
}