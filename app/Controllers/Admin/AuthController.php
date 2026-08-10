<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use Config\Database;

class AuthController extends BaseController
{
    public function index()
    {
        // If already logged in, redirect to dashboard
        if (session()->get('admin_logged_in')) {
            return redirect()->to('/admin/allotment');
        }
        return view('admin/login');
    }

    public function authenticate()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $db = Database::connect();
        
        // Ensure table exists for safety, though it should be migrated
        if (!$db->tableExists('admin_users')) {
            // Seed a default user for testing if table doesn't exist
            $forge = \Config\Database::forge();
            $forge->addField([
                'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
                'username' => ['type' => 'VARCHAR', 'constraint' => '50', 'unique' => true],
                'password' => ['type' => 'VARCHAR', 'constraint' => '255']
            ]);
            $forge->addKey('id', true);
            $forge->createTable('admin_users', true);
            
            $db->table('admin_users')->insert([
                'username' => 'admin',
                'password' => password_hash('admin123', PASSWORD_BCRYPT)
            ]);
        }

        $user = $db->table('admin_users')->where('username', $username)->get()->getRow();

        if ($user && password_verify($password, $user->password)) {
            session()->set([
                'admin_logged_in' => true,
                'admin_username'  => $user->username
            ]);
            return redirect()->to('/admin/allotment');
        } else {
            return redirect()->back()->with('error', 'Invalid Credentials');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/admin/login');
    }
}
