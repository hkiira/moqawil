<?php $this->extend('/Common/crud'); ?>
<?php $this->loadHelper('Form', ['templates' => 'app_form']);
$this->assign('objet', $this->Form->create($product, ['type' => 'file', 'id' => 'kt_form_1']));
$this->assign('title', 'Ajouter un Produit');
$this->assign('subtitle', 'Créez un nouveau produit et définissez ses unités de mesure et de vente.');
?>

<div class="card-body">
    <!-- Section: Informations Générales -->
    <div class="card card-custom card-border mb-6">
        <div class="card-header bg-light-primary border-0 min-h-50px px-5">
            <div class="card-title">
                <span class="card-icon">
                    <i class="flaticon2-information text-primary font-size-h5"></i>
                </span>
                <h5 class="card-label text-primary font-weight-bolder font-size-h6 mb-0">Informations Générales</h5>
            </div>
        </div>
        <div class="card-body p-6">
            <div class="row">
                <div class="col-xl-6">
                    <div class="form-group mb-4">
                        <label class="font-weight-bold">Nom de l'article <span class="text-danger">*</span></label>
                        <?= $this->Form->control('title', ['label' => false, 'class' => 'form-control form-control-solid', 'placeholder' => 'Entrez le nom du produit']); ?>
                        <?= $this->Form->control('brand_id', ['type' => 'hidden', 'value' => 1]); ?>
                    </div>
                    <div class="form-group mb-4">
                        <label class="font-weight-bold">Catégorie <span class="text-danger">*</span></label>
                        <?= $this->Form->control('category_id', ['label' => false, 'options' => $categories, 'class' => 'form-control select2 selectpicker', 'data-live-search' => 'true', 'empty' => 'Sélectionner une Catégorie']); ?>
                    </div>
                    <div class="form-group mb-4">
                        <label class="font-weight-bold">Gestion du stock</label>
                        <?php
                        $stockOptions = [0 => 'Non', 1 => 'Oui'];
                        echo $this->Form->control('gstock', ['label' => false, 'options' => $stockOptions, 'class' => 'form-control select2', 'default' => 0]);
                        ?>
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="form-group mb-4">
                        <label class="font-weight-bold">Photo du produit</label>
                        <div class="custom-file">
                            <?= $this->Form->control('photo.photo', ['label' => false, 'type' => 'file', 'class' => 'custom-file-input', 'id' => 'customFile']); ?>
                            <label class="custom-file-label" for="customFile">Choisir un fichier</label>
                        </div>
                    </div>
                    <?= $this->Form->control('commission', ['class' => 'form-control', 'label' => false, 'type' => 'hidden', 'value' => 0]); ?>
                    <?= $this->Form->control('packtype_id', ['type' => 'hidden', 'value' => 1]); ?>
                    <div class="form-group mb-4">
                        <label class="font-weight-bold">Prix d'achat global <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <?= $this->Form->control('buyingprice', ['class' => 'form-control form-control-solid', 'label' => false, 'type' => 'number', 'step' => 'any', 'placeholder' => '0.00', 'required' => 'required']); ?>
                            <div class="input-group-append"><span class="input-group-text">DH</span></div>
                        </div>
                    </div>
                    <div class="form-group mb-4">
                        <label class="font-weight-bold">Statut</label>
                        <?php
                        $statutOptions = [0 => 'Inactif', 1 => 'Actif'];
                        echo $this->Form->control('statut', [
                            'label' => false,
                            'options' => $statutOptions,
                            'class' => 'form-control select2',
                            'default' => 1
                        ]);
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section: Unité de Mesure -->
    <div class="card card-custom card-border mb-6">
        <div class="card-header bg-light-primary border-0 min-h-50px px-5">
            <div class="card-title">
                <span class="card-icon">
                    <i class="flaticon-settings-1 text-primary font-size-h5"></i>
                </span>
                <h5 class="card-label text-primary font-weight-bolder font-size-h6 mb-0">Unité de Mesure</h5>
            </div>
        </div>
        <div class="card-body p-6">
            <div class="row">
                <div class="col-md-6 form-group">
                    <label class="font-weight-bold">Valeur de la mesure <span class="text-danger">*</span></label>
                    <?= $this->Form->control('measurement_quantity', [
                        'label' => false,
                        'type' => 'number',
                        'class' => 'form-control form-control-solid',
                        'step' => '0.01',
                        'min' => '0.01',
                        'value' => '1',
                        'placeholder' => 'Ex: 1.5'
                    ]); ?>
                </div>
                <div class="col-md-6 form-group">
                    <label class="font-weight-bold">Unité physique <span class="text-danger">*</span></label>
                    <?= $this->Form->control('measurement_unit_id', [
                        'label' => false,
                        'options' => $measurementUnits,
                        'class' => 'form-control select2',
                        'empty' => 'Sélectionner une unité physique'
                    ]); ?>
                </div>
            </div>
            <span class="form-text text-muted font-size-sm mt-2"><i class="flaticon-questions text-muted mr-1"></i> Exemple: 1.5 Litre (L), 2 Kilogramme (kg), 500 Gramme (g), etc.</span>
        </div>
    </div>

    <!-- Section: Unités de Vente (Repeater) -->
    <div class="card card-custom card-border mb-6">
        <div class="card-header bg-light-primary border-0 min-h-50px px-5">
            <div class="card-title">
                <span class="card-icon">
                    <i class="flaticon-open-box text-primary font-size-h5"></i>
                </span>
                <h5 class="card-label text-primary font-weight-bolder font-size-h6 mb-0">Unités de Vente (Packages)</h5>
            </div>
        </div>
        <div class="card-body p-6">
            <div id="productunites_repeater">
                <div data-repeater-list="productunites">
                    <div data-repeater-item class="form-group row align-items-center mb-5 p-5 border border-dashed rounded bg-light-neutral">
                        <div class="col-md-5 form-group mb-0">
                            <label class="font-weight-bold">Quantité (Nombre de pièces):</label>
                            <?= $this->Form->control('productunites.__INDEX__.quantity', [
                                'label' => false,
                                'type' => 'number',
                                'min' => 1,
                                'class' => 'form-control product-quantity form-control-solid',
                                'value' => 1,
                                'required' => false
                            ]); ?>
                        </div>
                        <div class="col-md-5 form-group mb-0">
                            <label class="font-weight-bold">Type de Package (Carton, Sac, Boite):</label>
                            <?= $this->Form->control('productunites.__INDEX__.unite_id', [
                                'label' => false,
                                'options' => $unites,
                                'class' => 'form-control product-select select2-repeater',
                                'empty' => 'Sélectionner un Package',
                                'required' => false
                            ]); ?>
                        </div>
                        <div class="col-md-2 form-group mb-0 text-right mt-6">
                            <a href="javascript:;" data-repeater-delete class="btn btn-sm font-weight-bolder btn-clean btn-icon btn-hover-icon-danger btn-hover-light-danger" title="Supprimer">
                                <i class="la la-trash-o icon-xl"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="form-group row mb-0">
                    <div class="col-lg-4">
                        <a href="javascript:;" data-repeater-create class="btn btn-sm font-weight-bolder btn-light-primary">
                            <i class="la la-plus"></i> Ajouter un Package
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->Html->script(['/assets/js/pages/crud/forms/widgets/select2.js', '/assets/plugins/custom/formrepeater/formrepeater.bundle.js'], ['block' => 'script_bottom']) ?>

<?= $this->Html->scriptStart(['block'=>'script_bottom']) ?>
$(document).ready(function() {
    // Custom file input name display
    $(".custom-file-input").on("change", function() {
        var fileName = $(this).val().split("\\").pop();
        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });

    $.fn.select2.defaults.set("width", "100%");
    $('.select2:not(.select2-repeater)').each(function() {
        $(this).select2({
            placeholder: $(this).find('option[value=""]').text() || 'Sélectionner une option',
        });
    });
    
    var repeater = $('#productunites_repeater').repeater({
        initEmpty: false,
        defaultValues: {
            'productunites[__INDEX__][quantity]': 1,
            'productunites[__INDEX__][statut]': '1'
        },
        show: function () {
            $(this).slideDown();
            var item = $(this);
            var list = item.closest('[data-repeater-list]');
            var index = list.find('[data-repeater-item]').length - 1;

            item.find('[name*="__INDEX__"]').each(function() {
                var currentName = $(this).attr('name');
                var newName = currentName.replace('__INDEX__', index);
                $(this).attr('name', newName);
                var newId = newName.replace(/\[/g, '-').replace(/\]/g, '');
                $(this).attr('id', newId);
            });
            
            item.find('.select2-repeater').select2({
                placeholder: 'Sélectionner un Package',
                width: '100%'
            });
        },
        hide: function (deleteElement) {
            if (confirm("Êtes-vous sûr de vouloir supprimer ce package ?")) {
                $(this).slideUp(deleteElement);
            }
        }
    });
});
<?= $this->Html->scriptEnd(); ?>
