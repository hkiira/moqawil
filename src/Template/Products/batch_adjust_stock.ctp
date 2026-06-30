<?php
/**
 * @var \App\View\AppView $this
 * @var array $products An array of product entities/data selected for batch update.
 * @var string $product_ids_json JSON string of selected product IDs.
 * @var array $warehousesList
 * @var array $adjustmentTypes
 * @var array $movementTypes
 */
$this->extend('/Common/crud');
$this->assign('title', 'Ajustement de Stock en Groupe');
$this->assign('subtitle', 'Appliquer un ajustement de stock à plusieurs produits sélectionnés.');

$this->assign('objet', $this->Form->create(null, ['url' => ['action' => 'batchAdjustStock'], 'id' => 'batch_adjust_stock_process_form', 'type' => 'post']));
?>

<div class="card-body p-6">
    <!-- Section: Selected Products List -->
    <div class="card card-custom card-border mb-6">
        <div class="card-header bg-light-primary border-0 min-h-50px px-5">
            <div class="card-title">
                <span class="card-icon">
                    <i class="flaticon2-box text-primary font-size-h5"></i>
                </span>
                <h5 class="card-label text-primary font-weight-bolder font-size-h6 mb-0">Produits Sélectionnés</h5>
            </div>
        </div>
        <div class="card-body p-5">
            <?php if (!empty($products)): ?>
                <div class="d-flex flex-wrap">
                    <?php foreach ($products as $productId => $productTitle): ?>
                        <span class="label label-xl label-inline label-light-primary font-weight-bold mr-2 mb-2">
                            <i class="la la-box font-size-lg mr-1 text-primary"></i> <?= h($productTitle) ?> <small class="text-muted ml-1">(ID: <?= $productId ?>)</small>
                        </span>
                    <?php endforeach; ?>
                </div>
                <?= $this->Form->hidden('selected_product_ids_json', ['value' => $product_ids_json]); ?>
                <?= $this->Form->hidden('process_batch_update', ['value' => '1']); ?>
            <?php else: ?>
                <div class="alert alert-custom alert-light-danger role="alert">
                    <div class="alert-icon"><i class="flaticon2-warning"></i></div>
                    <div class="alert-text font-weight-bold">Aucun produit n'a été sélectionné ou transmis. Veuillez retourner à la liste des produits.</div>
                </div>
                <?php $this->assign('submit_disabled', true); ?>
            <?php endif; ?>
        </div>
    </div>

    <?php if (!empty($products)): ?>
        <!-- Section: Common Settings -->
        <div class="card card-custom card-border mb-6 bg-light-neutral">
            <div class="card-header bg-light-warning border-0 min-h-50px px-5">
                <div class="card-title">
                    <span class="card-icon">
                        <i class="flaticon2-settings text-warning font-size-h5"></i>
                    </span>
                    <h5 class="card-label text-warning font-weight-bolder font-size-h6 mb-0">Paramètres Communs (Requis)</h5>
                </div>
            </div>
            <div class="card-body p-6">
                <div class="row">
                    <div class="col-md-4 form-group">
                        <label class="font-weight-bold text-dark">Entrepôt</label>
                        <?= $this->Form->control('common_warehouse_id', [
                            'label' => false,
                            'options' => $warehousesList,
                            'empty' => 'Sélectionner un entrepôt',
                            'class' => 'form-control select2',
                            'required' => true,
                            'id' => 'common_warehouse_id'
                        ]); ?>
                    </div>
                    <div class="col-md-4 form-group">
                        <label class="font-weight-bold text-dark">Raison / Type de Mouvement</label>
                        <?= $this->Form->control('common_movement_type', [
                            'label' => false,
                            'options' => $movementTypes,
                            'empty' => 'Sélectionner une raison',
                            'class' => 'form-control select2',
                            'required' => true,
                            'id' => 'common_movement_type'
                        ]); ?>
                    </div>
                    <div class="col-md-4 form-group">
                        <label class="font-weight-bold text-dark">Notes Communes</label>
                        <?= $this->Form->control('common_notes', [
                            'label' => false,
                            'type' => 'textarea',
                            'rows' => 1,
                            'class' => 'form-control form-control-solid',
                            'placeholder' => 'Ajouter des notes...',
                            'id' => 'common_notes'
                        ]); ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section: Individual Adjustments -->
        <h5 class="mb-4 mt-6 text-dark font-weight-bolder"><i class="flaticon-list-3 text-primary mr-2"></i> Ajustements Individuels par Produit</h5>
        
        <?php foreach ($products as $productId => $productTitle): ?>
            <div class="card card-custom card-border mb-4">
                <div class="card-body p-5">
                    <div class="d-flex align-items-center justify-content-between mb-4 border-bottom pb-2">
                        <h6 class="font-weight-bolder text-dark mb-0">
                            <i class="la la-box font-size-h3 text-muted mr-2"></i><?= h($productTitle) ?>
                        </h6>
                        <span class="label label-inline label-light-dark font-weight-bold">ID: <?= $productId ?></span>
                    </div>
                    <?= $this->Form->hidden('products_adjustments.'.$productId.'.product_id', ['value' => $productId]); ?>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold">Type d'Ajustement <span class="text-danger">*</span></label>
                            <?= $this->Form->control('products_adjustments.'.$productId.'.adjustment_type', [
                                'label' => false,
                                'options' => $adjustmentTypes,
                                'class' => 'form-control select2-product-adjust',
                                'empty' => 'Choisir le type d\'ajustement',
                                'required' => true
                            ]); ?>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold">Quantité <span class="text-danger">*</span></label>
                            <?= $this->Form->control('products_adjustments.'.$productId.'.quantity', [
                                'label' => false,
                                'type' => 'number',
                                'step' => 'any',
                                'class' => 'form-control form-control-solid',
                                'placeholder' => 'Ex: 10 ou -5',
                                'required' => true
                            ]); ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php if (!empty($products)): ?>
    <?= $this->Html->script('/assets/js/pages/crud/forms/widgets/select2.js', ['block' => 'script_bottom']) ?>
    <?= $this->Html->scriptStart(['block'=>'script_bottom']) ?>
    $(document).ready(function() {
        $.fn.select2.defaults.set("width", "100%");
        // Initialize common select2s
        $('#common_warehouse_id, #common_movement_type').select2({
            placeholder: 'Sélectionner une option',
        });
        // Initialize select2s within each product row
        $('.select2-product-adjust').select2({
            placeholder: 'Choisir type',
        });

        // FormValidation
        if (typeof FormValidation !== 'undefined' && document.getElementById('batch_adjust_stock_process_form')) {
            var form = document.getElementById('batch_adjust_stock_process_form');
            var fv = FormValidation.formValidation(form, {
                fields: {
                    'common_warehouse_id': {
                        validators: { notEmpty: { message: 'L\'entrepôt commun est requis.' } }
                    },
                    'common_movement_type': {
                        validators: { notEmpty: { message: 'Le type de mouvement commun est requis.' } }
                    }
                    // Per-product validation
                    <?php foreach ($products as $productId => $productTitle): ?>
                        ,'products_adjustments[<?= $productId ?>][adjustment_type]': {
                            validators: { notEmpty: { message: 'Type d\'ajustement requis pour <?= h($productTitle) ?>.' } }
                        },
                        'products_adjustments[<?= $productId ?>][quantity]': {
                            validators: {
                                notEmpty: { message: 'Quantité requise pour <?= h($productTitle) ?>.' },
                                numeric: { message: 'La quantité doit être un nombre.' }
                            }
                        }
                    <?php endforeach; ?>
                },
                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    bootstrap: new FormValidation.plugins.Bootstrap(),
                    submitButton: new FormValidation.plugins.SubmitButton(),
                    defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
                }
            });
        }
    });
    <?= $this->Html->scriptEnd(); ?>
<?php endif; ?>
