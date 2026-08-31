<?= $this->extend('templates/index') ?>
<?= $this->section('content') ?>

<div class="content-page">
    <div class="content">
        <div class="container-fluid mt-4">
            <div class="row">
                <div class="col-sm-12">
                    <h3 class="pull-left page-title"><?= esc($config['title']) ?></h3>
                    <ol class="breadcrumb pull-right">
                        <li><a href="<?= base_url('dashboard') ?>">Accueil</a></li>
                        <li><a href="<?= base_url('parametres/configuration-structures') ?>">Paramètres</a></li>
                        <li class="active"><?= esc($config['title']) ?></li>
                    </ol>
                </div>
            </div>

            <?php foreach (['success' => 'success', 'error' => 'danger', 'warning' => 'warning'] as $type => $alertClass): ?>
                <?php if ($message = session()->getFlashdata($type)): ?>
                    <div class="alert alert-<?= $alertClass ?> alert-dismissible fade show" role="alert">
                        <?= esc($message) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
                    <div>
                        <h5 class="mb-1">Gestion du référentiel</h5>
                        <small class="text-muted"><?= count($items) ?> élément(s)</small>
                    </div>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#referentielModal">
                        <i class="fa fa-plus me-1"></i> Ajouter
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle">
                            <thead class="table-primary">
                                <tr>
                                    <?php foreach ($config['fields'] as $field): ?>
                                        <th><?= esc($config['labels'][$field]) ?></th>
                                    <?php endforeach; ?>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($items === []): ?>
                                    <tr><td colspan="<?= count($config['fields']) + 1 ?>" class="text-center text-muted py-4">Aucun élément enregistré.</td></tr>
                                <?php endif; ?>
                                <?php foreach ($items as $item): ?>
                                    <tr>
                                        <?php foreach ($config['fields'] as $field): ?>
                                            <td>
                                                <?php if ($field === 'actif'): ?>
                                                    <span class="badge bg-<?= (int) ($item[$field] ?? 0) === 1 ? 'success' : 'secondary' ?>">
                                                        <?= (int) ($item[$field] ?? 0) === 1 ? 'Actif' : 'Inactif' ?>
                                                    </span>
                                                <?php else: ?>
                                                    <?= esc((string) ($item[$field] ?? '')) ?>
                                                <?php endif; ?>
                                            </td>
                                        <?php endforeach; ?>
                                        <td class="text-end text-nowrap">
                                            <button type="button" class="btn btn-sm btn-outline-primary edit-referentiel" data-bs-toggle="modal" data-bs-target="#referentielModal" data-item='<?= esc(json_encode($item, JSON_UNESCAPED_UNICODE), 'attr') ?>' title="Modifier">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                            <form method="post" action="<?= base_url('parametres/' . $slug . '/delete/' . $item['id']) ?>" class="d-inline delete-referentiel-form" data-name="<?= esc((string) ($item[$config['label']] ?? ''), 'attr') ?>">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer"><i class="fa fa-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="referentielModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form method="post" action="<?= base_url('parametres/' . $slug . '/store') ?>" id="referentielForm">
                <?= csrf_field() ?>
                <div class="modal-header text-white bg-primary">
                    <h5 class="modal-title" id="referentielModalTitle">Ajouter un élément</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <?php foreach ($config['fields'] as $field): ?>
                            <?php if ($field === 'actif'): ?>
                                <div class="col-12 form-check form-switch ms-2">
                                    <input type="checkbox" class="form-check-input" name="actif" id="field-actif" value="1" checked>
                                    <label class="form-check-label" for="field-actif">Actif</label>
                                </div>
                            <?php elseif ($field === 'description'): ?>
                                <div class="col-12">
                                    <label class="form-label" for="field-<?= esc($field) ?>"><?= esc($config['labels'][$field]) ?></label>
                                    <textarea class="form-control" name="<?= esc($field) ?>" id="field-<?= esc($field) ?>" rows="3"></textarea>
                                </div>
                            <?php else: ?>
                                <div class="col-md-<?= count($config['fields']) > 4 ? '6' : '12' ?>">
                                    <label class="form-label" for="field-<?= esc($field) ?>"><?= esc($config['labels'][$field]) ?></label>
                                    <input type="<?= esc($config['types'][$field] ?? 'text') ?>" class="form-control" name="<?= esc($field) ?>" id="field-<?= esc($field) ?>" <?= $field === $config['label'] ? 'required' : '' ?><?= $slug === 'types-offre' && $field === 'code' ? ' readonly placeholder="Généré automatiquement" title="Le code est généré automatiquement selon le type d’offre."' : '' ?>>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('referentielModal');
    const form = document.getElementById('referentielForm');
    const title = document.getElementById('referentielModalTitle');
    const baseAction = form.action;
    const offerLabel = document.getElementById('field-libelle');
    const offerCode = document.getElementById('field-code');

    if ('<?= esc($slug) ?>' === 'types-offre' && offerLabel && offerCode) {
        offerCode.value = 'Généré automatiquement (préfixe 4)';
        offerCode.title = 'Le code est généré automatiquement avec le préfixe 4 et le suffixe de l’offre.';
    }

    document.querySelectorAll('.edit-referentiel').forEach((button) => {
        button.addEventListener('click', () => {
            const item = JSON.parse(button.dataset.item);
            title.textContent = 'Modifier un élément';
            form.action = `${baseAction.replace('/store', '')}/update/${item.id}`;

            Object.keys(item).forEach((field) => {
                const input = document.getElementById(`field-${field}`);
                if (!input) return;
                if ('<?= esc($slug) ?>' === 'types-offre' && field === 'code') return;
                if (input.type === 'checkbox') input.checked = Number(item[field]) === 1;
                else input.value = item[field] ?? '';
            });

        });
    });

    document.querySelectorAll('.delete-referentiel-form').forEach((deleteForm) => {
        deleteForm.addEventListener('submit', (event) => {
            event.preventDefault();

            const name = deleteForm.dataset.name;

            Swal.fire({
                title: 'Voulez-vous vraiment supprimer ?',
                text: `« ${name} » .`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Oui, supprimer',
                cancelButtonText: 'Annuler',
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteForm.submit();
                }
            });
        });
    });

    modal.addEventListener('hidden.bs.modal', () => {
        title.textContent = 'Ajouter un élément';
        form.action = baseAction;
        form.reset();
        const active = document.getElementById('field-actif');
        if (active) active.checked = true;
    });
});
</script>

<?= $this->endSection() ?>
