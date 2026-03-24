<?php

namespace App\Controllers;

class C_DashboardController extends BaseController
{
    public function index()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        $data['user_permissions'] = $this->getUserPermissions();

        return view('V_dashboard', $data);
    }
}
