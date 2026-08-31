<!-- <!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de la Structure</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1><i class="fas fa-building"></i> Détails de la Structure</h1>
                    <div>
                        <a href="<?= base_url('structures/edit/' . $structure['id']) ?>" class="btn btn-warning me-2">
                            <i class="fas fa-edit"></i> Modifier
                        </a>
                        <a href="<?= base_url('structures') ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Retour à la liste
                        </a>
                    </div>
                </div>

                <div class="row">
                    <!-- Informations générales -->
                    <div class="col-md-8">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5><i class="fas fa-info-circle"></i> Informations Générales</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Code:</strong> <?= esc($structure['code_structure']) ?></p>
                                        <p><strong>Nom:</strong> <?= esc($structure['nom_structure']) ?></p>
                                        <p><strong>Langue Nationale:</strong> <?= esc($structure['langue_nationale']) ?></p>
                                        <p><strong>Opérateur:</strong> <?= esc($operateur['nom_organisation']) ?></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>État:</strong>
                                            <?php
                                            $badgeClass = match($structure['etat']) {
                                                'EN_ATTENTE' => 'warning',
                                                'VALIDE' => 'info',
                                                'OUVERT' => 'success',
                                                'FERME' => 'danger',
                                                'GELE' => 'secondary',
                                                default => 'light'
                                            };
                                            ?>
                                            <span class="badge bg-<?= $badgeClass ?>"><?= esc($structure['etat']) ?></span>
                                        </p>
                                        <p><strong>Nombre de Classes:</strong> <?= $nombreClasses ?></p>
                                        <p><strong>Nombre d'Apprenants:</strong> <?= $nombreApprenants ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Localisation -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5><i class="fas fa-map-marker-alt"></i> Localisation</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Région:</strong> <?= esc($structure['region']) ?></p>
                                        <p><strong>Département:</strong> <?= esc($structure['departement']) ?></p>
                                        <p><strong>Commune:</strong> <?= esc($structure['commune']) ?></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Quartier:</strong> <?= esc($structure['quartier']) ?></p>
                                        <p><strong>IA:</strong> <?= esc($structure['ia']) ?></p>
                                        <p><strong>IEF:</strong> <?= esc($structure['ief']) ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Géolocalisation -->
                        <?php if ($structure['latitude'] && $structure['longitude']): ?>
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5><i class="fas fa-globe"></i> Géolocalisation</h5>
                            </div>
                            <div class="card-body">
                                <p><strong>Latitude:</strong> <?= esc($structure['latitude']) ?></p>
                                <p><strong>Longitude:</strong> <?= esc($structure['longitude']) ?></p>
                                <!-- Ici, vous pouvez intégrer une carte si nécessaire -->
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Dates -->
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h5><i class="fas fa-calendar"></i> Dates</h5>
                            </div>
                            <div class="card-body">
                                <p><strong>Créé le:</strong> <?= date('d/m/Y H:i', strtotime($structure['created_at'])) ?></p>
                                <p><strong>Modifié le:</strong> <?= date('d/m/Y H:i', strtotime($structure['updated_at'])) ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> -->