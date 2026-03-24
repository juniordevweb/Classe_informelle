<?= $this->include('templates/header') ?>
<?= $this->include('templates/top_bar') ?>
<?= $this->include('templates/left_sidebar') ?> <br>

<div class="content-page">
    <div class="container mt-4">
        <br><br><br>

        <div class="d-flex justify-content-between mb-4">
            <h3>Gestion des Utilisateurs</h3> <br>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                Ajouter Utilisateur
            </button>
        </div>

        <table class="table table-bordered">
           <thead>
    <tr>
        <th>Nom</th>
        <th>Email</th>
        <th>Profil</th>
        <th width="150">Action</th>
    </tr>
</thead>
            <tbody>
<?php foreach($users as $user): ?>
    <tr>
        <td><?= $user['nom'] ?></td>
        <td><?= $user['email'] ?></td>
        <td>
            <?php
            $role = array_filter($profils, fn($p)=> $p['id']==$user['role_id']);
            echo $role ? array_values($role)[0]['nom_role'] : '';
            ?>
        </td>

        <!-- ACTION BUTTONS -->
       <td class="text-center">

    <!-- BLOQUER -->
    <button class="btn btn-warning btn-sm blockBtn"
            data-id="<?= $user['id'] ?>"
            data-nom="<?= $user['nom'] ?>"
            title="Bloquer">
        <i class="fa fa-user"></i>
    </button>

    <!-- MODIFIER -->
    <button class="btn btn-info btn-sm editBtn"
        data-id="<?= $user['id'] ?>"
        data-nom="<?= $user['nom'] ?>"
        data-email="<?= $user['email'] ?>"
        data-role="<?= $user['role_id'] ?>"
        title="Modifier">
    <i class="fa fa-edit"></i>
</button>

    <!-- SUPPRIMER -->
    <button class="btn btn-danger btn-sm deleteBtn"
            data-id="<?= $user['id'] ?>"
            data-nom="<?= $user['nom'] ?>"
            title="Supprimer">
         <i class="fa fa-trash"></i>
    </button>

</td>
    </tr>
<?php endforeach; ?>
</tbody>
        </table>
    </div>
</div>

<!-- Modal Ajouter Utilisateur -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content shadow-lg rounded-4">

      <form method="post" action="<?= base_url('users/save_user') ?>">
        
        <!-- Header -->
        <div class="modal-header bg-gradient-primary text-white border-0 rounded-top">
          <h5 class="modal-title">
            <i class="fa fa-user-plus me-2"></i> Ajouter Utilisateur
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>

        <!-- Body -->
        <div class="modal-body p-4">

          <div class="mb-3 form-floating">
            <input type="text" name="nom" class="form-control" id="userNom" placeholder="Nom" required>
            <label for="userNom"><i class="fa fa-user me-2"></i>Nom</label>
          </div>

          <div class="mb-3 form-floating">
            <input type="email" name="email" class="form-control" id="userEmail" placeholder="Email" required>
            <label for="userEmail"><i class="fa fa-envelope me-2"></i>Email</label>
          </div>

          <div class="mb-3 form-floating">
            <select name="role_id" class="form-select" id="userRole" required>
              <option value="">Sélectionner un profil</option>
              <?php foreach($profils as $p): ?>
                  <option value="<?= $p['id'] ?>"><?= $p['nom_role'] ?></option>
              <?php endforeach; ?>
            </select>
            <label for="userRole"><i class="fa fa-shield-alt me-2"></i>Profil</label>
          </div>

          <!-- Nouveau champ mot de passe -->
          <div class="mb-3 form-floating">
            <input type="text" name="password" class="form-control" id="userPassword" placeholder="Mot de passe" readonly>
            <label for="userPassword"><i class="fa fa-lock me-2"></i>Mot de passe</label>
          </div>

        </div>

        <!-- Footer -->
        <div class="modal-footer border-0 justify-content-between p-3">
          <button type="button" class="btn btn-outline-secondary btn-lg" data-bs-dismiss="modal">
            <i class="fa fa-times me-1"></i> Annuler
          </button>
          <button type="submit" class="btn btn-success btn-lg">
            <i class="fa fa-check me-1"></i> Enregistrer
          </button>
        </div>

      </form>

    </div>
  </div>
</div>

<!-- Script pour générer un mot de passe aléatoire -->
<script>
document.getElementById('addUserModal').addEventListener('show.bs.modal', function () {
    const passwordField = document.getElementById('userPassword');
    const randomPassword = Math.random().toString(36).slice(-8); // 8 caractères aléatoires
    passwordField.value = randomPassword;
});
</script>

<!-- MODAL MODIFIER UTILISATEUR -->
<div class="modal fade" id="editUserModal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form method="post" action="<?= base_url('users/update') ?>">
        <div class="modal-header bg-info text-white">
            <h5>Modifier Utilisateur</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
            <input type="hidden" name="id" id="edit_id">

            <input type="text" name="nom" id="edit_nom" 
                   class="form-control mb-3" placeholder="Nom" required>

            <input type="email" name="email" id="edit_email" 
                   class="form-control mb-3" placeholder="Email" required>

            <select name="role_id" id="edit_role"
                    class="form-select mb-3" required>
                <?php foreach($profils as $p): ?>
                    <option value="<?= $p['id'] ?>">
                        <?= $p['nom_role'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                Annuler
            </button>
            <button type="submit" class="btn btn-info">
                Mettre à jour
            </button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {

// ALERT AJOUT REUSSI

    <?php if(session()->getFlashdata('success')): ?>
        Swal.fire({
            icon: 'success',
            title: 'Succès !',
            html: "<?= session()->getFlashdata('success') ?>",
            confirmButtonColor: '#3085d6',
            timer: 3000,
            timerProgressBar: true,
            showConfirmButton: true
        });
    <?php endif; ?>

    <?php if(session()->getFlashdata('error')): ?>
        Swal.fire({
            icon: 'error',
            title: 'Erreur !',
            html: "<?= session()->getFlashdata('error') ?>",
            confirmButtonColor: '#d33',
            timer: 3000,
            timerProgressBar: true,
            showConfirmButton: true
        });
    <?php endif; ?>

//ALERT MODIFIER ET SUPPRIMER
    <?php if(session()->getFlashdata('success_update')): ?>
        Swal.fire({
            icon: 'success',
            title: 'Mis à jour !',
            text: "<?= session()->getFlashdata('success_update') ?>",
            timer: 2000,
            showConfirmButton: false
        });
    <?php endif; ?>

    <?php if(session()->getFlashdata('success_delete')): ?>
        Swal.fire({
            icon: 'success',
            title: 'Supprimé !',
            text: "<?= session()->getFlashdata('success_delete') ?>",
            timer: 2000,
            showConfirmButton: false
        });
    <?php endif; ?>

    // ===== OUVRIR MODAL EDIT =====
    document.querySelectorAll('.editBtn').forEach(button => {
        button.addEventListener('click', function() {

            document.getElementById('edit_id').value = this.dataset.id;
            document.getElementById('edit_nom').value = this.dataset.nom;
            document.getElementById('edit_email').value = this.dataset.email;
            document.getElementById('edit_role').value = this.dataset.role;

            let modal = new bootstrap.Modal(document.getElementById('editUserModal'));
            modal.show();
        });
    });


    // ================= BLOQUER =================
    document.querySelectorAll('.blockBtn').forEach(button => {
        button.addEventListener('click', function() {

            let id = this.dataset.id;
            let nom = this.dataset.nom;

            Swal.fire({
                title: 'Bloquer utilisateur ?',
                html: "Voulez-vous bloquer : <br><strong>" + nom + "</strong> ?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#f0ad4e',
                cancelButtonText: 'Annuler',
                confirmButtonText: 'Oui, bloquer'
            }).then((result) => {
                if(result.isConfirmed){
                    window.location.href = "<?= base_url('users/block/') ?>" + id;
                }
            });
        });
    });


  


    // ================= SUPPRIMER =================
    document.querySelectorAll('.deleteBtn').forEach(button => {
        button.addEventListener('click', function() {

            let id = this.dataset.id;
            let nom = this.dataset.nom;

            Swal.fire({
                title: 'Supprimer utilisateur ?',
                html: "Cette action est irréversible ! <br><strong>" + nom + "</strong>",
                icon: 'error',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonText: 'Annuler',
                confirmButtonText: 'Oui, supprimer'
            }).then((result) => {
                if(result.isConfirmed){
                    window.location.href = "<?= base_url('users/delete/') ?>" + id;
                }
            });
        });
    });

});
</script>

<?= $this->include('templates/footer') ?>
