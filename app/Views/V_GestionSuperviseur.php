<?= $this->include('templates/header') ?>
<?= $this->include('templates/top_bar') ?>
<?= $this->include('templates/left_sidebar') ?>
<?php
$user_permissions = $user_permissions ?? [];
$superviseurs = $superviseurs ?? [];

$hasSuperviseurPermission = static function (array $permissions, int $menuId, int $sousMenuId, int $permissionId): bool {
    foreach ($permissions as $permission) {
        $dbSousMenu = ((int) ($permission['sous_menu_id'] ?? 0) === 0)
            ? (int) ($permission['menu_id'] ?? 0)
            : (int) ($permission['sous_menu_id'] ?? 0);

        if (
            (int) ($permission['menu_id'] ?? 0) === $menuId &&
            $dbSousMenu === $sousMenuId &&
            (int) ($permission['permission_id'] ?? 0) === $permissionId
        ) {
            return true;
        }
    }

    return false;
};

$canAddSuperviseur = $hasSuperviseurPermission($user_permissions, 3, 3, 2);
$canEditSuperviseur = $hasSuperviseurPermission($user_permissions, 3, 3, 3);
$canDeleteSuperviseur = $hasSuperviseurPermission($user_permissions, 3, 3, 4);
$showSuperviseurActionsColumn = $canEditSuperviseur || $canDeleteSuperviseur;
?>
<br><br><br>
<style>
    .superviseur-modal .modal-dialog { margin: 1rem auto; }
    .superviseur-modal .modal-content {
        border: 0;
        overflow: hidden;
        border-radius: 22px;
        background: #f4f7fb;
        box-shadow: 0 24px 60px rgba(19, 42, 76, 0.18);
    }
    .superviseur-modal .superviseur-form { display: flex; flex-direction: column; min-height: 0; }
    .superviseur-modal .modal-header {
        padding: 1.35rem 1.5rem;
        border-bottom: 0;
        background: linear-gradient(135deg, #15355f 0%, #1d6697 52%, #29a0c9 100%);
    }
    .superviseur-modal .modal-title-wrap { display: flex; align-items: center; gap: 14px; }
    .superviseur-modal .modal-title-icon {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, 0.16);
        color: #fff;
        font-size: 20px;
        flex-shrink: 0;
    }
    .superviseur-modal .modal-subtitle { margin: 4px 0 0; color: rgba(255, 255, 255, 0.8); font-size: 13px; }
    .superviseur-modal .modal-body {
        padding: 1.5rem;
        max-height: calc(100vh - 220px);
        overflow-y: auto;
        background: radial-gradient(circle at top right, rgba(41, 160, 201, 0.09), transparent 30%), #f4f7fb;
    }
    .superviseur-modal .section-card {
        border: 1px solid #e2e9f3;
        border-radius: 18px;
        background: #fff;
        box-shadow: 0 12px 28px rgba(17, 41, 72, 0.06);
    }
    .superviseur-modal .section-card + .section-card { margin-top: 1rem; }
    .superviseur-modal .section-card .card-header { padding: 1rem 1.25rem 0; background: transparent; border: 0; }
    .superviseur-modal .section-title { margin: 0; font-size: 15px; font-weight: 700; color: #16365c; }
    .superviseur-modal .section-description { margin: 0.35rem 0 0; color: #748399; font-size: 12px; }
    .superviseur-modal .card-body { padding: 1.15rem 1.25rem 1.25rem; }
    .superviseur-modal .form-label { margin-bottom: 0.45rem; font-size: 13px; font-weight: 700; color: #4d5d73; }
    .superviseur-modal .form-control,
    .superviseur-modal .form-select {
        min-height: 48px;
        border-radius: 12px;
        border: 1px solid #d8e1ee;
        box-shadow: none;
        background-color: #fcfdff;
    }
    .superviseur-modal .form-control:focus,
    .superviseur-modal .form-select:focus {
        border-color: #1d6697;
        box-shadow: 0 0 0 0.2rem rgba(29, 102, 151, 0.14);
        background-color: #fff;
    }
    .superviseur-modal .modal-footer {
        position: sticky;
        bottom: 0;
        z-index: 2;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 0.75rem;
        padding: 1rem 1.5rem 1.25rem;
        border-top: 1px solid #e4ebf3;
        background: rgba(255, 255, 255, 0.97);
        box-shadow: 0 -10px 24px rgba(17, 41, 72, 0.08);
    }
    .superviseur-modal .footer-note { margin: 0; font-size: 12px; color: #7a8798; }
    .superviseur-modal .footer-actions { display: flex; gap: 0.75rem; flex-wrap: wrap; }
    .superviseur-modal .btn-footer { min-width: 150px; min-height: 46px; border-radius: 12px; font-weight: 600; }
    @media (max-width: 767.98px) {
        .superviseur-modal .modal-dialog { margin: 0.5rem; }
        .superviseur-modal .modal-body { padding: 1rem; max-height: calc(100vh - 180px); }
        .superviseur-modal .modal-footer { flex-direction: column; align-items: stretch; }
        .superviseur-modal .footer-actions { width: 100%; flex-direction: column-reverse; }
        .superviseur-modal .btn-footer { width: 100%; }
    }
</style>

<div class="content-page">
    <div class="container-fluid mt-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
                <div>
                    <h4 class="mb-1">Gestion des Superviseurs</h4>
                    <p class="text-muted mb-0">Suivi des superviseurs, de leur affectation et de leurs acces.</p>
                </div>
                <?php if ($canAddSuperviseur): ?>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSuperviseurModal">
                        <i class="fa fa-plus me-1"></i> Ajouter Superviseur
                    </button>
                <?php endif; ?>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Matricule</th>
                                <th>Nom complet</th>
                                <th>Sexe</th>
                                <th>Telephone</th>
                                <th>Email</th>
                                <th>Fonction</th>
                                <th>Affectation</th>
                                <th>Login</th>
                                <th>Statut</th>
                                <?php if ($showSuperviseurActionsColumn): ?>
                                    <th class="text-center" width="140">Actions</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($superviseurs): ?>
                                <?php foreach ($superviseurs as $superviseur): ?>
                                    <tr>
                                        <td><span class="fw-semibold"><?= esc($superviseur['matricule']) ?></span></td>
                                        <td><?= esc(trim(($superviseur['prenom'] ?? '') . ' ' . ($superviseur['nom'] ?? ''))) ?></td>
                                        <td><?= esc($superviseur['sexe']) ?></td>
                                        <td><?= esc($superviseur['telephone']) ?></td>
                                        <td><?= esc($superviseur['email']) ?></td>
                                        <td><span class="badge text-bg-light"><?= esc($superviseur['fonction']) ?></span></td>
                                        <td>
                                            <small class="text-muted">
                                                <?= esc($superviseur['structure_affectation']) ?><br>
                                                <?= esc($superviseur['region']) ?> / <?= esc($superviseur['departement']) ?><br>
                                                <?= esc($superviseur['date_affectation']) ?>
                                            </small>
                                        </td>
                                        <td><?= esc($superviseur['login']) ?></td>
                                        <td>
                                            <span class="badge <?= ($superviseur['statut'] ?? 'actif') === 'actif' ? 'text-bg-success' : 'text-bg-secondary' ?>">
                                                <?= esc($superviseur['statut'] ?? 'actif') ?>
                                            </span>
                                        </td>
                                        <?php if ($showSuperviseurActionsColumn): ?>
                                            <td class="text-center">
                                                <?php if ($canEditSuperviseur): ?>
                                                    <button type="button" class="btn btn-sm btn-outline-primary editSuperviseurBtn"
                                                        data-id="<?= esc($superviseur['id'], 'attr') ?>"
                                                        data-matricule="<?= esc($superviseur['matricule'], 'attr') ?>"
                                                        data-prenom="<?= esc($superviseur['prenom'], 'attr') ?>"
                                                        data-nom="<?= esc($superviseur['nom'], 'attr') ?>"
                                                        data-sexe="<?= esc($superviseur['sexe'], 'attr') ?>"
                                                        data-telephone="<?= esc($superviseur['telephone'], 'attr') ?>"
                                                        data-email="<?= esc($superviseur['email'], 'attr') ?>"
                                                        data-fonction="<?= esc($superviseur['fonction'], 'attr') ?>"
                                                        data-structure-affectation="<?= esc($superviseur['structure_affectation'], 'attr') ?>"
                                                        data-region="<?= esc($superviseur['region'], 'attr') ?>"
                                                        data-departement="<?= esc($superviseur['departement'], 'attr') ?>"
                                                        data-date-affectation="<?= esc($superviseur['date_affectation'], 'attr') ?>"
                                                        data-login="<?= esc($superviseur['login'], 'attr') ?>"
                                                        data-statut="<?= esc($superviseur['statut'], 'attr') ?>">
                                                        <i class="fa fa-edit"></i>
                                                    </button>
                                                <?php endif; ?>
                                                <?php if ($canDeleteSuperviseur): ?>
                                                    <button type="button" class="btn btn-sm btn-outline-danger deleteSuperviseurBtn"
                                                        data-id="<?= esc($superviseur['id'], 'attr') ?>"
                                                        data-nom="<?= esc(trim(($superviseur['prenom'] ?? '') . ' ' . ($superviseur['nom'] ?? '')), 'attr') ?>">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                <?php endif; ?>
                                            </td>
                                        <?php endif; ?>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="<?= $showSuperviseurActionsColumn ? '10' : '9' ?>" class="text-center text-muted py-4">Aucun superviseur enregistre.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if ($canAddSuperviseur): ?>
<div class="modal fade superviseur-modal" id="addSuperviseurModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow">
            <form method="post" action="<?= base_url('superviseur/save') ?>" class="superviseur-form">
                <div class="modal-header text-white">
                    <div class="modal-title-wrap">
                        <span class="modal-title-icon"><i class="fa fa-id-badge"></i></span>
                        <div>
                            <h5 class="modal-title mb-1">Ajouter un superviseur</h5>
                            <p class="modal-subtitle">Completez les informations personnelles, l'affectation et les acces du superviseur.</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="card section-card border-0">
                        <div class="card-header">
                            <h6 class="section-title">Identite et contact</h6>
                            <p class="section-description">Renseignez les informations d'identification du superviseur.</p>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-4"><label class="form-label">Matricule</label><input type="text" name="matricule" class="form-control" value="<?= esc(old('matricule')) ?>" required></div>
                                <div class="col-md-4"><label class="form-label">Prenom</label><input type="text" name="prenom" class="form-control" value="<?= esc(old('prenom')) ?>" required></div>
                                <div class="col-md-4"><label class="form-label">Nom</label><input type="text" name="nom" class="form-control" value="<?= esc(old('nom')) ?>" required></div>
                                <div class="col-md-4">
                                    <label class="form-label">Sexe</label>
                                    <select name="sexe" class="form-select" required>
                                        <option value="">Selectionner</option>
                                        <option value="Masculin" <?= old('sexe') === 'Masculin' ? 'selected' : '' ?>>Masculin</option>
                                        <option value="Feminin" <?= old('sexe') === 'Feminin' ? 'selected' : '' ?>>Feminin</option>
                                    </select>
                                </div>
                                <div class="col-md-4"><label class="form-label">Telephone</label><input type="text" name="telephone" class="form-control" value="<?= esc(old('telephone')) ?>" required></div>
                                <div class="col-md-4"><label class="form-label">Email</label><input type="email" name="email" class="form-control" value="<?= esc(old('email')) ?>"></div>
                            </div>
                        </div>
                    </div>
                    <div class="card section-card border-0">
                        <div class="card-header">
                            <h6 class="section-title">Affectation</h6>
                            <p class="section-description">Precisez la fonction et la zone administrative d'affectation.</p>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">Fonction</label>
                                    <select name="fonction" class="form-select" required>
                                        <option value="">Selectionner</option>
                                        <option value="IEF" <?= old('fonction') === 'IEF' ? 'selected' : '' ?>>IEF</option>
                                        <option value="IA" <?= old('fonction') === 'IA' ? 'selected' : '' ?>>IA</option>
                                        <option value="Central" <?= old('fonction') === 'Central' ? 'selected' : '' ?>>Central</option>
                                    </select>
                                </div>
                                <div class="col-md-4"><label class="form-label">Structure affectation</label><input type="text" name="structure_affectation" class="form-control" value="<?= esc(old('structure_affectation')) ?>" required></div>
                                <div class="col-md-4"><label class="form-label">Date affectation</label><input type="date" name="date_affectation" class="form-control" value="<?= esc(old('date_affectation')) ?>" required></div>
                                <div class="col-md-6"><label class="form-label">Region</label><input type="text" name="region" class="form-control" value="<?= esc(old('region')) ?>" required></div>
                                <div class="col-md-6"><label class="form-label">Departement</label><input type="text" name="departement" class="form-control" value="<?= esc(old('departement')) ?>" required></div>
                            </div>
                        </div>
                    </div>
                    <div class="card section-card border-0">
                        <div class="card-header">
                            <h6 class="section-title">Acces et statut</h6>
                            <p class="section-description">Configurez le login et l'etat du compte superviseur.</p>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-4"><label class="form-label">Login</label><input type="text" name="login" class="form-control" value="<?= esc(old('login')) ?>" required></div>
                                <div class="col-md-4"><label class="form-label">Mot de passe</label><input type="password" name="password" class="form-control" required></div>
                                <div class="col-md-4">
                                    <label class="form-label">Statut</label>
                                    <select name="statut" class="form-select" required>
                                        <option value="actif" <?= old('statut', 'actif') === 'actif' ? 'selected' : '' ?>>Actif</option>
                                        <option value="inactif" <?= old('statut') === 'inactif' ? 'selected' : '' ?>>Inactif</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <p class="footer-note">Le pied de page reste visible pour garder les actions accessibles meme sur un long formulaire.</p>
                    <div class="footer-actions">
                        <button type="button" class="btn btn-outline-secondary btn-footer" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary btn-footer"><i class="fa fa-check me-1"></i> Enregistrer</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<?php if ($canEditSuperviseur): ?>
<div class="modal fade superviseur-modal" id="editSuperviseurModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow">
            <form method="post" action="<?= base_url('superviseur/update') ?>" class="superviseur-form">
                <input type="hidden" name="id" id="edit_superviseur_id">
                <div class="modal-header text-white">
                    <div class="modal-title-wrap">
                        <span class="modal-title-icon"><i class="fa fa-edit"></i></span>
                        <div>
                            <h5 class="modal-title mb-1">Modifier un superviseur</h5>
                            <p class="modal-subtitle">Le mot de passe reste optionnel en modification pour eviter de l'ecraser inutilement.</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="card section-card border-0">
                        <div class="card-header">
                            <h6 class="section-title">Identite et contact</h6>
                            <p class="section-description">Mettez a jour les donnees personnelles et de communication.</p>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-4"><label class="form-label">Matricule</label><input type="text" name="matricule" id="edit_matricule" class="form-control" required></div>
                                <div class="col-md-4"><label class="form-label">Prenom</label><input type="text" name="prenom" id="edit_prenom" class="form-control" required></div>
                                <div class="col-md-4"><label class="form-label">Nom</label><input type="text" name="nom" id="edit_nom" class="form-control" required></div>
                                <div class="col-md-4">
                                    <label class="form-label">Sexe</label>
                                    <select name="sexe" id="edit_sexe" class="form-select" required>
                                        <option value="Masculin">Masculin</option>
                                        <option value="Feminin">Feminin</option>
                                    </select>
                                </div>
                                <div class="col-md-4"><label class="form-label">Telephone</label><input type="text" name="telephone" id="edit_telephone" class="form-control" required></div>
                                <div class="col-md-4"><label class="form-label">Email</label><input type="email" name="email" id="edit_email" class="form-control"></div>
                            </div>
                        </div>
                    </div>
                    <div class="card section-card border-0">
                        <div class="card-header">
                            <h6 class="section-title">Affectation</h6>
                            <p class="section-description">Ajustez la fonction, la structure et la localisation administrative.</p>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">Fonction</label>
                                    <select name="fonction" id="edit_fonction" class="form-select" required>
                                        <option value="IEF">IEF</option>
                                        <option value="IA">IA</option>
                                        <option value="Central">Central</option>
                                    </select>
                                </div>
                                <div class="col-md-4"><label class="form-label">Structure affectation</label><input type="text" name="structure_affectation" id="edit_structure_affectation" class="form-control" required></div>
                                <div class="col-md-4"><label class="form-label">Date affectation</label><input type="date" name="date_affectation" id="edit_date_affectation" class="form-control" required></div>
                                <div class="col-md-6"><label class="form-label">Region</label><input type="text" name="region" id="edit_region" class="form-control" required></div>
                                <div class="col-md-6"><label class="form-label">Departement</label><input type="text" name="departement" id="edit_departement" class="form-control" required></div>
                            </div>
                        </div>
                    </div>
                    <div class="card section-card border-0">
                        <div class="card-header">
                            <h6 class="section-title">Acces et statut</h6>
                            <p class="section-description">Si le mot de passe est vide, le mot de passe actuel est conserve.</p>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-4"><label class="form-label">Login</label><input type="text" name="login" id="edit_login" class="form-control" required></div>
                                <div class="col-md-4"><label class="form-label">Nouveau mot de passe</label><input type="password" name="password" id="edit_password" class="form-control" placeholder="Laisser vide pour conserver"></div>
                                <div class="col-md-4">
                                    <label class="form-label">Statut</label>
                                    <select name="statut" id="edit_statut" class="form-select" required>
                                        <option value="actif">Actif</option>
                                        <option value="inactif">Inactif</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <p class="footer-note">Toutes les actions restent visibles pendant le scroll pour eviter les boutons coupes.</p>
                    <div class="footer-actions">
                        <button type="button" class="btn btn-outline-secondary btn-footer" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary btn-footer"><i class="fa fa-save me-1"></i> Modifier</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', function () {
    <?php if (session()->getFlashdata('success')): ?>
        Swal.fire({ icon: 'success', title: 'Succes', text: "<?= esc(session()->getFlashdata('success'), 'js') ?>" });
    <?php endif; ?>
    <?php if (session()->getFlashdata('success_delete')): ?>
        Swal.fire({ icon: 'success', title: 'Succes', text: "<?= esc(session()->getFlashdata('success_delete'), 'js') ?>" });
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        Swal.fire({ icon: 'error', title: 'Erreur', text: "<?= esc(session()->getFlashdata('error'), 'js') ?>" }).then(() => {
            const addModal = document.getElementById('addSuperviseurModal');
            if (addModal) {
                new bootstrap.Modal(addModal).show();
            }
        });
    <?php endif; ?>

    <?php if ($canEditSuperviseur): ?>
        document.querySelectorAll('.editSuperviseurBtn').forEach(function (button) {
            button.addEventListener('click', function () {
                document.getElementById('edit_superviseur_id').value = this.dataset.id || '';
                document.getElementById('edit_matricule').value = this.dataset.matricule || '';
                document.getElementById('edit_prenom').value = this.dataset.prenom || '';
                document.getElementById('edit_nom').value = this.dataset.nom || '';
                document.getElementById('edit_sexe').value = this.dataset.sexe || 'Masculin';
                document.getElementById('edit_telephone').value = this.dataset.telephone || '';
                document.getElementById('edit_email').value = this.dataset.email || '';
                document.getElementById('edit_fonction').value = this.dataset.fonction || 'IEF';
                document.getElementById('edit_structure_affectation').value = this.dataset.structureAffectation || '';
                document.getElementById('edit_region').value = this.dataset.region || '';
                document.getElementById('edit_departement').value = this.dataset.departement || '';
                document.getElementById('edit_date_affectation').value = this.dataset.dateAffectation || '';
                document.getElementById('edit_login').value = this.dataset.login || '';
                document.getElementById('edit_password').value = '';
                document.getElementById('edit_statut').value = this.dataset.statut || 'actif';
                new bootstrap.Modal(document.getElementById('editSuperviseurModal')).show();
            });
        });
    <?php endif; ?>

    <?php if ($canDeleteSuperviseur): ?>
        document.querySelectorAll('.deleteSuperviseurBtn').forEach(function (button) {
            button.addEventListener('click', function () {
                const id = this.dataset.id;
                const nom = this.dataset.nom || 'ce superviseur';
                Swal.fire({
                    title: 'Supprimer superviseur ?',
                    html: "Cette action est irreversible.<br><strong>" + nom + "</strong>",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Oui, supprimer',
                    cancelButtonText: 'Annuler',
                    confirmButtonColor: '#dc3545'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "<?= base_url('superviseur/delete/') ?>" + id;
                    }
                });
            });
        });
    <?php endif; ?>
});
</script>

<?= $this->include('templates/footer') ?>
