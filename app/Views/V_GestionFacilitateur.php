<?= $this->include('templates/header') ?>
<?= $this->include('templates/top_bar') ?>
<?= $this->include('templates/left_sidebar') ?>
<?php
$user_permissions = $user_permissions ?? [];
$facilitateurs = $facilitateurs ?? [];
$structures = $structures ?? [];
$classes = $classes ?? [];

$hasFacilitateurPermission = static function (array $permissions, int $menuId, int $sousMenuId, int $permissionId): bool {
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

$canAddFacilitateur = $hasFacilitateurPermission($user_permissions, 2, 2, 2);
$canEditFacilitateur = $hasFacilitateurPermission($user_permissions, 2, 2, 3);
$canDeleteFacilitateur = $hasFacilitateurPermission($user_permissions, 2, 2, 4);
$showFacilitateurActionsColumn = $canEditFacilitateur || $canDeleteFacilitateur;
?>
<br><br><br>
<style>
    .facilitateur-modal .modal-content {
        border: 0;
        overflow: hidden;
        background: #f6f8fc;
    }

    .facilitateur-modal .modal-header {
        padding: 1.25rem 1.5rem;
        background: linear-gradient(135deg, #14335f 0%, #1f5faa 100%);
    }

    .facilitateur-modal .modal-title-wrap {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .facilitateur-modal .modal-title-icon {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, 0.16);
        font-size: 20px;
    }

    .facilitateur-modal .modal-subtitle {
        margin: 4px 0 0;
        font-size: 13px;
        color: rgba(255, 255, 255, 0.8);
    }

    .facilitateur-modal .modal-body {
        max-height: 70vh;
        overflow-y: auto;
        padding: 1.5rem;
    }

    .facilitateur-modal .section-card {
        background: #fff;
        border: 1px solid #e8edf5;
        border-radius: 18px;
        padding: 1.25rem;
        box-shadow: 0 12px 28px rgba(17, 41, 72, 0.06);
    }

    .facilitateur-modal .section-title {
        margin: 0 0 1rem;
        font-size: 15px;
        font-weight: 700;
        color: #16365c;
    }

    .facilitateur-modal .form-label {
        font-size: 13px;
        font-weight: 600;
        color: #4f5f75;
        margin-bottom: 6px;
    }

    .facilitateur-modal .form-control,
    .facilitateur-modal .form-select {
        min-height: 46px;
        border-radius: 12px;
        border: 1px solid #d9e2ef;
        box-shadow: none;
    }

    .facilitateur-modal .form-control:focus,
    .facilitateur-modal .form-select:focus {
        border-color: #1f5faa;
        box-shadow: 0 0 0 0.2rem rgba(31, 95, 170, 0.14);
    }

    .facilitateur-modal .modal-footer {
        position: sticky;
        bottom: 0;
        z-index: 2;
        padding: 1rem 1.5rem;
        background: #fff;
        border-top: 1px solid #e6ebf2;
        box-shadow: 0 -8px 20px rgba(17, 41, 72, 0.05);
    }

    .facilitateur-modal .btn-footer {
        min-width: 150px;
        min-height: 46px;
        border-radius: 12px;
        font-weight: 600;
    }

    .facilitateur-modal .helper-text {
        font-size: 12px;
        color: #7b8798;
        margin-top: 4px;
    }
</style>
<BR></BR>
<div class="content-page">
    <div class="container-fluid mt-4">
        <div class="card shadow-sm border-0">
            <div class="card-header text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0">
                    <i class="fa fa-users me-2"></i> Gestion des Facilitateurs
                </h4>
                <BR></BR>
                <?php if ($canAddFacilitateur): ?>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addFacilitateurModal">
                        <i class="fa fa-plus"></i> Ajouter Facilitateur
                    </button>
                <?php endif; ?>
            </div>
<BR></BR>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Matricule</th>
                                <th>Prenom</th>
                                <th>Nom</th>
                                <th>Sexe</th>
                                <th>Telephone</th>
                                <th>Email</th>
                                <th>Niveau</th>
                                <th>Specialite</th>
                                <th>Type contrat</th>
                                <th>Statut</th>
                                <?php if ($showFacilitateurActionsColumn): ?>
                                    <th class="text-center" width="150">Actions</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($facilitateurs): ?>
                                <?php foreach ($facilitateurs as $facilitateur): ?>
                                    <tr>
                                        <td><?= esc($facilitateur['matricule']) ?></td>
                                        <td><?= esc($facilitateur['prenom']) ?></td>
                                        <td><?= esc($facilitateur['nom']) ?></td>
                                        <td><?= esc($facilitateur['sexe']) ?></td>
                                        <td><?= esc($facilitateur['telephone']) ?></td>
                                        <td><?= esc($facilitateur['email']) ?></td>
                                        <td><?= esc($facilitateur['niveau_etude']) ?></td>
                                        <td><?= esc($facilitateur['specialite']) ?></td>
                                        <td><?= esc($facilitateur['type_contrat']) ?></td>
                                        <td>
                                            <span class="badge <?= ($facilitateur['statut'] ?? 'actif') === 'actif' ? 'bg-success' : 'bg-secondary' ?>">
                                                <?= esc($facilitateur['statut'] ?? 'actif') ?>
                                            </span>
                                        </td>
                                        <?php if ($showFacilitateurActionsColumn): ?>
                                            <td class="text-center">
                                                <?php if ($canEditFacilitateur): ?>
                                                    <button
                                                        type="button"
                                                        class="btn btn-sm btn-outline-primary editFacilitateurBtn"
                                                        data-id="<?= esc($facilitateur['id'], 'attr') ?>"
                                                        data-matricule="<?= esc($facilitateur['matricule'], 'attr') ?>"
                                                        data-prenom="<?= esc($facilitateur['prenom'], 'attr') ?>"
                                                        data-nom="<?= esc($facilitateur['nom'], 'attr') ?>"
                                                        data-sexe="<?= esc($facilitateur['sexe'], 'attr') ?>"
                                                        data-date-naissance="<?= esc($facilitateur['date_naissance'], 'attr') ?>"
                                                        data-telephone="<?= esc($facilitateur['telephone'], 'attr') ?>"
                                                        data-email="<?= esc($facilitateur['email'], 'attr') ?>"
                                                        data-adresse="<?= esc($facilitateur['adresse'], 'attr') ?>"
                                                        data-niveau-etude="<?= esc($facilitateur['niveau_etude'], 'attr') ?>"
                                                        data-specialite="<?= esc($facilitateur['specialite'], 'attr') ?>"
                                                        data-date-recrutement="<?= esc($facilitateur['date_recrutement'], 'attr') ?>"
                                                        data-type-contrat="<?= esc($facilitateur['type_contrat'], 'attr') ?>"
                                                        data-structure-id="<?= esc($facilitateur['structure_id'], 'attr') ?>"
                                                        data-classe-id="<?= esc($facilitateur['classe_id'], 'attr') ?>"
                                                        data-statut="<?= esc($facilitateur['statut'], 'attr') ?>"
                                                        title="Modifier">
                                                        <i class="fa fa-edit"></i>
                                                    </button>
                                                <?php endif; ?>
                                                <?php if ($canDeleteFacilitateur): ?>
                                                    <button
                                                        type="button"
                                                        class="btn btn-sm btn-outline-danger deleteFacilitateurBtn"
                                                        data-id="<?= esc($facilitateur['id'], 'attr') ?>"
                                                        data-nom-complet="<?= esc(trim(($facilitateur['prenom'] ?? '') . ' ' . ($facilitateur['nom'] ?? '')), 'attr') ?>"
                                                        title="Supprimer">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                <?php endif; ?>
                                            </td>
                                        <?php endif; ?>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="<?= $showFacilitateurActionsColumn ? '11' : '10' ?>" class="text-center text-muted">Aucun facilitateur enregistre.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if ($canAddFacilitateur): ?>
<div class="modal fade facilitateur-modal" id="addFacilitateurModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content shadow-lg rounded-4">
            <form method="post" action="<?= base_url('facilitateur/save') ?>">
                <div class="modal-header text-white border-0 rounded-top">
                    <div class="modal-title-wrap">
                        <div class="modal-title-icon">
                            <i class="fa fa-user-plus"></i>
                        </div>
                        <div>
                            <h5 class="modal-title mb-0">Ajouter un facilitateur</h5>
                            <p class="modal-subtitle">Renseignez les informations administratives et professionnelles.</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body p-4">
                    <div class="row g-4">
                        <div class="col-12">
                            <div class="section-card">
                                <h6 class="section-title">Informations personnelles</h6>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label">Matricule</label>
                                        <input type="text" name="matricule" class="form-control" value="<?= esc(old('matricule')) ?>" placeholder="Ex: FAC-001" required>
                                        <div class="helper-text">Ce champ doit etre unique.</div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Prenom</label>
                                        <input type="text" name="prenom" class="form-control" value="<?= esc(old('prenom')) ?>" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Nom</label>
                                        <input type="text" name="nom" class="form-control" value="<?= esc(old('nom')) ?>" required>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">Sexe</label>
                                        <select name="sexe" class="form-select" required>
                                            <option value="">Selectionner</option>
                                            <option value="Masculin" <?= old('sexe') === 'Masculin' ? 'selected' : '' ?>>Masculin</option>
                                            <option value="Feminin" <?= old('sexe') === 'Feminin' ? 'selected' : '' ?>>Feminin</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Date de naissance</label>
                                        <input type="date" name="date_naissance" class="form-control" value="<?= esc(old('date_naissance')) ?>" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Telephone</label>
                                        <input type="text" name="telephone" class="form-control" value="<?= esc(old('telephone')) ?>" required>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">Email</label>
                                        <input type="email" name="email" class="form-control" value="<?= esc(old('email')) ?>" placeholder="exemple@email.com">
                                    </div>
                                    <div class="col-md-8">
                                        <label class="form-label">Adresse</label>
                                        <input type="text" name="adresse" class="form-control" value="<?= esc(old('adresse')) ?>" placeholder="Quartier, commune, ville">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="section-card">
                                <h6 class="section-title">Informations professionnelles</h6>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label">Niveau d'etude</label>
                                        <input type="text" name="niveau_etude" class="form-control" value="<?= esc(old('niveau_etude')) ?>" placeholder="Ex: Licence">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Specialite</label>
                                        <input type="text" name="specialite" class="form-control" value="<?= esc(old('specialite')) ?>" placeholder="Ex: Mathematiques">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Date de recrutement</label>
                                        <input type="date" name="date_recrutement" class="form-control" value="<?= esc(old('date_recrutement')) ?>" required>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">Type de contrat</label>
                                        <input type="text" name="type_contrat" class="form-control" value="<?= esc(old('type_contrat')) ?>" placeholder="Ex: CDI, CDD, Vacation" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Structure</label>
                                        <select name="structure_id" class="form-select">
                                            <option value="">Selectionner</option>
                                            <?php foreach ($structures as $structure): ?>
                                                <option value="<?= esc($structure['id']) ?>" <?= old('structure_id') == $structure['id'] ? 'selected' : '' ?>>
                                                    <?= esc($structure['label']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Classe</label>
                                        <select name="classe_id" class="form-select">
                                            <option value="">Selectionner</option>
                                            <?php foreach ($classes as $classe): ?>
                                                <option value="<?= esc($classe['id']) ?>" <?= old('classe_id') == $classe['id'] ? 'selected' : '' ?>>
                                                    <?= esc($classe['label']) ?>
                                                </option>
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
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-footer" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success btn-footer">
                        <i class="fa fa-check me-1"></i> Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<?php if ($canEditFacilitateur): ?>
<div class="modal fade facilitateur-modal" id="editFacilitateurModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content shadow-lg rounded-4">
            <form method="post" action="<?= base_url('facilitateur/update') ?>">
                <input type="hidden" name="id" id="edit_id">
                <div class="modal-header text-white border-0 rounded-top">
                    <div class="modal-title-wrap">
                        <div class="modal-title-icon">
                            <i class="fa fa-edit"></i>
                        </div>
                        <div>
                            <h5 class="modal-title mb-0">Modifier un facilitateur</h5>
                            <p class="modal-subtitle">Mettez a jour les informations du facilitateur selectionne.</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body p-4">
                    <div class="row g-4">
                        <div class="col-12">
                            <div class="section-card">
                                <h6 class="section-title">Informations personnelles</h6>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label">Matricule</label>
                                        <input type="text" name="matricule" id="edit_matricule" class="form-control" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Prenom</label>
                                        <input type="text" name="prenom" id="edit_prenom" class="form-control" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Nom</label>
                                        <input type="text" name="nom" id="edit_nom" class="form-control" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Sexe</label>
                                        <select name="sexe" id="edit_sexe" class="form-select" required>
                                            <option value="">Selectionner</option>
                                            <option value="Masculin">Masculin</option>
                                            <option value="Feminin">Feminin</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Date de naissance</label>
                                        <input type="date" name="date_naissance" id="edit_date_naissance" class="form-control" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Telephone</label>
                                        <input type="text" name="telephone" id="edit_telephone" class="form-control" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Email</label>
                                        <input type="email" name="email" id="edit_email" class="form-control">
                                    </div>
                                    <div class="col-md-8">
                                        <label class="form-label">Adresse</label>
                                        <input type="text" name="adresse" id="edit_adresse" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="section-card">
                                <h6 class="section-title">Informations professionnelles</h6>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label">Niveau d'etude</label>
                                        <input type="text" name="niveau_etude" id="edit_niveau_etude" class="form-control">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Specialite</label>
                                        <input type="text" name="specialite" id="edit_specialite" class="form-control">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Date de recrutement</label>
                                        <input type="date" name="date_recrutement" id="edit_date_recrutement" class="form-control" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Type de contrat</label>
                                        <input type="text" name="type_contrat" id="edit_type_contrat" class="form-control" required>
                                    </div>
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
                                        <label class="form-label">Classe</label>
                                        <select name="classe_id" id="edit_classe_id" class="form-select">
                                            <option value="">Selectionner</option>
                                            <?php foreach ($classes as $classe): ?>
                                                <option value="<?= esc($classe['id']) ?>"><?= esc($classe['label']) ?></option>
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
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-footer" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success btn-footer">
                        <i class="fa fa-save me-1"></i> Modifier
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        <?php if (session()->getFlashdata('success')): ?>
            Swal.fire({
                icon: 'success',
                title: 'Succes',
                text: "<?= esc(session()->getFlashdata('success'), 'js') ?>",
                confirmButtonColor: '#3085d6'
            });
        <?php endif; ?>

        <?php if (session()->getFlashdata('success_delete')): ?>
            Swal.fire({
                icon: 'success',
                title: 'Succes',
                text: "<?= esc(session()->getFlashdata('success_delete'), 'js') ?>",
                confirmButtonColor: '#3085d6'
            });
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            Swal.fire({
                icon: 'error',
                title: 'Erreur',
                text: "<?= esc(session()->getFlashdata('error'), 'js') ?>",
                confirmButtonColor: '#d33'
            }).then(() => {
                const modalEl = document.getElementById('addFacilitateurModal');
                if (modalEl) {
                    const modal = new bootstrap.Modal(modalEl);
                    modal.show();
                }
            });
        <?php endif; ?>

        <?php if ($canEditFacilitateur): ?>
            document.querySelectorAll('.editFacilitateurBtn').forEach(function (button) {
                button.addEventListener('click', function () {
                    document.getElementById('edit_id').value = this.dataset.id || '';
                    document.getElementById('edit_matricule').value = this.dataset.matricule || '';
                    document.getElementById('edit_prenom').value = this.dataset.prenom || '';
                    document.getElementById('edit_nom').value = this.dataset.nom || '';
                    document.getElementById('edit_sexe').value = this.dataset.sexe || '';
                    document.getElementById('edit_date_naissance').value = this.dataset.dateNaissance || '';
                    document.getElementById('edit_telephone').value = this.dataset.telephone || '';
                    document.getElementById('edit_email').value = this.dataset.email || '';
                    document.getElementById('edit_adresse').value = this.dataset.adresse || '';
                    document.getElementById('edit_niveau_etude').value = this.dataset.niveauEtude || '';
                    document.getElementById('edit_specialite').value = this.dataset.specialite || '';
                    document.getElementById('edit_date_recrutement').value = this.dataset.dateRecrutement || '';
                    document.getElementById('edit_type_contrat').value = this.dataset.typeContrat || '';
                    document.getElementById('edit_structure_id').value = this.dataset.structureId || '';
                    document.getElementById('edit_classe_id').value = this.dataset.classeId || '';
                    document.getElementById('edit_statut').value = this.dataset.statut || 'actif';

                    const modal = new bootstrap.Modal(document.getElementById('editFacilitateurModal'));
                    modal.show();
                });
            });
        <?php endif; ?>

        <?php if ($canDeleteFacilitateur): ?>
            document.querySelectorAll('.deleteFacilitateurBtn').forEach(function (button) {
                button.addEventListener('click', function () {
                    const id = this.dataset.id;
                    const nom = this.dataset.nomComplet || 'ce facilitateur';

                    Swal.fire({
                        title: 'Supprimer facilitateur ?',
                        html: "Cette action est irreversible.<br><strong>" + nom + "</strong>",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Oui, supprimer',
                        cancelButtonText: 'Annuler'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = "<?= base_url('facilitateur/delete/') ?>" + id;
                        }
                    });
                });
            });
        <?php endif; ?>
    });
</script>

<?= $this->include('templates/footer') ?>
