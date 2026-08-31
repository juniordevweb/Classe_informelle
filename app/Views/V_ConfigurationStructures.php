<?= $this->extend('templates/index') ?>
<?= $this->section('content') ?>

<?php
/**
 * Référentiels disponibles dans la configuration des structures.
 *
 * Le contrôleur peut fournir les compteurs dans le tableau
 * $referentielCounts, indexé par la clé de chaque référentiel.
 */
$referentiels = [
    [
        'key' => 'types_offre',
        'icon' => 'fa-school',
        'title' => "Types d'offre",
        'description' => 'Gestion des offres EBJA',
        'route' => 'parametres/types-offre',
    ],
    [
        'key' => 'langues',
        'icon' => 'fa-language',
        'title' => 'Langues nationales',
        'description' => "Gestion des langues d'enseignement",
        'route' => 'parametres/langues',
    ],
    [
        'key' => 'sites',
        'icon' => 'fa-building',
        'title' => 'Sites abritants',
        'description' => 'École, Foyer, Mosquée...',
        'route' => 'parametres/sites',
    ],
    [
        'key' => 'statuts',
        'icon' => 'fa-house',
        'title' => "Statuts d'occupation",
        'description' => 'Propriétaire, Prêt, Location...',
        'route' => 'parametres/statuts',
    ],
    [
        'key' => 'financements',
        'icon' => 'fa-hand-holding-dollar',
        'title' => 'Sources de financement',
        'description' => 'Etat, ONG, PTF...',
        'route' => 'parametres/financements',
    ],
    [
        'key' => 'programmes',
        'icon' => 'fa-diagram-project',
        'title' => 'Programmes',
        'description' => 'PNEBJA, PAPSE, UNICEF...',
        'route' => 'parametres/programmes',
    ],
    [
        'key' => 'etats',
        'icon' => 'fa-circle-check',
        'title' => 'États des structures',
        'description' => 'Ouvert, Validé, Fermé...',
        'route' => 'parametres/etats',
    ],
    [
        'key' => 'nomenclature',
        'icon' => 'fa-signature',
        'title' => 'Nomenclature',
        'description' => 'Gestion de la nomenclature',
        'route' => 'parametres/nomenclature',
    ],
    [
        'key' => 'codification',
        'icon' => 'fa-barcode',
        'title' => 'Codification',
        'description' => 'Gestion des règles de codification',
        'route' => 'parametres/codification',
    ],
];

$referentielCounts = $referentielCounts ?? [];
?>

<style>
    /* Styles limités à cette page pour conserver les composants globaux inchangés. */
    .configuration-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .configuration-card:hover {
        transform: translateY(-4px) scale(1.01);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }

    .configuration-icon {
        width: 48px;
        height: 48px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        font-size: 1.15rem;
    }
</style>

<div class="content-page">
    <div class="content">
        <div class="container-fluid mt-4">
            <!-- En-tête de page et fil d'Ariane -->
            <div class="row">
                <div class="col-sm-12">
                    <h3 class="pull-left page-title">Configuration des Structures</h3>
                    <ol class="breadcrumb pull-right">
                        <li><a href="<?= base_url('dashboard') ?>">Accueil</a></li>
                        <li><a href="<?= base_url('parametres') ?>">Paramètres</a></li>
                        <li class="active">Configuration des Structures</li>
                    </ol>
                </div>
            </div>

            <div class="mb-4">
                <p class="text-muted mb-0">Administration des référentiels utilisés pour la gestion des structures.</p>
            </div>

            <!-- Messages flash -->
            <?php foreach (['success' => 'success', 'error' => 'danger', 'warning' => 'warning'] as $type => $alertClass): ?>
                <?php if ($message = session()->getFlashdata($type)): ?>
                    <div class="alert alert-<?= $alertClass ?> alert-dismissible fade show" role="alert">
                        <?= esc($message) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>

            <!-- Tableau de bord des référentiels -->
            <div class="row g-4">
                <?php foreach ($referentiels as $referentiel): ?>
                    <div class="col-12 col-md-6 col-xl-4">
                        <div class="card configuration-card border-0 shadow-sm rounded-4 h-100">
                            <div class="card-body p-4 d-flex flex-column">
                                <div class="d-flex align-items-start justify-content-between gap-3 mb-3">
                                    <span class="configuration-icon bg-primary text-danger" aria-hidden="true">
                                        <i class="fa-solid <?= esc($referentiel['icon']) ?>"></i>
                                    </span>
                                    <span class="badge bg-danger rounded-pill">
                                        <?= esc($referentielCounts[$referentiel['key']] ?? 0) ?>
                                    </span>
                                </div>

                                <h5 class="card-title mb-2"><?= esc($referentiel['title']) ?></h5>
                                <p class="card-text text-muted mb-3"><?= esc($referentiel['description']) ?></p>

                                <div class="d-flex align-items-center justify-content-between mt-auto pt-2">
                                    <span class="badge bg-success">Actif</span>
                                    <a href="<?= base_url($referentiel['route']) ?>" class="btn btn-outline-success">
                                        <i class="fa-solid fa-gear me-1"></i> Configurer
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
