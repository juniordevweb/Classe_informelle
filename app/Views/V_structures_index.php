<?= $this->extend('templates/index') ?>
<?= $this->section('content') ?>
<div class="pt-3"></div>
<style>
        .structure-action-btn {
            width: 36px;
            height: 36px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .structure-modal .modal-dialog {
            margin: 1rem auto;
        }

        .structure-modal .modal-content {
            border: 0;
            overflow: hidden;
            border-radius: 22px;
            background: #f4f7fb;
            box-shadow: 0 24px 60px rgba(19, 42, 76, 0.18);
        }

        .structure-modal .structure-form,
        .structure-modal .structure-modal-body {
            display: flex;
            flex-direction: column;
            min-height: 0;
        }

        .structure-modal .modal-header {
            padding: 1.35rem 1.5rem;
            border-bottom: 0;
        }

        .structure-modal .modal-header.modal-header-primary {
            background: linear-gradient(135deg, #113564 0%, #1f5faa 55%, #2d7be0 100%);
        }

        .structure-modal .modal-header.modal-header-info {
            background: linear-gradient(135deg, #0f4c5c 0%, #177e89 50%, #27a8b8 100%);
        }

        .structure-modal .modal-header.modal-header-edit {
            background: linear-gradient(135deg, #15355f 0%, #1d6697 52%, #29a0c9 100%);
        }

        .structure-modal .modal-header.modal-header-danger {
            background: linear-gradient(135deg, #7f1d1d 0%, #b91c1c 50%, #ef4444 100%);
        }

        .structure-modal .modal-title-wrap {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .structure-modal .modal-title-icon {
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

        .structure-modal .modal-subtitle {
            margin: 4px 0 0;
            color: rgba(255, 255, 255, 0.8);
            font-size: 13px;
        }

        .structure-modal .modal-body {
            padding: 1.5rem;
            max-height: calc(100vh - 220px);
            overflow-y: auto;
            background:
                radial-gradient(circle at top right, rgba(31, 95, 170, 0.08), transparent 28%),
                #f4f7fb;
        }

        .structure-modal .section-card {
            border: 1px solid #e2e9f3;
            border-radius: 18px;
            background: #fff;
            box-shadow: 0 12px 28px rgba(17, 41, 72, 0.06);
        }

        .structure-modal .section-card + .section-card {
            margin-top: 1rem;
        }

        .structure-modal .section-card .card-header {
            padding: 1rem 1.25rem 0;
            background: transparent;
            border: 0;
        }

        .structure-modal .section-title {
            margin: 0;
            font-size: 15px;
            font-weight: 700;
            color: #16365c;
        }

        .structure-modal .section-description {
            margin: 0.35rem 0 0;
            color: #748399;
            font-size: 12px;
        }

        .structure-modal .card-body {
            padding: 1.15rem 1.25rem 1.25rem;
        }

        .structure-modal .form-label {
            margin-bottom: 0.45rem;
            font-size: 13px;
            font-weight: 700;
            color: #4d5d73;
        }

        .structure-modal .form-control,
        .structure-modal .form-select {
            min-height: 48px;
            border-radius: 12px;
            border: 1px solid #d8e1ee;
            box-shadow: none;
            background-color: #fcfdff;
        }

        .structure-modal .form-control:focus,
        .structure-modal .form-select:focus {
            border-color: #1f5faa;
            box-shadow: 0 0 0 0.2rem rgba(31, 95, 170, 0.14);
            background-color: #fff;
        }

        .structure-modal .detail-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
            gap: 0.9rem;
        }

        .structure-modal .detail-item {
            padding: 0.95rem 1rem;
            border-radius: 14px;
            background: #f7faff;
            border: 1px solid #e7eef8;
        }

        .structure-modal .detail-label {
            display: block;
            margin-bottom: 0.35rem;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: #6c7a8b;
        }

        .structure-modal .detail-value {
            display: block;
            color: #19324d;
            font-weight: 600;
            word-break: break-word;
        }

        .structure-modal .modal-footer {
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

        .structure-modal .footer-note {
            margin: 0;
            font-size: 12px;
            color: #7a8798;
        }

        .structure-modal .footer-actions {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .structure-modal .btn-footer {
            min-width: 150px;
            min-height: 46px;
            border-radius: 12px;
            font-weight: 600;
        }

        @media (max-width: 767.98px) {
            .structure-modal .modal-dialog {
                margin: 0.5rem;
            }

            .structure-modal .modal-body {
                padding: 1rem;
                max-height: calc(100vh - 180px);
            }

            .structure-modal .modal-footer {
                flex-direction: column;
                align-items: stretch;
            }

            .structure-modal .footer-actions {
                width: 100%;
                flex-direction: column-reverse;
            }

            .structure-modal .btn-footer {
                width: 100%;
            }
        }
    </style>
    <div class="content-page">
        <div class="content">
            <div class="container-fluid mt-4">
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= esc(session()->getFlashdata('success')) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= esc(session()->getFlashdata('error')) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1><i class="fa fa-building"></i> Gestion des Structures</h1>
                    <button type="button" id="openAddStructureModal" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStructureModal">
                        <i class="fa fa-plus"></i> Nouvelle Structure
                    </button>
                </div>

                <div class="card mb-4">
                    <div class="card-body">
                        <form method="get" action="<?= base_url('structures') ?>" class="row g-3">
                            <div class="col-md-3">
                                <input type="text" name="search" class="form-control" placeholder="Rechercher par nom" value="<?= esc(request()->getGet('search')) ?>">
                            </div>
                          
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-secondary me-2"><i class="fa fa-search"></i> Filtrer</button>
                                <a href="<?= base_url('structures') ?>" class="btn btn-outline-secondary"><i class="fa fa-times"></i> Reinitialiser</a>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-primary">
                                    <tr>
                                        <th>Code</th>
                                        <th>Nom</th>
                                        <th>Region</th>
                                        <th>Operateur</th>
                                        <th>Etat</th>
                                        <th>Classes</th>
                                        <th>Date creation</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($structures as $structure):
                                        $badgeClass = match($structure['etat']) {
                                            'EN_ATTENTE' => 'warning',
                                            'VALIDE' => 'info',
                                            'OUVERT' => 'success',
                                            'FERME' => 'danger',
                                            'GELE' => 'secondary',
                                            default => 'light'
                                        };
                                    ?>
                                        <tr>
                                            <td><?= esc($structure['code_structure']) ?></td>
                                            <td><?= esc($structure['nom_structure']) ?></td>
                                            <td><?= esc($structure['region']) ?></td>
                                            <td><?= esc($structure['nom_operateur']) ?></td>
                                            <td><span class="badge bg-<?= $badgeClass ?>"><?= esc($structure['etat']) ?></span></td>
                                            <td><?= esc($structure['nombre_classes']) ?></td>
                                            <td><?= date('d/m/Y', strtotime($structure['created_at'])) ?></td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-outline-success me-1 structure-action-btn" data-bs-toggle="modal" data-bs-target="#viewStructureModal" onclick="loadStructureView(<?= $structure['id'] ?>)" title="Voir detail">
                                                    <i class="fa fa-eye"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-primary me-1 structure-action-btn" data-bs-toggle="modal" data-bs-target="#editStructureModal" onclick="loadStructureEdit(<?= $structure['id'] ?>)" title="Modifier">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger structure-action-btn" data-bs-toggle="modal" data-bs-target="#deleteConfirmModal" onclick="setDeleteId(<?= $structure['id'] ?>)" title="Supprimer">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center mt-4">
                            <?= isset($pager) ? $pager->simpleLinks('structures', 'prev_next') : '' ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade structure-modal" id="addStructureModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <form method="post" action="<?= base_url('structures/store') ?>" id="addStructureForm" class="structure-form">
                    <?= csrf_field() ?>
                    <div class="modal-header modal-header-primary text-white">
                        <div class="modal-title-wrap">
                            <span class="modal-title-icon"><i class="fa fa-plus-circle"></i></span>
                            <div>
                                <h5 class="modal-title mb-1 text-white">Ajouter une structure</h5>
                                <p class="modal-subtitle">Renseignez l'identite, la localisation et l'etat administratif de la structure.</p>
                            </div>
                        </div>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="section-card">
                            <div class="card-header">
                                <h6 class="section-title">Informations generales</h6>
                                <p class="section-description">Definissez le nom principal, la langue nationale et l'operateur responsable.</p>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="addNomStructure" class="form-label">Nom de la structure *</label>
                                        <input type="text" class="form-control" id="addNomStructure" name="nom_structure" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="addLangueNationale" class="form-label">Langue nationale *</label>
                                        <select class="form-select" id="addLangueNationale" name="langue_nationale" required>
                                            <option value="">Selectionner une langue nationale</option>
                                            <?php foreach ($languesNationales as $langue): ?>
                                                <option value="<?= esc($langue['nom'], 'attr') ?>"><?= esc($langue['nom']) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <label for="addOperateurId" class="form-label">Operateur *</label>
                                        <select class="form-select" id="addOperateurId" name="operateur_id" required>
                                            <option value="">Selectionner un operateur</option>
                                            <?php foreach ($operateurs as $operateur): ?>
                                                <option value="<?= $operateur['id'] ?>"><?= esc($operateur['nom_organisation']) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="section-card">
                            <div class="card-header">
                                <h6 class="section-title">Localisation</h6>
                                <p class="section-description">Renseignez l'ancrage administratif complet de la structure.</p>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="addRegion" class="form-label">Region *</label>
                                        <select class="form-select" id="addRegion" name="region" required>
                                            <option value="">Selectionner une region</option>
                                            <?php foreach ($atlasRegions as $atlasRegion): ?>
                                                <option value="<?= esc($atlasRegion['libelle_structure'], 'attr') ?>" data-atlas-id="<?= (int) $atlasRegion['id_atlas'] ?>">
                                                    <?= esc($atlasRegion['libelle_structure']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="addDepartement" class="form-label">Departement *</label>
                                        <input type="text" class="form-control" id="addDepartement" name="departement" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="addCommune" class="form-label">Commune *</label>
                                        <input type="text" class="form-control" id="addCommune" name="commune" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="addQuartier" class="form-label">Quartier *</label>
                                        <input type="text" class="form-control" id="addQuartier" name="quartier" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="addIa" class="form-label">IA *</label>
                                        <select class="form-select" id="addIa" name="ia" required>
                                            <option value="">Selectionner d'abord une region</option>
                                            <?php foreach ($ias as $ia): ?>
                                                <option value="<?= esc($ia['libelle_structure'], 'attr') ?>" data-region-id="<?= (int) ($ia['id_parent_atlas'] ?? 0) ?>" hidden>
                                                    <?= esc($ia['libelle_structure']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="addIef" class="form-label">IEF *</label>
                                        <input type="text" class="form-control" id="addIef" name="ief" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="section-card">
                            <div class="card-header">
                                <h6 class="section-title">Coordonnees et statut</h6>
                                <p class="section-description">Ajoutez la geolocalisation si disponible et choisissez l'etat courant de la structure.</p>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label for="addLatitude" class="form-label">Latitude</label>
                                        <input type="text" class="form-control" id="addLatitude" name="latitude" placeholder="14.6937">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="addLongitude" class="form-label">Longitude</label>
                                        <input type="text" class="form-control" id="addLongitude" name="longitude" placeholder="-17.4441">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="addEtat" class="form-label">Etat *</label>
                                        <select class="form-select" id="addEtat" name="etat" required>
                                            <?php foreach ($etats as $etat): ?>
                                                <option value="<?= esc($etat['code'], 'attr') ?>"><?= esc($etat['libelle']) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <p class="footer-note">Les champs marques d'un * sont obligatoires.</p>
                        <div class="footer-actions">
                            <button type="button" class="btn btn-outline-secondary btn-footer" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" class="btn btn-primary btn-footer">Enregistrer la structure</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('addRegion')?.addEventListener('change', function () {
            const regionId = this.options[this.selectedIndex]?.dataset.atlasId || '';
            const ia = document.getElementById('addIa');
            const options = Array.from(ia.options);
            let firstMatchingOption = null;

            options.forEach((option, index) => {
                if (index === 0) return;
                const matches = regionId !== '' && option.dataset.regionId === regionId;
                option.hidden = !matches;
                option.disabled = !matches;
                if (matches && !firstMatchingOption) firstMatchingOption = option;
            });

            ia.value = firstMatchingOption?.value || '';
        });
    </script>

    <div class="modal fade structure-modal" id="viewStructureModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header modal-header-info text-white">
                    <div class="modal-title-wrap">
                            <span class="modal-title-icon"><i class="fa fa-eye"></i></span>
                        <div>
                            <h5 class="modal-title mb-1" id="viewModalTitle">Details de la structure</h5>
                            <p class="modal-subtitle">Consultez la fiche complete sans quitter la liste.</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body structure-modal-body" id="viewModalContent">
                    <div class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Chargement...</span>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <p class="footer-note">La fiche affiche les informations operateur, statut, localisation et dates systeme.</p>
                    <div class="footer-actions">
                        <button type="button" class="btn btn-outline-secondary btn-footer" data-bs-dismiss="modal">Fermer</button>
                        <button type="button" class="btn btn-primary btn-footer" id="editFromViewBtn" onclick="editFromView()">Modifier</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade structure-modal" id="editStructureModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <form method="post" id="editStructureForm" class="structure-form">
                    <?= csrf_field() ?>
                    <div class="modal-header modal-header-edit text-white">
                        <div class="modal-title-wrap">
                            <span class="modal-title-icon"><i class="fa fa-edit"></i></span>
                            <div>
                                <h5 class="modal-title mb-1">Modifier la structure</h5>
                                <p class="modal-subtitle">Ajustez les informations sans quitter l'ecran de gestion.</p>
                            </div>
                        </div>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body" id="editModalContent">
                        <div class="text-center">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Chargement...</span>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <p class="footer-note">Les modifications sont appliquees a la structure selectionnee.</p>
                        <div class="footer-actions">
                            <button type="button" class="btn btn-outline-secondary btn-footer" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" class="btn btn-primary btn-footer">Mettre a jour</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade structure-modal" id="deleteConfirmModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header modal-header-danger text-white">
                    <div class="modal-title-wrap">
                            <span class="modal-title-icon"><i class="fa fa-exclamation-triangle"></i></span>
                        <div>
                            <h5 class="modal-title mb-1 text-white">Confirmer la suppression</h5>
                            <p class="modal-subtitle"></p>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <p class="mb-0">
                        <i class="fa fa-info-circle text-warning me-2"></i>
                        Etes-vous sur de vouloir supprimer cette structure ?  
                    </p>
                </div>

                <div class="modal-footer">
                    <p class="footer-note"></p>
                    <div class="footer-actions">
                        <button type="button" class="btn btn-outline-secondary btn-footer" data-bs-dismiss="modal">Annuler</button>
                        <button type="button" class="btn btn-danger btn-footer" id="confirmDeleteBtn" onclick="confirmDelete()">Supprimer</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentStructureId = null;
        let deleteStructureId = null;

        function escapeHtml(value) {
            if (value === null || value === undefined) {
                return '';
            }

            return String(value)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        const addStructureModalTrigger = document.getElementById('openAddStructureModal');
        if (addStructureModalTrigger) {
            addStructureModalTrigger.addEventListener('click', function() {
                document.getElementById('addStructureForm').reset();
            });
        }

        function loadStructureView(id) {
            currentStructureId = id;
            const modalContent = document.getElementById('viewModalContent');
            modalContent.innerHTML = '<div class="text-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Chargement...</span></div></div>';

            fetch(`<?= base_url('structures/api/get/') ?>${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        renderViewContent(data.data);
                    } else {
                        modalContent.innerHTML = '<div class="alert alert-danger">Erreur lors du chargement des donnees.</div>';
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    modalContent.innerHTML = '<div class="alert alert-danger">Erreur de connexion au serveur.</div>';
                });
        }

        function renderViewContent(structure) {
            const badgeClass = {
                'EN_ATTENTE': 'warning',
                'VALIDE': 'info',
                'OUVERT': 'success',
                'FERME': 'danger',
                'GELE': 'secondary'
            }[structure.etat] || 'light';

            const gpsHtml = structure.latitude && structure.longitude ? `
                <div class="section-card">
                    <div class="card-header">
                        <h6 class="section-title">Coordonnees GPS</h6>
                        <p class="section-description">Ces informations facilitent le reperage et le suivi terrain.</p>
                    </div>
                    <div class="card-body">
                        <div class="detail-grid">
                            <div class="detail-item">
                                <span class="detail-label">Latitude</span>
                                <span class="detail-value">${escapeHtml(structure.latitude)}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Longitude</span>
                                <span class="detail-value">${escapeHtml(structure.longitude)}</span>
                            </div>
                        </div>
                    </div>
                </div>
            ` : '';

            const html = `
                <div class="section-card">
                    <div class="card-header">
                        <h6 class="section-title">Informations generales</h6>
                        <p class="section-description">Lecture rapide de l'identite de la structure et de son rattachement.</p>
                    </div>
                    <div class="card-body">
                        <div class="detail-grid">
                            <div class="detail-item">
                                <span class="detail-label">Code</span>
                                <span class="detail-value"><code>${escapeHtml(structure.code_structure)}</code></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Nom</span>
                                <span class="detail-value">${escapeHtml(structure.nom_structure)}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Langue nationale</span>
                                <span class="detail-value">${escapeHtml(structure.langue_nationale)}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Operateur</span>
                                <span class="detail-value">${escapeHtml(structure.operateur_nom)}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Etat</span>
                                <span class="detail-value"><span class="badge bg-${badgeClass}">${escapeHtml(structure.etat)}</span></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Classes</span>
                                <span class="detail-value"><span class="badge bg-secondary">${escapeHtml(structure.nombre_classes || 0)}</span></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="section-card">
                    <div class="card-header">
                        <h6 class="section-title">Localisation</h6>
                        <p class="section-description">Territoire administratif et point d'implantation de la structure.</p>
                    </div>
                    <div class="card-body">
                        <div class="detail-grid">
                            <div class="detail-item">
                                <span class="detail-label">Region</span>
                                <span class="detail-value">${escapeHtml(structure.region)}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Departement</span>
                                <span class="detail-value">${escapeHtml(structure.departement)}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Commune</span>
                                <span class="detail-value">${escapeHtml(structure.commune)}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Quartier</span>
                                <span class="detail-value">${escapeHtml(structure.quartier)}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">IA</span>
                                <span class="detail-value">${escapeHtml(structure.ia)}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">IEF</span>
                                <span class="detail-value">${escapeHtml(structure.ief)}</span>
                            </div>
                        </div>
                    </div>
                </div>

                ${gpsHtml}

                <div class="section-card">
                    <div class="card-header">
                        <h6 class="section-title">Dates systeme</h6>
                        <p class="section-description">Suivi de creation et de derniere mise a jour de l'enregistrement.</p>
                    </div>
                    <div class="card-body">
                        <div class="detail-grid">
                            <div class="detail-item">
                                <span class="detail-label">Cree le</span>
                                <span class="detail-value">${escapeHtml(new Date(structure.created_at).toLocaleString('fr-FR'))}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Modifie le</span>
                                <span class="detail-value">${escapeHtml(new Date(structure.updated_at).toLocaleString('fr-FR'))}</span>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            document.getElementById('viewModalContent').innerHTML = html;
            document.getElementById('viewModalTitle').textContent = structure.nom_structure;
        }

        function loadStructureEdit(id) {
            currentStructureId = id;
            const modalContent = document.getElementById('editModalContent');
            modalContent.innerHTML = '<div class="text-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Chargement...</span></div></div>';

            fetch(`<?= base_url('structures/api/get/') ?>${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        renderEditContent(data.data);
                    } else {
                        modalContent.innerHTML = '<div class="alert alert-danger">Erreur lors du chargement des donnees.</div>';
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    modalContent.innerHTML = '<div class="alert alert-danger">Erreur de connexion au serveur.</div>';
                });
        }

        function renderEditContent(structure) {
            const html = `
                <div class="section-card">
                    <div class="card-header">
                        <h6 class="section-title">Informations generales</h6>
                        <p class="section-description">Ajustez l'identite de la structure et son operateur de rattachement.</p>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="editNomStructure" class="form-label">Nom de la structure *</label>
                                <input type="text" class="form-control" id="editNomStructure" name="nom_structure" value="${escapeHtml(structure.nom_structure)}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="editLangueNationale" class="form-label">Langue nationale *</label>
                                <select class="form-select" id="editLangueNationale" name="langue_nationale" required>
                                    <option value="">Selectionner une langue nationale</option>
                                    <?php foreach ($languesNationales as $langue): ?>
                                        <option value="<?= esc($langue['nom'], 'attr') ?>" ${String(structure.langue_nationale) === <?= json_encode((string) $langue['nom']) ?> ? 'selected' : ''}><?= esc($langue['nom']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-12">
                                <label for="editOperateurId" class="form-label">Operateur *</label>
                                <select class="form-select" id="editOperateurId" name="operateur_id" required>
                                    <option value="">Selectionner un operateur</option>
                                    <?php foreach ($operateurs as $operateur): ?>
                                        <option value="<?= $operateur['id'] ?>" ${String(structure.operateur_id) === '<?= $operateur['id'] ?>' ? 'selected' : ''}><?= esc($operateur['nom_organisation']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="section-card">
                    <div class="card-header">
                        <h6 class="section-title">Localisation</h6>
                        <p class="section-description">Corrigez si besoin la region, les subdivisions et la zone de proximite.</p>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="editRegion" class="form-label">Region *</label>
                                <input type="text" class="form-control" id="editRegion" name="region" value="${escapeHtml(structure.region)}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="editDepartement" class="form-label">Departement *</label>
                                <input type="text" class="form-control" id="editDepartement" name="departement" value="${escapeHtml(structure.departement)}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="editCommune" class="form-label">Commune *</label>
                                <input type="text" class="form-control" id="editCommune" name="commune" value="${escapeHtml(structure.commune)}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="editQuartier" class="form-label">Quartier *</label>
                                <input type="text" class="form-control" id="editQuartier" name="quartier" value="${escapeHtml(structure.quartier)}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="editIa" class="form-label">IA *</label>
                                <select class="form-select" id="editIa" name="ia" required>
                                    <option value="">Selectionner une IA</option>
                                    <?php foreach ($ias as $ia): ?>
                                        <option value="<?= esc($ia['libelle_structure'], 'attr') ?>" ${String(structure.ia).toUpperCase() === <?= json_encode(strtoupper((string) $ia['libelle_structure'])) ?> ? 'selected' : ''}><?= esc($ia['libelle_structure']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="editIef" class="form-label">IEF *</label>
                                <input type="text" class="form-control" id="editIef" name="ief" value="${escapeHtml(structure.ief)}" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="section-card">
                    <div class="card-header">
                        <h6 class="section-title">Coordonnees et statut</h6>
                        <p class="section-description">Mettez a jour les coordonnees GPS et l'etat de fonctionnement.</p>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="editLatitude" class="form-label">Latitude</label>
                                <input type="text" class="form-control" id="editLatitude" name="latitude" value="${escapeHtml(structure.latitude || '')}" placeholder="14.6937">
                            </div>
                            <div class="col-md-4">
                                <label for="editLongitude" class="form-label">Longitude</label>
                                <input type="text" class="form-control" id="editLongitude" name="longitude" value="${escapeHtml(structure.longitude || '')}" placeholder="-17.4441">
                            </div>
                            <div class="col-md-4">
                                <label for="editEtat" class="form-label">Etat *</label>
                                <select class="form-select" id="editEtat" name="etat" required>
                                    <?php foreach ($etats as $etat): ?>
                                        <option value="<?= esc($etat['code'], 'attr') ?>" ${structure.etat === <?= json_encode($etat['code']) ?> ? 'selected' : ''}><?= esc($etat['libelle']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            document.getElementById('editModalContent').innerHTML = html;
            document.getElementById('editStructureForm').action = `<?= base_url('structures/update/') ?>${structure.id}`;
        }

        function editFromView() {
            const viewModal = bootstrap.Modal.getInstance(document.getElementById('viewStructureModal'));
            if (viewModal) {
                viewModal.hide();
            }

            loadStructureEdit(currentStructureId);

            setTimeout(() => {
                const editModal = new bootstrap.Modal(document.getElementById('editStructureModal'));
                editModal.show();
            }, 180);
        }

        function setDeleteId(id) {
            deleteStructureId = id;
        }

        function confirmDelete() {
            if (deleteStructureId) {
                submitPostAction(`<?= base_url('structures/delete/') ?>${deleteStructureId}`);
            }
        }
    </script>
            </div>
        </div>
    </div>

<?= $this->endSection() ?>
