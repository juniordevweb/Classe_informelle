<?= $this->include('templates/header') ?>
<?= $this->include('templates/top_bar') ?>
<?= $this->include('templates/left_sidebar') ?>
<?php
$user_permissions = $user_permissions ?? [];
$operateurs = $operateurs ?? [];
$structures = $structures ?? [];

$hasOperateurPermission = static function (array $permissions, int $menuId, int $sousMenuId, int $permissionId): bool {
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

$canAddOperateur = $hasOperateurPermission($user_permissions, 4, 4, 2);
$canEditOperateur = $hasOperateurPermission($user_permissions, 4, 4, 3);
$canDeleteOperateur = $hasOperateurPermission($user_permissions, 4, 4, 4);
$showOperateurActionsColumn = $canEditOperateur || $canDeleteOperateur;
?>
<br><br><br>
<style>
    .operateur-modal .modal-dialog {
        margin: 1rem auto;
    }

    .operateur-modal .modal-content {
        border: 0;
        overflow: hidden;
        border-radius: 22px;
        background: #f4f7fb;
        box-shadow: 0 24px 60px rgba(19, 42, 76, 0.18);
    }

    .operateur-modal .operateur-form {
        display: flex;
        flex-direction: column;
        min-height: 0;
    }

    .operateur-modal .modal-header {
        padding: 1.35rem 1.5rem;
        border-bottom: 0;
        background: linear-gradient(135deg, #113564 0%, #1f5faa 55%, #2d7be0 100%);
    }

    .operateur-modal .modal-title-wrap {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .operateur-modal .modal-title-icon {
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

    .operateur-modal .modal-subtitle {
        margin: 4px 0 0;
        color: rgba(255, 255, 255, 0.78);
        font-size: 13px;
    }

    .operateur-modal .modal-body {
        padding: 1.5rem;
        max-height: calc(100vh - 220px);
        overflow-y: auto;
        background:
            radial-gradient(circle at top right, rgba(31, 95, 170, 0.08), transparent 28%),
            #f4f7fb;
    }

    .operateur-modal .section-card {
        border: 1px solid #e2e9f3;
        border-radius: 18px;
        background: #fff;
        box-shadow: 0 12px 28px rgba(17, 41, 72, 0.06);
    }

    .operateur-modal .section-card + .section-card {
        margin-top: 1rem;
    }

    .operateur-modal .section-card .card-header {
        padding: 1rem 1.25rem 0;
        background: transparent;
        border: 0;
    }

    .operateur-modal .section-title {
        margin: 0;
        font-size: 15px;
        font-weight: 700;
        color: #16365c;
    }

    .operateur-modal .section-description {
        margin: 0.35rem 0 0;
        color: #748399;
        font-size: 12px;
    }

    .operateur-modal .card-body {
        padding: 1.15rem 1.25rem 1.25rem;
    }

    .operateur-modal .form-label {
        margin-bottom: 0.45rem;
        font-size: 13px;
        font-weight: 700;
        color: #4d5d73;
    }

    .operateur-modal .form-control,
    .operateur-modal .form-select {
        min-height: 48px;
        border-radius: 12px;
        border: 1px solid #d8e1ee;
        box-shadow: none;
        background-color: #fcfdff;
    }

    .operateur-modal .form-control:focus,
    .operateur-modal .form-select:focus {
        border-color: #1f5faa;
        box-shadow: 0 0 0 0.2rem rgba(31, 95, 170, 0.14);
        background-color: #fff;
    }

    .operateur-modal .modal-footer {
        position: sticky;
        bottom: 0;
        z-index: 2;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 0.75rem;
        padding: 1rem 1.5rem 1.25rem;
        border-top: 1px solid #e4ebf3;
        background: rgba(255, 255, 255, 0.96);
        box-shadow: 0 -10px 24px rgba(17, 41, 72, 0.08);
    }

    .operateur-modal .footer-note {
        margin: 0;
        font-size: 12px;
        color: #7a8798;
    }

    .operateur-modal .footer-actions {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    .operateur-modal .btn-footer {
        min-width: 150px;
        min-height: 46px;
        border-radius: 12px;
        font-weight: 600;
    }

    @media (max-width: 767.98px) {
        .operateur-modal .modal-dialog {
            margin: 0.5rem;
        }

        .operateur-modal .modal-body {
            padding: 1rem;
            max-height: calc(100vh - 180px);
        }

        .operateur-modal .modal-footer {
            flex-direction: column;
            align-items: stretch;
        }

        .operateur-modal .footer-actions {
            width: 100%;
            flex-direction: column-reverse;
        }

        .operateur-modal .btn-footer {
            width: 100%;
        }
    }
</style>

<div class="content-page">
    <div class="container-fluid mt-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
                <div>
                    <h4 class="mb-1">Gestion des Operateurs</h4>
                    <p class="text-muted mb-0">Suivi des organisations et de leurs responsables.</p>
                </div>
                <?php if ($canAddOperateur): ?>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addOperateurModal">
                        <i class="fa fa-plus me-1"></i> Ajouter Operateur
                    </button>
                <?php endif; ?>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Code</th>
                                <th>Organisation</th>
                                <th>Type</th>
                                <th>Responsable</th>
                                <th>Telephone</th>
                                <th>Email</th>
                                <th>Agrement</th>
                                <th>Periode</th>
                                <th>Statut</th>
                                <?php if ($showOperateurActionsColumn): ?>
                                    <th class="text-center" width="140">Actions</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($operateurs): ?>
                                <?php foreach ($operateurs as $operateur): ?>
                                    <tr>
                                        <td><span class="fw-semibold"><?= esc($operateur['code_operateur']) ?></span></td>
                                        <td><?= esc($operateur['nom_organisation']) ?></td>
                                        <td><span class="badge text-bg-light"><?= esc($operateur['type_operateur']) ?></span></td>
                                        <td><?= esc(trim(($operateur['prenom_responsable'] ?? '') . ' ' . ($operateur['nom_responsable'] ?? ''))) ?></td>
                                        <td><?= esc($operateur['telephone']) ?></td>
                                        <td><?= esc($operateur['email']) ?></td>
                                        <td><?= esc($operateur['numero_agrement']) ?></td>
                                        <td>
                                            <small class="text-muted">
                                                <?= esc($operateur['date_debut']) ?><br>
                                                <?= esc($operateur['date_fin'] ?: '-') ?>
                                            </small>
                                        </td>
                                        <td>
                                            <span class="badge <?= ($operateur['statut'] ?? 'actif') === 'actif' ? 'text-bg-success' : 'text-bg-secondary' ?>">
                                                <?= esc($operateur['statut'] ?? 'actif') ?>
                                            </span>
                                        </td>
                                        <?php if ($showOperateurActionsColumn): ?>
                                            <td class="text-center">
                                                <?php if ($canEditOperateur): ?>
                                                    <button type="button" class="btn btn-sm btn-outline-primary editOperateurBtn"
                                                        data-id="<?= esc($operateur['id'], 'attr') ?>"
                                                        data-code-operateur="<?= esc($operateur['code_operateur'], 'attr') ?>"
                                                        data-nom-organisation="<?= esc($operateur['nom_organisation'], 'attr') ?>"
                                                        data-type-operateur="<?= esc($operateur['type_operateur'], 'attr') ?>"
                                                        data-prenom-responsable="<?= esc($operateur['prenom_responsable'], 'attr') ?>"
                                                        data-nom-responsable="<?= esc($operateur['nom_responsable'], 'attr') ?>"
                                                        data-telephone="<?= esc($operateur['telephone'], 'attr') ?>"
                                                        data-email="<?= esc($operateur['email'], 'attr') ?>"
                                                        data-adresse="<?= esc($operateur['adresse'], 'attr') ?>"
                                                        data-numero-agrement="<?= esc($operateur['numero_agrement'], 'attr') ?>"
                                                        data-date-debut="<?= esc($operateur['date_debut'], 'attr') ?>"
                                                        data-date-fin="<?= esc($operateur['date_fin'], 'attr') ?>"
                                                        data-structure-id="<?= esc($operateur['structure_id'], 'attr') ?>"
                                                        data-statut="<?= esc($operateur['statut'], 'attr') ?>">
                                                        <i class="fa fa-edit"></i>
                                                    </button>
                                                <?php endif; ?>
                                                <?php if ($canDeleteOperateur): ?>
                                                    <button type="button" class="btn btn-sm btn-outline-danger deleteOperateurBtn"
                                                        data-id="<?= esc($operateur['id'], 'attr') ?>"
                                                        data-nom="<?= esc($operateur['nom_organisation'], 'attr') ?>">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                <?php endif; ?>
                                            </td>
                                        <?php endif; ?>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="<?= $showOperateurActionsColumn ? '10' : '9' ?>" class="text-center text-muted py-4">Aucun operateur enregistre.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if ($canAddOperateur): ?>
<div class="modal fade operateur-modal" id="addOperateurModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow">
            <form method="post" action="<?= base_url('operateur/save') ?>" class="operateur-form">
                <div class="modal-header bg-primary text-white">
                    <div class="modal-title-wrap">
                        <span class="modal-title-icon"><i class="fa fa-building"></i></span>
                        <div>
                            <h5 class="modal-title mb-1">Ajouter un operateur</h5>
                            <p class="modal-subtitle">Renseignez l'organisation, le responsable et le statut dans un seul formulaire.</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body bg-light">
                    <div class="card section-card border-0 shadow-sm mb-3">
                        <div class="card-header">
                            <h6 class="section-title">Organisation</h6>
                            <p class="section-description">Identite, contact principal et periode d'agrement.</p>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-4"><label class="form-label">Code operateur</label><input type="text" name="code_operateur" class="form-control" value="<?= esc(old('code_operateur')) ?>" required></div>
                                <div class="col-md-4"><label class="form-label">Nom organisation</label><input type="text" name="nom_organisation" class="form-control" value="<?= esc(old('nom_organisation')) ?>" required></div>
                                <div class="col-md-4"><label class="form-label">Type operateur</label><input type="text" name="type_operateur" class="form-control" value="<?= esc(old('type_operateur')) ?>" placeholder="ONG, Projet, Etat..." required></div>
                                <div class="col-md-6"><label class="form-label">Adresse</label><input type="text" name="adresse" class="form-control" value="<?= esc(old('adresse')) ?>"></div>
                                <div class="col-md-3"><label class="form-label">Telephone</label><input type="text" name="telephone" class="form-control" value="<?= esc(old('telephone')) ?>" required></div>
                                <div class="col-md-3"><label class="form-label">Email</label><input type="email" name="email" class="form-control" value="<?= esc(old('email')) ?>"></div>
                                <div class="col-md-4"><label class="form-label">Numero agrement</label><input type="text" name="numero_agrement" class="form-control" value="<?= esc(old('numero_agrement')) ?>"></div>
                                <div class="col-md-4"><label class="form-label">Date debut</label><input type="date" name="date_debut" class="form-control" value="<?= esc(old('date_debut')) ?>" required></div>
                                <div class="col-md-4"><label class="form-label">Date fin</label><input type="date" name="date_fin" class="form-control" value="<?= esc(old('date_fin')) ?>"></div>
                            </div>
                        </div>
                    </div>

                    <div class="card section-card border-0 shadow-sm">
                        <div class="card-header">
                            <h6 class="section-title">Responsable et statut</h6>
                            <p class="section-description">Associez le responsable, la structure de rattachement et l'etat du dossier.</p>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-4"><label class="form-label">Prenom responsable</label><input type="text" name="prenom_responsable" class="form-control" value="<?= esc(old('prenom_responsable')) ?>" required></div>
                                <div class="col-md-4"><label class="form-label">Nom responsable</label><input type="text" name="nom_responsable" class="form-control" value="<?= esc(old('nom_responsable')) ?>" required></div>
                                <div class="col-md-4">
                                    <label class="form-label">Structure</label>
                                    <select name="structure_id" class="form-select">
                                        <option value="">Selectionner</option>
                                        <?php foreach ($structures as $structure): ?>
                                            <option value="<?= esc($structure['id']) ?>" <?= old('structure_id') == $structure['id'] ? 'selected' : '' ?>><?= esc($structure['label']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
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
                <div class="modal-footer bg-white">
                    <p class="footer-note">Les champs obligatoires doivent etre completes avant validation.</p>
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

<?php if ($canEditOperateur): ?>
<div class="modal fade operateur-modal" id="editOperateurModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow">
            <form method="post" action="<?= base_url('operateur/update') ?>" class="operateur-form">
                <input type="hidden" name="id" id="edit_operateur_id">
                <div class="modal-header bg-primary text-white">
                    <div class="modal-title-wrap">
                        <span class="modal-title-icon"><i class="fa fa-edit"></i></span>
                        <div>
                            <h5 class="modal-title mb-1">Modifier un operateur</h5>
                            <p class="modal-subtitle">Mettez a jour les informations de l'organisation sans perdre le contexte.</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body bg-light">
                    <div class="card section-card border-0 shadow-sm mb-3">
                        <div class="card-header">
                            <h6 class="section-title">Organisation</h6>
                            <p class="section-description">Revoyez les donnees d'identification et de contact de l'operateur.</p>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-4"><label class="form-label">Code operateur</label><input type="text" name="code_operateur" id="edit_code_operateur" class="form-control" required></div>
                                <div class="col-md-4"><label class="form-label">Nom organisation</label><input type="text" name="nom_organisation" id="edit_nom_organisation" class="form-control" required></div>
                                <div class="col-md-4"><label class="form-label">Type operateur</label><input type="text" name="type_operateur" id="edit_type_operateur" class="form-control" required></div>
                                <div class="col-md-6"><label class="form-label">Adresse</label><input type="text" name="adresse" id="edit_adresse" class="form-control"></div>
                                <div class="col-md-3"><label class="form-label">Telephone</label><input type="text" name="telephone" id="edit_telephone" class="form-control" required></div>
                                <div class="col-md-3"><label class="form-label">Email</label><input type="email" name="email" id="edit_email" class="form-control"></div>
                                <div class="col-md-4"><label class="form-label">Numero agrement</label><input type="text" name="numero_agrement" id="edit_numero_agrement" class="form-control"></div>
                                <div class="col-md-4"><label class="form-label">Date debut</label><input type="date" name="date_debut" id="edit_date_debut" class="form-control" required></div>
                                <div class="col-md-4"><label class="form-label">Date fin</label><input type="date" name="date_fin" id="edit_date_fin" class="form-control"></div>
                            </div>
                        </div>
                    </div>

                    <div class="card section-card border-0 shadow-sm">
                        <div class="card-header">
                            <h6 class="section-title">Responsable et statut</h6>
                            <p class="section-description">Confirmez le rattachement et l'etat actif ou inactif du dossier.</p>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-4"><label class="form-label">Prenom responsable</label><input type="text" name="prenom_responsable" id="edit_prenom_responsable" class="form-control" required></div>
                                <div class="col-md-4"><label class="form-label">Nom responsable</label><input type="text" name="nom_responsable" id="edit_nom_responsable" class="form-control" required></div>
                                <div class="col-md-4">
                                    <label class="form-label">Structure</label>
                                    <select name="structure_id" id="edit_structure_id" class="form-select">
                                        <option value="">Selectionner</option>
                                        <?php foreach ($structures as $structure): ?>
                                            <option value="<?= esc($structure['id']) ?>"><?= esc($structure['label']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
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
                <div class="modal-footer bg-white">
                    <p class="footer-note">Le pied de page reste visible pendant le scroll pour garder l’action accessible.</p>
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
            const addModal = document.getElementById('addOperateurModal');
            if (addModal) {
                new bootstrap.Modal(addModal).show();
            }
        });
    <?php endif; ?>

    <?php if ($canEditOperateur): ?>
        document.querySelectorAll('.editOperateurBtn').forEach(function (button) {
            button.addEventListener('click', function () {
                document.getElementById('edit_operateur_id').value = this.dataset.id || '';
                document.getElementById('edit_code_operateur').value = this.dataset.codeOperateur || '';
                document.getElementById('edit_nom_organisation').value = this.dataset.nomOrganisation || '';
                document.getElementById('edit_type_operateur').value = this.dataset.typeOperateur || '';
                document.getElementById('edit_prenom_responsable').value = this.dataset.prenomResponsable || '';
                document.getElementById('edit_nom_responsable').value = this.dataset.nomResponsable || '';
                document.getElementById('edit_telephone').value = this.dataset.telephone || '';
                document.getElementById('edit_email').value = this.dataset.email || '';
                document.getElementById('edit_adresse').value = this.dataset.adresse || '';
                document.getElementById('edit_numero_agrement').value = this.dataset.numeroAgrement || '';
                document.getElementById('edit_date_debut').value = this.dataset.dateDebut || '';
                document.getElementById('edit_date_fin').value = this.dataset.dateFin || '';
                document.getElementById('edit_structure_id').value = this.dataset.structureId || '';
                document.getElementById('edit_statut').value = this.dataset.statut || 'actif';

                new bootstrap.Modal(document.getElementById('editOperateurModal')).show();
            });
        });
    <?php endif; ?>

    <?php if ($canDeleteOperateur): ?>
        document.querySelectorAll('.deleteOperateurBtn').forEach(function (button) {
            button.addEventListener('click', function () {
                const id = this.dataset.id;
                const nom = this.dataset.nom || 'cet operateur';

                Swal.fire({
                    title: 'Supprimer operateur ?',
                    html: "Cette action est irreversible.<br><strong>" + nom + "</strong>",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Oui, supprimer',
                    cancelButtonText: 'Annuler',
                    confirmButtonColor: '#dc3545'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "<?= base_url('operateur/delete/') ?>" + id;
                    }
                });
            });
        });
    <?php endif; ?>
});
</script>

<?= $this->include('templates/footer') ?>
