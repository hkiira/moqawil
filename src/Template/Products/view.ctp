<?php $this->extend('/Common/crud'); ?>
<?php $this->loadHelper('Form', ['templates' => 'app_form']);

$this->assign('title', 'Détails du produit : ' . h($product->title));
// Add edit button to toolbar block
$editButton = $this->Html->link('<i class="la la-edit icon-sm"></i> Modifier ce produit', ['action' => 'edit', $product->id], ['class' => 'btn btn-light-warning font-weight-bolder btn-sm mr-2', 'escape' => false]);
$this->assign('edit', $editButton);
?>

<div class="card-body p-6">
    <div class="row">
        <!-- Main details (Left Column) -->
        <div class="col-lg-8">
            <div class="card card-custom card-border mb-6">
                <div class="card-header bg-light-primary border-0 min-h-50px px-5">
                    <div class="card-title">
                        <span class="card-icon">
                            <i class="flaticon-list text-primary font-size-h5"></i>
                        </span>
                        <h5 class="card-label text-primary font-weight-bolder font-size-h6 mb-0">Informations Générales
                        </h5>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-head-custom table-vertical-center table-hover mb-0">
                            <tbody>
                                <tr>
                                    <td class="font-weight-bolder text-dark-50 w-250px pl-5"><?= __('Référence') ?></td>
                                    <td class="text-dark font-weight-bold"><?= h($product->reference) ?></td>
                                </tr>
                                <?php if ($product->has('category') && $product->category): ?>
                                    <tr>
                                        <td class="font-weight-bolder text-dark-50 pl-5"><?= __('Catégorie') ?></td>
                                        <td><?= $this->Html->link($product->category->title, ['controller' => 'Categories', 'action' => 'view', $product->category->id], ['class' => 'text-primary font-weight-bold']) ?>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                                <?php if ($product->has('supplier') && $product->supplier): ?>
                                    <tr>
                                        <td class="font-weight-bolder text-dark-50 pl-5"><?= __('Fournisseur') ?></td>
                                        <td><?= $this->Html->link($product->supplier->name, ['controller' => 'Suppliers', 'action' => 'view', $product->supplier->id], ['class' => 'text-primary font-weight-bold']) ?>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                                <tr>
                                    <td class="font-weight-bolder text-dark-50 pl-5"><?= __('Prix d\'achat') ?></td>
                                    <td class="text-success font-weight-bolder">
                                        <?= $this->Number->currency($product->buyingprice, 'MAD') ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bolder text-dark-50 pl-5"><?= __('Prix de vente') ?></td>
                                    <td class="text-primary font-weight-bolder">
                                        <?= $this->Number->currency($product->sellingprice, 'MAD') ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bolder text-dark-50 pl-5"><?= __('Commission') ?></td>
                                    <td class="text-dark"><?= $this->Number->format($product->commission) ?></td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bolder text-dark-50 pl-5"><?= __('Statut') ?></td>
                                    <td>
                                        <?php if ($product->statut): ?>
                                            <span
                                                class="label label-lg label-light-success label-inline font-weight-bolder">Actif</span>
                                        <?php else: ?>
                                            <span
                                                class="label label-lg label-light-danger label-inline font-weight-bolder">Inactif</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php if ($product->has('company') && $product->company): ?>
                                    <tr>
                                        <td class="font-weight-bolder text-dark-50 pl-5"><?= __('Société') ?></td>
                                        <td><?= $this->Html->link($product->company->name, ['controller' => 'Companies', 'action' => 'view', $product->company->id], ['class' => 'text-muted']) ?>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                                <tr>
                                    <td class="font-weight-bolder text-dark-50 pl-5"><?= __('Créé le') ?></td>
                                    <td class="text-muted font-size-sm"><?= h($product->created) ?></td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bolder text-dark-50 pl-5"><?= __('Modifié le') ?></td>
                                    <td class="text-muted font-size-sm"><?= h($product->modified) ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column (Image and Units) -->
        <div class="col-lg-4">
            <!-- Product Photo -->
            <div class="card card-custom card-border mb-6">
                <div class="card-header bg-light-primary border-0 min-h-50px px-5">
                    <div class="card-title">
                        <span class="card-icon">
                            <i class="flaticon2-image-file text-primary font-size-h5"></i>
                        </span>
                        <h5 class="card-label text-primary font-weight-bolder font-size-h6 mb-0">Photo</h5>
                    </div>
                </div>
                <div class="card-body p-6 text-center">
                    <?php
                    if (!empty($product->photo_path)) {
                        echo $this->Html->image($product->photo_path, ['alt' => h($product->title), 'class' => 'img-fluid rounded max-h-250px']);
                    } elseif ($product->photo && is_string($product->photo)) {
                        echo $this->Html->image('products_photos/' . $product->photo, ['alt' => h($product->title), 'class' => 'img-fluid rounded max-h-250px']);
                    } else {
                        echo $this->Html->image('unvailable.jpg', ['alt' => 'Pas d\'image', 'class' => 'img-fluid rounded max-h-250px']);
                    }
                    ?>
                </div>
            </div>

            <!-- Product Sales Packages -->
            <div class="card card-custom card-border mb-6">
                <div class="card-header bg-light-primary border-0 min-h-50px px-5">
                    <div class="card-title">
                        <span class="card-icon">
                            <i class="flaticon-open-box text-primary font-size-h5"></i>
                        </span>
                        <h5 class="card-label text-primary font-weight-bolder font-size-h6 mb-0">Unités de Vente</h5>
                    </div>
                </div>
                <div class="card-body p-5">
                    <?php if (!empty($product->productunites)): ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($product->productunites as $pu): ?>
                                <?php if ($pu->has('unite') && $pu->unite): ?>
                                    <div class="list-group-item d-flex align-items-center justify-content-between px-0 py-3">
                                        <div class="d-flex align-items-center">
                                            <i class="la la-box text-warning font-size-h3 mr-3"></i>
                                            <span class="font-weight-bold text-dark-75"><?= h($pu->unite->title) ?></span>
                                        </div>
                                        <span class="label label-inline label-light-primary font-weight-bolder font-size-sm">
                                            <?= $pu->quantity ?> pièces
                                        </span>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted text-center py-4 mb-0"><i class="flaticon2-warning text-warning mr-1"></i>
                            Aucune unité définie.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Packs section -->
    <?php if (!empty($product->packproducts)): ?>
        <div class="card card-custom card-border mt-6">
            <div class="card-header bg-light-primary border-0 min-h-50px px-5">
                <div class="card-title">
                    <span class="card-icon">
                        <i class="flaticon2-box-1 text-primary font-size-h5"></i>
                    </span>
                    <h5 class="card-label text-primary font-weight-bolder font-size-h6 mb-0">
                        <?= __('Produit présent dans les Packs suivants') ?>
                    </h5>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-head-custom table-vertical-center table-hover mb-0">
                        <thead>
                            <tr class="bg-light">
                                <th class="pl-5" scope="col"><?= __('Pack') ?></th>
                                <th scope="col"><?= __('Quantité dans le pack') ?></th>
                                <th scope="col" class="text-right pr-5"><?= __('Actions') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($product->packproducts as $packproduct): ?>
                                <tr>
                                    <td class="pl-5">
                                        <?= $packproduct->has('pack') ? $this->Html->link($packproduct->pack->title, ['controller' => 'Packs', 'action' => 'view', $packproduct->pack_id], ['class' => 'font-weight-bolder text-primary text-hover-primary']) : h($packproduct->pack_id) ?>
                                    </td>
                                    <td>
                                        <span
                                            class="label label-inline label-light-success font-weight-bolder"><?= h($packproduct->quantity) ?></span>
                                    </td>
                                    <td class="text-right pr-5">
                                        <?= $this->Html->link('<i class="la la-eye"></i> Voir Pack', ['controller' => 'Packs', 'action' => 'view', $packproduct->pack_id], ['class' => 'btn btn-xs btn-light-primary font-weight-bold btn-icon-sm', 'escape' => false]) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Suppliers Orders & Receipts section -->
    <div class="card card-custom card-border mt-6">
        <div class="card-header bg-light-primary border-0 min-h-50px px-5">
            <div class="card-title">
                <span class="card-icon">
                    <i class="flaticon2-shopping-cart text-primary font-size-h5"></i>
                </span>
                <h5 class="card-label text-primary font-weight-bolder font-size-h6 mb-0">
                    <?= __('Commandes Fournisseurs & Réceptions') ?>
                </h5>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-head-custom table-vertical-center table-hover mb-0">
                    <thead>
                        <tr class="bg-light">
                            <th class="pl-5" scope="col"><?= __('N° Commande') ?></th>
                            <th scope="col"><?= __('N° Réception') ?></th>
                            <th scope="col"><?= __('Date') ?></th>
                            <th scope="col"><?= __('Quantité') ?></th>
                            <th scope="col"><?= __('Prix Unitaire') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($product->supporderproducts)): ?>
                            <?php foreach ($product->supporderproducts as $sop): ?>
                                <tr>
                                    <td class="pl-5 font-weight-bold">
                                        <?php if ($sop->has('supplierorder') && $sop->supplierorder): ?>
                                            <?= $this->Html->link($sop->supplierorder->code, ['controller' => 'Supplierorders', 'action' => 'view', $sop->supplierorder->id], ['class' => 'text-primary font-weight-bolder']) ?>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="font-weight-bold">
                                        <?php if ($sop->has('receipt') && $sop->receipt): ?>
                                            <?= $this->Html->link($sop->receipt->code, ['controller' => 'Receipts', 'action' => 'view', $sop->receipt->id], ['class' => 'text-success font-weight-bolder']) ?>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-muted font-size-sm">
                                        <?= h($sop->created) ?>
                                    </td>
                                    <td>
                                        <span class="label label-inline label-light-info font-weight-bolder">
                                            <?= h($sop->quantity) ?>
                                            <?= ($sop->has('productunite') && $sop->productunite->has('unite') && $sop->productunite->unite) ? h($sop->productunite->unite->title) : __('unités') ?>
                                        </span>
                                    </td>
                                    <td class="text-dark font-weight-bolder">
                                        <?= $this->Number->currency($sop->price, 'MAD') ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-muted text-center py-4">
                                    <i class="flaticon2-warning text-warning mr-1"></i>
                                    <?= __('Aucune commande ou réception enregistrée pour ce produit.') ?>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>