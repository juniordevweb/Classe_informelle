<?php namespace App\Controllers;

use App\Models\M_UserModel;
use App\Models\M_RoleModel;

class C_UserController extends BaseController
{
    protected $userModel;
    protected $roleModel;

    public function __construct()
    {
        $this->userModel = new M_UserModel();
        $this->roleModel = new M_RoleModel();
    }

    public function index()
    { 
        $data['user_permissions'] = $this->getUserPermissions();
        $data['users'] = $this->userModel->findAll();
        $data['profils'] = $this->roleModel->findAll();
        return view('V_GestionUser', $data);
    }

    // ================= AJOUTER =================
    public function save_user()
    {
        $nom = $this->request->getPost('nom');
        $email = $this->request->getPost('email');
        $role_id = $this->request->getPost('role_id');

        if(!$nom || !$email || !$role_id){
            return redirect()->back()
                ->with('error', 'Tous les champs sont obligatoires.');
        }

       $rawPassword = $this->request->getPost('password');
$hashedPassword = password_hash($rawPassword, PASSWORD_DEFAULT);

        $this->userModel->save([
            'nom' => $nom,
            'email' => $email,
            'password' => $hashedPassword,
            'role_id' => $role_id,
            'status' => 1 // 1 = actif
        ]);

        return redirect()->to('/users')
            ->with('success', "Utilisateur créé. Mot de passe: $rawPassword");
    }

    // ================= BLOQUER =================
    public function block($id)
    {
        $user = $this->userModel->find($id);

        if(!$user){
            return redirect()->to('/users')
                ->with('error', 'Utilisateur introuvable.');
        }

        $newStatus = ($user['status'] == 1) ? 0 : 1;

        $this->userModel->update($id, [
            'status' => $newStatus
        ]);

        $message = $newStatus == 0 
            ? "Utilisateur bloqué avec succès."
            : "Utilisateur réactivé avec succès.";

        return redirect()->to('/users')->with('success', $message);
    }

    // ================= SUPPRIMER =================
  public function delete($id)
{
    $this->userModel->delete($id);
    return redirect()->to('/users')->with('success_delete', 'Utilisateur supprimé avec succès !');
}

  

    // ================= UPDATE =================
public function update()
{
    $id = $this->request->getPost('id');

    $this->userModel->update($id, [
        'nom' => $this->request->getPost('nom'),
        'email' => $this->request->getPost('email'),
        'role_id' => $this->request->getPost('role_id')
    ]);

    return redirect()->to('/users')->with('success_update', 'Utilisateur modifié avec succès !');
}
}