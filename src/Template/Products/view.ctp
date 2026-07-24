<?php $this->extend('/Common/crud'); ?>
<?php $this->loadHelper('Form', ['templates' => 'app_form']);

$this->assign('title', 'Détails du produit : ' . h($product->title));
$editButton = $this->Html->link('<i class="la la-edit icon-sm"></i> Modifier ce produit', ['action' => 'edit', $product->id], ['class' => 'btn btn-light-warning font-weight-bolder btn-sm mr-2', 'escape' => false]);
$printButton = $this->Html->link('<i class="la la-print icon-sm"></i> Imprimer PDF', ['action' => 'print', $product->id, '_ext' => 'pdf', '?' => ['start_date' => $startDate, 'end_date' => $endDate]], ['class' => 'btn btn-light-primary font-weight-bolder btn-sm mr-2', 'escape' => false, 'target' => '_blank']);
$this->assign('edit', $printButton . $editButton);

$realStock = 0;
if (!empty($product->whproducts)) {
    foreach ($product->whproducts as $whp) {
        if ($whp->has('warehouse') && $whp->warehouse && $whp->warehouse->whnature_id == 1) {
            $realStock += $whp->quantity;
        }
    }
}
$totalValue = $realStock * $product->buyingprice;

$notReceivedQty = 0;
$totalOrderedQty = 0;
$totalReceivedQty = 0;
if (!empty($product->supporderproducts)) {
    foreach ($product->supporderproducts as $sop) {
        $hasReceipt = ($sop->has('receipt') && $sop->receipt) || !empty($sop->receipt_id);
        $sopStatut = $sop->statut;
        if (($sopStatut === null || $sopStatut === '') && $sop->has('supplierorder') && $sop->supplierorder) {
            $sopStatut = $sop->supplierorder->statut;
        }

        $totalOrderedQty += $sop->quantity;
        if ($hasReceipt) {
            $totalReceivedQty += $sop->quantity;
        } else if ((int)$sopStatut !== 8) {
            $notReceivedQty += $sop->quantity;
        }
    }
}
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
                <div class="card-body p-6">
                    <span class="card-icon d-block mb-3">
                        <i class="flaticon-boxes text-info font-size-h1"></i>
                    </span>
                    <div class="font-weight-bolder text-info font-size-h4 mb-2 d-block">
                        Stock Réel
                    </div>
                    <div class="font-weight-bold text-info mt-2">
                        <span class="font-size-h1 mr-2">
                            <?= $realStock ?>
                        </span> <span class="font-size-lg">unités</span>
                    </div>
                    <div class="mt-5 pt-4 border-top border-info border-opacity-20">
                        <span class="text-info font-weight-bolder font-size-lg">Valeur Totale :</span>
                        <div class="text-info font-weight-bolder font-size-h2 mt-1">
                            <?= $this->Number->currency($totalValue, 'MAD') ?>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-top border-warning border-opacity-20">
                        <span class="text-warning font-weight-bolder font-size-lg">Non Réceptionné (En attente) :</span>
                        <div class="text-warning font-weight-bolder font-size-h2 mt-1">
                            <?= $notReceivedQty ?> <span class="font-size-lg">unités</span>
                        </div>
                    </div>
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

    <!-- Date Filter Toolbar -->
    <div class="card card-custom mb-6">
        <div class="card-body py-4">
            <form id="filter_form" method="GET" action="<?= $this->Url->build(['action' => 'view', $product->id]) ?>"
                class="form d-flex align-items-center">
                <div class="form-group mb-0 d-flex align-items-center">
                    <label class="mb-0 font-weight-bold mr-4">Période :</label>
                    <a href="#" class="btn btn-light font-weight-bold mr-2" id="kt_product_daterangepicker"
                        data-toggle="tooltip" title="Sélectionnez la plage des dates" data-placement="left">
                        <span class="text-muted font-size-base font-weight-bold mr-2"
                            id="kt_product_daterangepicker_title">Période</span>
                        <span class="text-primary font-size-base font-weight-bolder"
                            id="kt_product_daterangepicker_date"><?= ($startDate && $endDate) ? h($startDate) . ' / ' . h($endDate) : date('Y-m-d') ?></span>
                    </a>
                    <input type="hidden" name="start_date" id="start_date" value="<?= h($startDate) ?>" />
                    <input type="hidden" name="end_date" id="end_date" value="<?= h($endDate) ?>" />
                </div>
                <button type="submit" class="btn btn-sm btn-primary ml-4 font-weight-bolder">
                    <i class="la la-filter icon-sm"></i>Filtrer
                </button>
                <?php if ($startDate || $endDate): ?>
                    <a href="<?= $this->Url->build(['action' => 'view', $product->id]) ?>"
                        class="btn btn-sm btn-light-danger ml-2 font-weight-bolder">
                        <i class="la la-close icon-sm"></i>Réinitialiser
                    </a>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <!-- Suppliers Orders & Receipts section -->
    <div class="card card-custom card-border mt-6">
        <div class="card-header bg-light-primary border-0 min-h-50px px-5 d-flex align-items-center justify-content-between">
            <div class="card-title mb-0">
                <span class="card-icon">
                    <i class="flaticon2-shopping-cart text-primary font-size-h5"></i>
                </span>
                <h5 class="card-label text-primary font-weight-bolder font-size-h6 mb-0">
                    <?= __('Commandes Fournisseurs & Réceptions') ?>
                </h5>
            </div>
            <div>
                <span class="label label-lg label-inline label-light-warning font-weight-bolder font-size-base py-3 px-4">
                    <i class="la la-clock text-warning mr-1"></i> <?= __('Total Non Réceptionné :') ?> <?= $notReceivedQty ?>
                </span>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-head-custom table-vertical-center table-hover mb-0">
                    <thead>
                        <tr class="bg-light">
                            <th class="pl-5" scope="col"><?= __('N° Commande') ?></th>
                            <th scope="col"><?= __('N° Réception') ?></th>
                            <th scope="col"><?= __('Statut') ?></th>
                            <th scope="col"><?= __('Date') ?></th>
                            <th scope="col"><?= __('Qté Commandée') ?></th>
                            <th scope="col"><?= __('Qté Reçue') ?></th>
                            <th scope="col" class="text-warning"><?= __('Qté Non Reçue') ?></th>
                            <th scope="col"><?= __('Prix Unitaire') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($product->supporderproducts)): ?>
                            <?php foreach ($product->supporderproducts as $sop): ?>
                                <?php
                                    $sopStatut = $sop->statut;
                                    if (($sopStatut === null || $sopStatut === '') && $sop->has('supplierorder') && $sop->supplierorder) {
                                        $sopStatut = $sop->supplierorder->statut;
                                    }
                                    $hasReceipt = ($sop->has('receipt') && $sop->receipt) || !empty($sop->receipt_id);
                                    $unitTitle = ($sop->has('productunite') && $sop->productunite->has('unite') && $sop->productunite->unite) ? h($sop->productunite->unite->title) : __('unités');
                                ?>
                                <tr>
                                    <td class="pl-5 font-weight-bold">
                                        <?php if ($sop->has('supplierorder') && $sop->supplierorder): ?>
                                            <?= $this->Html->link($sop->supplierorder->code, ['controller' => 'Supplierorders', 'action' => 'view', $sop->supplierorder->id], ['class' => 'text-primary font-weight-bolder']) ?>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="font-weight-bold">
                                        <?php if ($hasReceipt): ?>
                                            <?= $this->Html->link($sop->receipt->code ?? ('#' . $sop->receipt_id), ['controller' => 'Receipts', 'action' => 'view', $sop->receipt->id ?? $sop->receipt_id], ['class' => 'text-success font-weight-bolder']) ?>
                                        <?php else: ?>
                                            <?php if ((int)$sopStatut === 8): ?>
                                                <span class="label label-inline label-light-danger font-weight-bolder"><?= __('Annulée') ?></span>
                                            <?php else: ?>
                                                <span class="label label-inline label-light-warning font-weight-bolder"><?= __('En attente') ?></span>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($hasReceipt): ?>
                                            <span class="label label-inline label-light-success font-weight-bolder"><?= __('Réceptionné') ?></span>
                                        <?php elseif ((int)$sopStatut === 8): ?>
                                            <span class="label label-inline label-light-danger font-weight-bolder"><?= __('Annulée') ?></span>
                                        <?php else: ?>
                                            <span class="label label-inline label-light-warning font-weight-bolder"><?= __('En attente') ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-muted font-size-sm">
                                        <?= h($sop->created) ?>
                                    </td>
                                    <td>
                                        <span class="label label-inline label-light-info font-weight-bolder">
                                            <?= h($sop->quantity) ?> <?= $unitTitle ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($hasReceipt): ?>
                                            <span class="label label-inline label-light-success font-weight-bolder">
                                                <?= h($sop->quantity) ?> <?= $unitTitle ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="label label-inline label-light-dark font-weight-bolder">
                                                0 <?= $unitTitle ?>
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!$hasReceipt && (int)$sopStatut !== 8): ?>
                                            <span class="label label-inline label-light-warning font-weight-bolder">
                                                <?= h($sop->quantity) ?> <?= $unitTitle ?>
                                            </span>
                                        <?php elseif ((int)$sopStatut === 8): ?>
                                            <span class="label label-inline label-light-danger font-weight-bolder">
                                                <?= __('Annulée') ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="label label-inline label-light-dark font-weight-bolder">
                                                0 <?= $unitTitle ?>
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-dark font-weight-bolder">
                                        <?= $this->Number->currency($sop->price, 'MAD') ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-muted text-center py-4">
                                    <i class="flaticon2-warning text-warning mr-1"></i>
                                    <?= __('Aucune commande ou réception enregistrée pour ce produit.') ?>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                    <?php if (!empty($product->supporderproducts)): ?>
                        <tfoot>
                            <tr class="bg-light-warning font-weight-bolder">
                                <td colspan="4" class="pl-5 text-warning font-weight-boldest text-uppercase">
                                    <?= __('Total Non Réceptionné (En Attente)') ?>
                                </td>
                                <td colspan="4">
                                    <span class="label label-inline label-light-warning font-weight-boldest font-size-h6 px-4 py-2">
                                        <?= $notReceivedQty ?>
                                    </span>
                                </td>
                            </tr>
                        </tfoot>
                    <?php endif; ?>
                </table>
            </div>
        </div>
    </div>

    <!-- Bons de Conditionnement section -->
    <div class="card card-custom card-border mt-6">
        <div class="card-header bg-light-primary border-0 min-h-50px px-5">
            <div class="card-title">
                <span class="card-icon">
                    <i class="flaticon2-box text-primary font-size-h5"></i>
                </span>
                <h5 class="card-label text-primary font-weight-bolder font-size-h6 mb-0">
                    <?= __('Bons de Conditionnement') ?>
                </h5>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-head-custom table-vertical-center table-hover mb-0">
                    <thead>
                        <tr class="bg-light">
                            <th class="pl-5" scope="col"><?= __('N° Bon') ?></th>
                            <th scope="col"><?= __('Date') ?></th>
                            <th scope="col"><?= __('Quantité') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($product->slipproducts)): ?>
                            <?php foreach ($product->slipproducts as $sp): ?>
                                <tr>
                                    <td class="pl-5 font-weight-bold">
                                        <?php if ($sp->has('slip') && $sp->slip): ?>
                                            <?= $this->Html->link($sp->slip->code, ['controller' => 'Slips', 'action' => 'view', $sp->slip->id], ['class' => 'text-primary font-weight-bolder']) ?>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-muted font-size-sm">
                                        <?= h($sp->created) ?>
                                    </td>
                                    <td>
                                        <span class="label label-inline label-light-info font-weight-bolder">
                                            <?= h($sp->quantity) ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" class="text-muted text-center py-4">
                                    <i class="flaticon2-warning text-warning mr-1"></i>
                                    <?= __('Aucun bon de conditionnement enregistré pour ce produit.') ?>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php $this->append('script_bottom'); ?>
<?= $this->Html->script('/assets/js/pages/widgets.js', ['block' => 'script_bottom']) ?>
<script>
    $(document).ready(function () {
        var start = <?= ($startDate) ? "moment('" . h($startDate) . "')" : "moment().startOf('month')" ?>;
        var end = <?= ($endDate) ? "moment('" . h($endDate) . "')" : "moment().endOf('month')" ?>;

        function cb(start, end, label) {
            var range = start.locale('fr').format('D MMM YYYY') + ' - ' + end.locale('fr').format('D MMM YYYY');
            $('#kt_product_daterangepicker_date').html(range);
        }

        $('#kt_product_daterangepicker').daterangepicker({
            startDate: start,
            endDate: end,
            locale: {
                format: 'YYYY-MM-DD',
                separator: ' / ',
                applyLabel: 'Appliquer',
                cancelLabel: 'Annuler',
                fromLabel: 'Du',
                toLabel: 'Au',
                customRangeLabel: 'Personnalisé',
                daysOfWeek: ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'],
                monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
                firstDay: 1
            },
            ranges: {
               "Aujourd'hui": [moment(), moment()],
               'Hier': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
               '7 Derniers Jours': [moment().subtract(6, 'days'), moment()],
               '30 Derniers Jours': [moment().subtract(29, 'days'), moment()],
               'Ce Mois': [moment().startOf('month'), moment().endOf('month')],
               'Mois Dernier': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        }, cb);

        <?php if ($startDate && $endDate): ?>
            cb(start, end, '');
        <?php else: ?>
            $('#kt_product_daterangepicker_date').html('Toutes les dates');
        <?php endif; ?>

        $('#kt_product_daterangepicker').on('apply.daterangepicker', function (ev, picker) {
            var datestart = picker.startDate.format('YYYY-MM-DD');
            var dateend = picker.endDate.format('YYYY-MM-DD');
            $('#start_date').val(datestart);
            $('#end_date').val(dateend);
            $('#kt_product_daterangepicker_date').text(datestart + ' / ' + dateend);
            $('#filter_form').submit();
        });
    });
</script>
<?php $this->end(); ?>