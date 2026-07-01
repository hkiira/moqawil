<?php   
$this->extend('/Common/crud');
?>
<?php $this->loadHelper('Form', [
    'templates' => 'app_form',
]); 
$this->assign('objet','');
$this->assign('title', 'Rapport de stock');
$this->assign('subtitle', 'Calcul des mouvements de stock entre deux dates');
$this->assign('goback', $this->Html->link('Retour', ['action' => 'index'], ['class' => 'btn btn-light-primary font-weight-bolder mr-2']));
$this->assign('edit', '<button type="button" id="print-report" class="btn btn-success font-weight-bolder"><i class="ki ki-printer icon-xs"></i>Imprimer</button>');
?>
<div class="card-body">
    <!-- Date Filter Form -->
    <div class="card card-custom mb-5">
        <div class="card-body">
            <?= $this->Form->create(null, ['type' => 'get']) ?>
            <div class="row w-100">
                <div class="col-md-6">
                    <label>Période</label>
                    <div class="input-group" id="kt_daterangepicker_stock">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="la la-calendar-check-o"></i>
                            </span>
                        </div>
                        <input type="text" class="form-control" readonly id="daterange_display" placeholder="Sélectionner une période"/>
                        <input type="hidden" name="start_date" id="date_start" value="<?= h($startDate) ?>"/>
                        <input type="hidden" name="end_date" id="date_end" value="<?= h($endDate) ?>"/>
                    </div>
                </div>
                <div class="col-md-4">
                    <?= $this->Form->control('user_id', [
                        'label' => 'Utilisateur',
                        'type' => 'select',
                        'options' => $users,
                        'empty' => 'Tous les utilisateurs',
                        'value' => $userId,
                        'class' => 'form-control select2',
                    ]) ?>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <?= $this->Form->button('Filtrer', ['class' => 'btn btn-primary']) ?>
                </div>
            </div>
            <?= $this->Form->end() ?>
        </div>
    </div>

    <!-- Quantity Formatting Helper -->
    <?php
    $formatQty = function($qty, $saletypeId, $displayData) {
        if ($saletypeId == 1 || $saletypeId == 2 || $saletypeId == 3) {
            if ($qty % $displayData['qtcarsac']) {
                if (intVal($qty / $displayData['qtcarsac']) > 0) {
                    return intVal($qty / $displayData['qtcarsac']) . ' ' . h($displayData['cartsac']) . ' et ' . ($qty % $displayData['qtcarsac']) . ' ' . h($displayData['kgunite']);
                } else {
                    return ($qty % $displayData['qtcarsac']) . ' ' . h($displayData['kgunite']);
                }
            } else {
                return intVal($qty / $displayData['qtcarsac']) . ' ' . h($displayData['cartsac']);
            }
        } else {
            return number_format($qty, 2) . ' ' . h($displayData['measurement_title'] ?? '');
        }
    };
    ?>

    <!-- Summary Cards -->
    <div class="row mb-5">
        <div class="col-lg-3">
            <div class="card card-custom bg-light-success">
                <div class="card-body">
                    <span class="svg-icon svg-icon-3x svg-icon-success">
                        <i class="flaticon2-box icon-3x text-success"></i>
                    </span>
                    <div class="text-dark font-weight-bolder font-size-h2 mt-3">
                        <?php 
                        $totalSlips = 0;
                        foreach ($productData as $data) {
                            $totalSlips += $data['charged_slips'];
                        }
                        echo number_format($totalSlips, 2);
                        ?>
                    </div>
                    <div class="font-weight-bold text-dark font-size-sm mt-1"><?= number_format($slipOrderAmount, 2) ?> DH</div>
                    <div class="font-weight-bold text-muted font-size-sm">Total chargé (Bons)</div>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card card-custom bg-light-info">
                <div class="card-body">
                    <span class="svg-icon svg-icon-3x svg-icon-info">
                        <i class="flaticon2-shopping-cart-1 icon-3x text-info"></i>
                    </span>
                    <div class="text-dark font-weight-bolder font-size-h2 mt-3">
                        <?php 
                        $totalPurchases = 0;
                        foreach ($productData as $data) {
                            $totalPurchases += $data['charged_purchases'];
                        }
                        echo number_format($totalPurchases, 2);
                        ?>
                    </div>
                    <div class="font-weight-bold text-dark font-size-sm mt-1"><?= number_format($purchaseOrderAmount, 2) ?> DH</div>
                    <div class="font-weight-bold text-muted font-size-sm">Total chargé (Retours)</div>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card card-custom bg-light-danger">
                <div class="card-body">
                    <span class="svg-icon svg-icon-3x svg-icon-danger">
                        <i class="flaticon2-graph icon-3x text-danger"></i>
                    </span>
                    <div class="text-dark font-weight-bolder font-size-h2 mt-3">
                        <?php 
                        $totalSold = 0;
                        foreach ($productData as $data) {
                            $totalSold += $data['sold'];
                        }
                        echo number_format($totalSold, 2);
                        ?>
                    </div>
                    <div class="font-weight-bold text-dark font-size-sm mt-1"><?= number_format($salesOrderAmount, 2) ?> DH</div>
                    <div class="font-weight-bold text-muted font-size-sm">Total vendu</div>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card card-custom bg-light-primary">
                <div class="card-body">
                    <span class="svg-icon svg-icon-3x svg-icon-primary">
                        <i class="flaticon2-layers-1 icon-3x text-primary"></i>
                    </span>
                    <div class="text-dark font-weight-bolder font-size-h2 mt-3">
                        <?php 
                        $totalRemaining = 0;
                        foreach ($productData as $data) {
                            $totalRemaining += $data['remaining_stock'];
                        }
                        echo number_format($totalRemaining, 2);
                        ?>
                    </div>
                    <div class="font-weight-bold text-dark font-size-sm mt-1"><?= number_format($remainingStockAmount, 2) ?> DH</div>
                    <div class="font-weight-bold text-muted font-size-sm">Stock restant</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Table -->
    <div class="card card-custom">
        <div class="card-header">
            <div class="card-title">
                <h3 class="card-label">Détails par produit (<?= date('d/m/Y H:i', strtotime($startDate)) ?> - <?= date('d/m/Y H:i', strtotime($endDate)) ?>)</h3>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-head-custom table-vertical-center table-bordered">
                    <thead>
                        <tr class="bg-light">
                            <th>Produit</th>
                            <th class="text-right">Chargé (Bons)</th>
                            <th class="text-right">Chargé (Retours)</th>
                            <th class="text-right">Total Chargé</th>
                            <th class="text-right">Vendu(Commandes)</th>
                            <th class="text-right">Total Livré</th>
                            <th class="text-right">Stock Restant</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($productData)): ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted">
                                    Aucune donnée disponible pour cette période
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($productData as $packId => $data): ?>
                                <?php 
                                $pack = $data['pack'];
                                $saletypeId = isset($pack->saletype) ? $pack->saletype->id : null;
                                
                                // Prepare display values based on saletype
                                $displayData = [];
                                if ($saletypeId == 1 || $saletypeId == 2 || $saletypeId == 3) {
                                    $cartsac = isset($pack->packunites[0]) ? $pack->packunites[0]->unite->title : '';
                                    $kgunite = isset($pack->packunites[0]) ? $pack->packunites[0]->unite->parentunite->abrev : '';
                                    $qtcarsac = isset($pack->packunites[0]) ? $pack->packunites[0]->quantity : 1;
                                    
                                    $displayData = [
                                        'cartsac' => $cartsac,
                                        'kgunite' => $kgunite,
                                        'qtcarsac' => $qtcarsac,
                                    ];
                                } else {
                                    if ($data['measurement_base_unit']) {
                                        $measurementTitle = $data['measurement_base_unit']->abbreviation;
                                    } else {
                                        $measurementTitle = isset($pack->measurement_unit) ? $pack->measurement_unit->abbreviation : '';
                                    }
                                    
                                    $displayData = [
                                        'measurement_title' => $measurementTitle,
                                    ];
                                }
                                ?>
                                <tr>
                                    <td>
                                        <span class="font-weight-bolder"><?= h($pack->title ?? 'N/A') ?></span>
                                        <?php if (!empty($pack->code)): ?>
                                            <br><small class="text-muted"><?= h($pack->code) ?></small>
                                        <?php endif; ?>
                                        <?php if ($saletypeId == 1 || $saletypeId == 2 || $saletypeId == 3): ?>
                                            <br><small class="text-muted">
                                                (<?= h($displayData['cartsac']) ?> = <?= number_format($displayData['qtcarsac'], 0) ?> <?= h($displayData['kgunite']) ?>)
                                            </small>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-right">
                                        <span class="label label-inline label-light-success font-weight-bold">
                                            <?= $formatQty($data['charged_slips'], $saletypeId, $displayData) ?>
                                        </span>
                                    </td>
                                    <td class="text-right">
                                        <span class="label label-inline label-light-info font-weight-bold">
                                            <?= $formatQty($data['charged_purchases'], $saletypeId, $displayData) ?>
                                        </span>
                                    </td>
                                    <td class="text-right font-weight-bolder">
                                        <?= $formatQty($data['total_charged'], $saletypeId, $displayData) ?>
                                    </td>
                                    <td class="text-right">
                                        <span class="label label-inline label-light-danger font-weight-bold">
                                            <?= $formatQty($data['sold'], $saletypeId, $displayData) ?>
                                        </span>
                                    </td>
                                    <td class="text-right font-weight-bolder">
                                        <?= number_format($data['sold_amount'], 2) ?> DH
                                    </td>
                                    <td class="text-right font-weight-bolder">
                                        <?php if ($data['remaining_stock'] < 0): ?>
                                            <span class="text-danger"><?= $formatQty($data['remaining_stock'], $saletypeId, $displayData) ?></span>
                                        <?php else: ?>
                                            <span class="text-primary"><?= $formatQty($data['remaining_stock'], $saletypeId, $displayData) ?></span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <!-- Total Row -->
                            <tr class="bg-light font-weight-bolder">
                                <td>TOTAL</td>
                                <td class="text-right"><?= number_format($totalSlips, 2) ?></td>
                                <td class="text-right"><?= number_format($totalPurchases, 2) ?></td>
                                <td class="text-right"><?= number_format($totalSlips + $totalPurchases, 2) ?></td>
                                <td class="text-right"><?= number_format($totalSold, 2) ?></td>
                                <td class="text-right"><?= number_format($salesOrderAmount, 2) ?> DH</td>
                                <td class="text-right"><?= number_format($totalRemaining, 2) ?></td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Detailed Transaction History -->
    <?php if (!empty($productData)): ?>
        <div class="card card-custom mt-5">
            <div class="card-header">
                <div class="card-title">
                    <h3 class="card-label">Historique Détaillé des Mouvements</h3>
                </div>
            </div>
            <div class="card-body">
                <?php 
                $productCount = 0;
                foreach ($productData as $packId => $data): 
                    $productCount++;
                    $pack = $data['pack'];
                    $saletypeId = isset($pack->saletype) ? $pack->saletype->id : null;
                    
                    // Prepare display values based on saletype
                    $displayData = [];
                    if ($saletypeId == 1 || $saletypeId == 2 || $saletypeId == 3) {
                        $cartsac = isset($pack->packunites[0]) ? $pack->packunites[0]->unite->title : '';
                        $kgunite = isset($pack->packunites[0]) ? $pack->packunites[0]->unite->parentunite->abrev : '';
                        $qtcarsac = isset($pack->packunites[0]) ? $pack->packunites[0]->quantity : 1;
                        
                        $displayData = [
                            'cartsac' => $cartsac,
                            'kgunite' => $kgunite,
                            'qtcarsac' => $qtcarsac,
                        ];
                    } else {
                        if ($data['measurement_base_unit']) {
                            $measurementTitle = $data['measurement_base_unit']->abbreviation;
                        } else {
                            $measurementTitle = isset($pack->measurement_unit) ? $pack->measurement_unit->abbreviation : '';
                        }
                        
                        $displayData = [
                            'measurement_title' => $measurementTitle,
                        ];
                    }
                    
                    // Collect transactions
                    $transactions = [];
                    foreach ($slips as $slip) {
                        foreach ($slip->slipproducts as $slipproduct) {
                            if ($slipproduct->item_id == $packId) {
                                $transactions[] = [
                                    'type' => 'slip',
                                    'date' => $slip->created,
                                    'code' => $slip->code,
                                    'quantity' => $slipproduct->quantity,
                                    'price' => $slipproduct->price,
                                    'info' => isset($slip->warehouse) ? $slip->warehouse->title : 'N/A'
                                ];
                            }
                        }
                    }
                    
                    foreach ($purchaseOrders as $order) {
                        foreach ($order->orderpacks as $orderpack) {
                            if ($orderpack->pack_id == $packId) {
                                $transactions[] = [
                                    'type' => 'purchase',
                                    'date' => $order->created,
                                    'code' => $order->code,
                                    'quantity' => $orderpack->quantity,
                                    'price' => $orderpack->price,
                                    'info' => isset($order->supplier) ? $order->supplier->name : 'N/A'
                                ];
                            }
                        }
                    }
                    
                    foreach ($salesOrders as $order) {
                        foreach ($order->orderpacks as $orderpack) {
                            if ($orderpack->pack_id == $packId) {
                                $transactions[] = [
                                    'type' => 'sale',
                                    'date' => $order->created,
                                    'code' => $order->code,
                                    'quantity' => $orderpack->quantity,
                                    'price' => $orderpack->price,
                                    'info' => (isset($order->customer) ? $order->customer->name : 'N/A') . ' - ' . (isset($order->user) ? $order->user->firstname : 'N/A')
                                ];
                            }
                        }
                    }
                    
                    if (empty($transactions)) continue;
                ?>
                    <div class="mb-8">
                        <h4 class="bg-light-primary p-3 rounded text-primary font-weight-bold font-size-h6 mb-3">
                            <?= h($pack->title) ?> <?= !empty($pack->code) ? '(' . h($pack->code) . ')' : '' ?>
                        </h4>
                        
                        <div class="table-responsive">
                            <table class="table table-bordered table-vertical-center">
                                <thead>
                                    <tr class="bg-light">
                                        <th style="width: 15%;">Date</th>
                                        <th style="width: 15%;">Type</th>
                                        <th style="width: 20%;">Code</th>
                                        <th class="text-right" style="width: 15%;">Qté</th>
                                        <th class="text-right" style="width: 15%;">Prix</th>
                                        <th class="text-right" style="width: 20%;">Montant</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($transactions as $trans): ?>
                                        <tr>
                                            <td><?= date('d/m/y H:i', strtotime($trans['date'])) ?></td>
                                            <td>
                                                <?php if ($trans['type'] == 'slip'): ?>
                                                    <span class="label label-inline label-light-primary font-weight-bold">Bon</span>
                                                <?php elseif ($trans['type'] == 'purchase'): ?>
                                                    <span class="label label-inline label-light-success font-weight-bold">Retour</span>
                                                <?php else: ?>
                                                    <span class="label label-inline label-light-danger font-weight-bold">Vente</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <span class="font-weight-bold"><?= h($trans['code']) ?></span>
                                                <br><small class="text-muted"><?= h($trans['info']) ?></small>
                                            </td>
                                            <td class="text-right">
                                                <?= $formatQty($trans['quantity'], $saletypeId, $displayData) ?>
                                            </td>
                                            <td class="text-right"><?= number_format($trans['price'], 2) ?> DH</td>
                                            <td class="text-right font-weight-bolder"><?= number_format($trans['quantity'] * $trans['price'], 2) ?> DH</td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endforeach; ?>
                
                <?php if (count($productData) > 20): ?>
                    <p class="text-center text-muted font-italic mt-5">
                        Note: Seuls les 20 premiers produits sont affichés dans l'historique détaillé pour des raisons de performance.
                    </p>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>


    <!-- Print Styles -->
    <style type="text/css" media="print">
        @page {
            size: landscape;
            margin: 1cm;
        }
        .card-toolbar,
        .btn,
        form {
            display: none !important;
        }
        body {
            background: white;
        }
        .card {
            box-shadow: none;
            border: none;
        }
        .table {
            font-size: 10pt;
        }
        .summary-cards {
            page-break-after: avoid;
        }
    </style>
</div>
<?= $this->Html->script('/assets/js/pages/crud/forms/widgets/select2.js', ['block' => 'script_bottom']) ?>
<?= $this->Html->scriptStart(['block'=>'script_bottom']) ?>
$(document).ready(function() {
    $.fn.select2.defaults.set("width", "100%");
    $('.select2').select2({
        placeholder: 'Sélectionner un utilisateur',
    });

    // Initialize daterangepicker
    var start = $('#date_start').val() ? moment($('#date_start').val()) : moment().startOf('month');
    var end = $('#date_end').val() ? moment($('#date_end').val()) : moment().endOf('month');
    
    function cb(start, end) {
        $('#daterange_display').val(start.format('DD/MM/YYYY HH:mm') + ' - ' + end.format('DD/MM/YYYY HH:mm'));
        $('#date_start').val(start.format('YYYY-MM-DD HH:mm:ss'));
        $('#date_end').val(end.format('YYYY-MM-DD HH:mm:ss'));
    }
    
    $('#kt_daterangepicker_stock').daterangepicker({
        buttonClasses: 'btn',
        applyClass: 'btn-primary',
        cancelClass: 'btn-secondary',
        startDate: start,
        endDate: end,
        timePicker: true,
        timePicker24Hour: true,
        locale: {
            format: 'DD/MM/YYYY HH:mm',
            applyLabel: 'Appliquer',
            cancelLabel: 'Annuler',
            fromLabel: 'De',
            toLabel: 'À',
            customRangeLabel: 'Personnalisé',
            daysOfWeek: ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'],
            monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
            firstDay: 1
        },
        ranges: {
            "Aujourd'hui": [moment().startOf('day'), moment().endOf('day')],
            'Hier': [moment().subtract(1, 'days').startOf('day'), moment().subtract(1, 'days').endOf('day')],
            '7 derniers jours': [moment().subtract(6, 'days').startOf('day'), moment().endOf('day')],
            '30 derniers jours': [moment().subtract(29, 'days').startOf('day'), moment().endOf('day')],
            'Ce mois': [moment().startOf('month').startOf('day'), moment().endOf('month').endOf('day')],
            'Mois dernier': [moment().subtract(1, 'month').startOf('month').startOf('day'), moment().subtract(1, 'month').endOf('month').endOf('day')]
        }
    }, cb);
    
    cb(start, end);
    
    $('#kt_daterangepicker_stock').on('cancel.daterangepicker', function(ev, picker) {
        $('#daterange_display').val('');
        $('#date_start').val('');
        $('#date_end').val('');
    });

    // Print button handler
    $('#print-report').on('click', function() {
        var startDate = $('#date_start').val();
        var endDate = $('#date_end').val();
        var userId = $('#user-id').val();
        
        var printUrl = '<?= $this->Url->build(['action' => 'stockreportprint', '_ext' => 'pdf']) ?>';
        printUrl += '?start_date=' + encodeURIComponent(startDate);
        printUrl += '&end_date=' + encodeURIComponent(endDate);
        if (userId) {
            printUrl += '&user_id=' + encodeURIComponent(userId);
        }
        
        window.open(printUrl, '_blank');
    });
});
<?= $this->Html->scriptEnd(); ?>
