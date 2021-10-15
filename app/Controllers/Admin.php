<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Admin extends BaseController
{
    protected $db;
    protected $builder;

    function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->builder = $this->db->table('users');
    }

    public function index()
    {
        // $users = new \Myth\Auth\Models\UserModel();
        $this->builder->select('users.id AS userid, username, email, name');
        $this->builder->join('auth_groups_users', 'auth_groups_users.user_id = users.id');
        $this->builder->join('auth_groups', 'auth_groups.id = auth_groups_users.group_id');
        $result = $this->builder->get();

        $data = [
            'title' => 'User List',
            'users' => $result->getResult()
        ];

        return view('admin/index', $data);
    }

    public function detail($id = 0)
    {
        // $users = new \Myth\Auth\Models\UserModel();
        $this->builder->select('users.id AS userid, username, email, fullname, user_image, name');
        $this->builder->join('auth_groups_users', 'auth_groups_users.user_id = users.id');
        $this->builder->join('auth_groups', 'auth_groups.id = auth_groups_users.group_id');
        $this->builder->where('users.id', $id);
        $result = $this->builder->get();

        $data = [
            'title' => 'User List',
            'user' => $result->getRow()
        ];

        if (empty($data['user'])) {
            return redirect()->to(site_url('admin'));
        }

        return view('admin/detail', $data);
    }
}