<?php $this->extend('/Common/crud'); ?>
<?php $this->loadHelper('Form', [ 'templates' => 'app_form' ]); 

$this->assign('objet',$this->Form->create($product,['type'=>'file','id'=>'kt_form_1'])); 
$this->assign('title', 'Modifier le produit : ' . h($product->title));
$this->assign('subtitle', 'Modifiez les détails de base et les emballages du produit.');
?>

<div class="card-body">
    <!-- Section: Informations Générales -->
    <div class="card card-custom card-border mb-6">
        <div class="card-header bg-light-primary border-0 min-h-50px px-5">
            <div class="card-title">
                <span class="card-icon">
                    <i class="flaticon2-edit text-primary font-size-h5"></i>
                </span>
                <h5 class="card-label text-primary font-weight-bolder font-size-h6 mb-0">Modifier les détails</h5>
            </div>
        </div>
        <div class="card-body p-6">
            <div class="row">
                <div class="col-md-12 mb-4">
                    <div class="form-group mb-0">
                        <label class="font-weight-bold text-dark">Emballages</label>
                        <?= $this->Form->control('product_packages._ids', [
                            'options' => $productPackages,
                            'class' => 'form-control select2',
                            'multiple' => true,
                            'label' => false,
                            'required' => true
                        ]) ?>
                    </div>
                </div>
                <div class="col-md-6 form-group mb-4">
                    <label class="font-weight-bold text-dark">Titre <span class="text-danger">*</span></label>
                    <?= $this->Form->control('title', [
                        'class' => 'form-control form-control-solid',
                        'label' => false,
                        'required' => true
                    ]) ?>
                </div>
                <div class="col-md-6 form-group mb-4">
                    <label class="font-weight-bold text-dark">Catégorie <span class="text-danger">*</span></label>
                    <?= $this->Form->control('category_id', [
                        'options' => $categories,
                        'class' => 'form-control selectpicker',
                        'data-live-search' => 'true',
                        'label' => false,
                        'required' => true
                    ]) ?>
                </div>
                <div class="col-md-12 form-group mb-0 mt-2">
                    <label class="font-weight-bold text-dark">Description</label>
                    <?= $this->Form->control('description', [
                        'type' => 'textarea',
                        'class' => 'form-control form-control-solid',
                        'rows' => 3,
                        'label' => false
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$this->Html->script('bootstrap-select.min', ['block' => true]);
$this->Html->css('bootstrap-select.min', ['block' => true]);
$this->Html->css('select2.min', ['block' => true]);
$this->Html->script('select2.min', ['block' => true]);
?>

<?php $this->start('script'); ?>
<script>
    $(document).ready(function() {
        $('.selectpicker').selectpicker();
        
        $('.select2').select2({
            placeholder: "<?= __('Sélectionner des emballages') ?>",
            allowClear: true,
            width: "100%",
            language: {
                noResults: function() {
                    return "<?= __('Aucun résultat trouvé') ?>";
                }
            }
        });
    });
</script>
<?php $this->end(); ?>
