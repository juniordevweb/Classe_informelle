<?php namespace App\Controllers;

use App\Models\M_UserModel;

class C_AuthController extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new M_UserModel();
    }

    public function login()
    {
        $data['user_permissions'] = $this->getUserPermissions();
        
        return view('V_connexion');
    }

  

public function process()
{
    $email = trim($this->request->getPost('email'));
    $password = trim($this->request->getPost('password'));

    $user = $this->userModel->where('email', $email)->first();

    if(!$user || !password_verify($password, $user['password'])){
        return redirect()->back()->with('error', 'Email ou mot de passe incorrect');
    }

    // Connexion DB
    $db = \Config\Database::connect();

    // Récupérer le rôle
    $role = $db->table('roles')
               ->select('nom_role')
               ->where('id', $user['role_id'])
               ->get()
               ->getRowArray();

    // Nom du rôle sécurisé
    $roleName = $role ? $role['nom_role'] : 'Invité';

    // Session
    session()->set([
        'user_id'   => $user['id'],
        'nom'       => $user['nom'],
        'email'     => $user['email'],
        'role_id'   => $user['role_id'],
        'role'      => $roleName,
        'logged_in' => true
    ]);

  

    return redirect()->to('/dashboard');
}
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }

    

    
}