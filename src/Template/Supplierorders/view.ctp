<?php 
    $this->assign('title', 'Afficher la commande :'.$supplierorder->code);
 ?>
    <div class="card card-custom card-sticky" id="kt_page_sticky_card">
        <div class="card-header">
        <div class="card-title">
            <h3 class="card-label">
                <?= $this->fetch('title') ?> <i class="mr-2"></i>
            </h3>
        </div>
        <div class="card-toolbar">
            <button onclick="goBack()" class="btn btn-light-primary font-weight-bolder mr-2">
                <i class="ki ki-long-arrow-back icon-xs"></i>Retour
            </button>
        </div>
    </div>
        <div class="card-body">
            <div class="row justify-content-center py-8 px-8 py-md-8 px-md-0">
                <div class="col-md-12">
                    <div class="d-flex justify-content-between flex-column flex-md-row">
                        <h1 class="display-4 font-weight-boldest mb-10">    <?= $supplierorder->supplier->name ?><br><?= $supplierorder->created->i18nFormat('dd/MM/yyyy')  ?>
                        </h1>
                        <div class="d-flex flex-column align-items-md-end px-0">
                            <span class=" d-flex flex-column align-items-md-end opacity-70">
                                <?= ($supplierorder->has('supplier') && $supplierorder->supplier && $supplierorder->supplier->has('adress') && $supplierorder->supplier->adress) ? h($supplierorder->supplier->adress->title) : '' ?>
                                <br>Téléphone: <?= ($supplierorder->has('supplier') && $supplierorder->supplier) ? h($supplierorder->supplier->phone) : '' ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center py-8 px-8 px-md-0">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th class="pl-0 font-weight-bold text-muted  text-uppercase">Article</th>
                                    <th class="text-right font-weight-bold text-muted text-uppercase">Qté Commandée</th>
                                    <th class="text-right font-weight-bold text-muted text-uppercase">Qté Reçue</th>
                                    <th class="text-right font-weight-bold text-muted text-uppercase text-warning">Qté Non Reçue</th>
                                    <th class="text-right font-weight-bold text-muted text-uppercase">P.U</th>
                                    <th class="text-right pr-0 font-weight-bold text-muted text-uppercase">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $total = 0;
                                    $totalremise = 0;
                                    $totalOrderedQty = 0;
                                    $totalReceivedQty = 0;
                                    $notReceivedQty = 0;
                                    $notReceivedTotal = 0;
                                ?>
                                <?php foreach ($supplierorder->supporderproducts as $key => $supporderproduct): ?>
                                    <?php
                                        $itemTitle = '-';
                                        if ($supporderproduct->has('product') && $supporderproduct->product) {
                                            $itemTitle = h($supporderproduct->product->title);
                                        } elseif ($supporderproduct->has('pack') && $supporderproduct->pack) {
                                            $itemTitle = h($supporderproduct->pack->title);
                                        }

                                        $pUnite = ($supporderproduct->has('productunite') && $supporderproduct->productunite) ? $supporderproduct->productunite : (($supporderproduct->has('pack') && $supporderproduct->pack && !empty($supporderproduct->pack->packunites[0])) ? $supporderproduct->pack->packunites[0] : null);

                                        $uniteQty = ($pUnite && isset($pUnite->quantity) && $pUnite->quantity > 0) ? (int)$pUnite->quantity : 1;
                                        $uniteAbrev = ($pUnite && $pUnite->has('unite') && $pUnite->unite) ? h($pUnite->unite->abrev) : 'unités';
                                        $parentAbrev = ($pUnite && $pUnite->has('unite') && $pUnite->unite && $pUnite->unite->has('parentunite') && $pUnite->unite->parentunite) ? h($pUnite->unite->parentunite->abrev) : '';

                                        $cartons = intVal($supporderproduct->quantity / $uniteQty);
                                        $pieces = $supporderproduct->quantity % $uniteQty;
                                        $hasReceipt = ($supporderproduct->has('receipt') && $supporderproduct->receipt) || !empty($supporderproduct->receipt_id);
                                        $sopStatut = $supporderproduct->statut;
                                        if (($sopStatut === null || $sopStatut === '') && $supplierorder) {
                                            $sopStatut = $supplierorder->statut;
                                        }

                                        $totalOrderedQty += $supporderproduct->quantity;
                                        if ($hasReceipt) {
                                            $totalReceivedQty += $supporderproduct->quantity;
                                        } else if ((int)$sopStatut !== 8) {
                                            $notReceivedQty += $supporderproduct->quantity;
                                            $notReceivedTotal += ($supporderproduct->price * $supporderproduct->quantity);
                                        }
                                    ?>
                                    <tr>
                                        <td><?= $itemTitle ?></td>
                                        <td class="text-right">
                                            <?php
                                                if ($uniteQty > 1 && $pieces > 0) {
                                                    if ($cartons > 0) {
                                                        echo $cartons . ' ' . $uniteAbrev . ' et ' . $pieces . ' ' . ($parentAbrev ?: 'pièces');
                                                    } else {
                                                        echo $pieces . ' ' . ($parentAbrev ?: 'pièces');
                                                    }
                                                } else {
                                                    echo ($cartons > 0 ? $cartons : $supporderproduct->quantity) . ' ' . $uniteAbrev;
                                                }
                                            ?>
                                        </td>
                                        <td class="text-right">
                                            <?php
                                                if ($hasReceipt) {
                                                    if ($uniteQty > 1 && $pieces > 0) {
                                                        if ($cartons > 0) {
                                                            echo $cartons . ' ' . $uniteAbrev . ' et ' . $pieces . ' ' . ($parentAbrev ?: 'pièces');
                                                        } else {
                                                            echo $pieces . ' ' . ($parentAbrev ?: 'pièces');
                                                        }
                                                    } else {
                                                        echo ($cartons > 0 ? $cartons : $supporderproduct->quantity) . ' ' . $uniteAbrev;
                                                    }
                                                } else {
                                                    echo '0 ' . $uniteAbrev;
                                                }
                                            ?>
                                        </td>
                                        <td class="text-right">
                                            <?php
                                                if (!$hasReceipt && (int)$sopStatut !== 8) {
                                                    if ($uniteQty > 1 && $pieces > 0) {
                                                        if ($cartons > 0) {
                                                            echo '<span class="label label-inline label-light-warning font-weight-bolder">' . $cartons . ' ' . $uniteAbrev . ' et ' . $pieces . ' ' . ($parentAbrev ?: 'pièces') . '</span>';
                                                        } else {
                                                            echo '<span class="label label-inline label-light-warning font-weight-bolder">' . $pieces . ' ' . ($parentAbrev ?: 'pièces') . '</span>';
                                                        }
                                                    } else {
                                                        echo '<span class="label label-inline label-light-warning font-weight-bolder">' . ($cartons > 0 ? $cartons : $supporderproduct->quantity) . ' ' . $uniteAbrev . '</span>';
                                                    }
                                                } elseif ((int)$sopStatut === 8) {
                                                    echo '<span class="label label-inline label-light-danger font-weight-bolder">Annulée</span>';
                                                } else {
                                                    echo '<span class="text-muted">0 ' . $uniteAbrev . '</span>';
                                                }
                                            ?>
                                        </td>
                                        <td class="text-right"><?= number_format($supporderproduct->price, 2, '.', '') ?></td>
                                        <td class="text-right"><?= number_format(($supporderproduct->price*$supporderproduct->quantity), 2, '.', '') ?></td>
                                    </tr>
                                    <?php $total+=($supporderproduct->price*$supporderproduct->quantity) ?>
                                <?php endforeach ?>
                            </tbody>
                            <tfoot>
                                <tr class="bg-light-warning font-weight-bolder">
                                    <td class="pl-0 text-warning text-uppercase font-weight-boldest" colspan="3">
                                        <?= __('Total Non Réceptionné (En attente)') ?>
                                    </td>
                                    <td class="text-right" colspan="3">
                                        <span class="label label-inline label-light-warning font-weight-boldest font-size-h6 px-4 py-2">
                                            <?= $notReceivedQty ?> unités (<?= number_format($notReceivedTotal, 2, '.', '') ?> DH)
                                        </span>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            </div>
                    </div>
                    <?= $this->Form->end() ?>
                </div>
            </div>
            
            <div class="row justify-content-center bg-gray-100 py-8 px-8 py-md-10 px-md-0 mx-0">
                <div class="col-md-10">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th class="font-weight-bold text-muted text-uppercase">TOTAL REMISE</th>
                                    <th class="font-weight-bold text-muted text-uppercase">STATUT DU PAIEMENT</th>
                                    <th class="font-weight-bold text-muted text-uppercase text-warning">NON RÉCEPTIONNÉ</th>
                                    <th class="font-weight-bold text-muted text-uppercase">TOTAL</th>
                                    <th class="font-weight-bold text-muted text-uppercase text-right">MONTANT TTC</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="font-weight-bolder">
                                    <td><?= number_format(($totalremise), 2, '.', '') ?> DH</td>
                                    <td>Success</td>
                                    <td>
                                        <span class="label label-inline label-light-warning font-weight-bolder">
                                            <?= $notReceivedQty ?> (<?= number_format($notReceivedTotal, 2, '.', '') ?> DH)
                                        </span>
                                    </td>
                                    <td><?= number_format(($total), 2, '.', '') ?> DH</td>
                                    <td class="text-primary font-size-h3 font-weight-boldest text-right"><?= number_format(($total-$totalremise), 2, '.', '') ?> DH</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?= $this->Html->script('/assets/js/pages/crud/forms/widgets/select2.js', ['block' => 'script_bottom']) ?>
<?= $this->Html->scriptStart(['block'=>'script_bottom']) ?>
  $('.select2').select2();
  $.fn.select2.defaults.set("width", "100%");
  

  $('#supplier-id').select2({
    placeholder: 'code ou nom du fournisseur ',
    ajax: {
      url : "<?php echo $this->Url->build( [ 'controller' => 'Supplierorders', 'action' => 'suppliers'] ); ?>",
      dataType: 'json',
      delay: 500,
      processResults: function (data) {
        return {
          results: data
        };
      },
      cache: true
    }
  });
    
  $('.groups').select2({
    placeholder: 'Selectionnez une catégorie'
  });

  $('.produit').select2({
    placeholder: 'Selectionnez un article',
  });

    
<?= $this->Html->scriptEnd(); ?>
