<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Database;

class PermissionFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        $role_id = session()->get('role_id');
        if (!$role_id) {
            return redirect()->to('/login');
        }

        if (empty($arguments) || count($arguments) < 3) {
            return;
        }

        [$menuId, $sousMenuId, $permissionId] = $arguments;

        $db = Database::connect();

        $permission = $db->table('role_permissions')
            ->where('role_id', $role_id)
            ->where('menu_id', $menuId)
            ->where('permission_id', $permissionId)
            ->groupStart()
                ->where('sous_menu_id', $sousMenuId)
                ->orWhere('sous_menu_id', 0)
            ->groupEnd()
            ->get()
            ->getRowArray();

        if (!$permission) {
            return redirect()->to('/dashboard')->with('error', 'Accès refusé');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
