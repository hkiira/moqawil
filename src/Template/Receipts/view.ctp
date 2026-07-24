<?php 
    $this->assign('title', 'Bon N° :'.$receipt->code);
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
        <div class="card-body p-0">
            <div class="row justify-content-center py-8 px-8 py-md-8 px-md-0">
                <div class="col-md-8">
                    <div class="d-flex justify-content-between flex-column flex-md-row px-15">
                        <h5 class="display-5 font-weight-boldest mb-10">    <?= $receipt->supplier->name ?>
                        </h5>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="d-flex justify-content-between flex-column flex-md-row px-15">
                        <h5 class="display-5 font-weight-boldest mb-10"> <?= $receipt->created->i18nFormat('dd/MM/yyyy')  ?>
                        </h5>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center py-8 px-8 px-md-0">
                <div class="col-md-10">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th class="pl-0 font-weight-bold text-muted  text-uppercase">Article</th>
                                    <th class="text-left font-weight-bold text-muted text-uppercase">Quantité</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($receipt->supporderproducts as $key => $supporderproduct): ?>
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
                                    ?>
                                    <tr>
                                        <td class="text-left"><?= $itemTitle ?></td>
                                        <td class="text-left">
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
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
