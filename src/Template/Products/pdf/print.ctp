<html>

<head>
	<style>
		@page {
			margin: 0.5cm 1cm 0.5cm 1cm;
			odd-footer-name: html_myFooter1;
		}

		h1 {
			font-family: sans;
			font-weight: bold;
			font-size: 26px;
			line-height: 10px;
			text-align: center;
		}

		h2 {
			font-family: sans;
			font-weight: bold;
			font-size: 18px;
			line-height: 10px;
		}

		h3 {
			font-family: sans;
			font-weight: bold;
			margin-top: 1.5em;
			margin-bottom: 0.5em;
			font-size: 14px;
			color: #0066CC;
			border-bottom: 2px solid #0066CC;
			padding-bottom: 3px;
		}

		.table {
			border-spacing: 0;
			width: 100%;
			border: 1px solid #CCCCCC;
			border-radius: 6px;
			box-shadow: 0 1px 1px #CCCCCC;
			margin-bottom: 20px;
		}

		.table th {
			background-color: #DCE9F9;
			font-weight: bold;
			border-left: 1px solid #CCCCCC;
			border-top: 1px solid #CCCCCC;
			padding: 6px 8px;
			font-size: 11px;
			font-family: sans;
			text-align: left;
		}

		.table td {
			border-left: 1px solid #CCCCCC;
			border-top: 1px solid #CCCCCC;
			padding: 6px 8px;
			font-size: 11px;
			font-family: sans;
			text-align: left;
		}

		.info-box {
			border: 1px solid #CCCCCC;
			padding: 10px;
			margin-bottom: 15px;
			background-color: #F9F9F9;
			width: 45%;
		}

		.info-row {
			margin-bottom: 6px;
			font-family: sans;
			font-size: 12px;
		}

		.info-label {
			font-weight: bold;
			display: inline-block;
			width: 120px;
		}

		.product-title-box {
			background-color: #E8F4F8;
			padding: 12px;
			text-align: center;
			border: 2px solid #0066CC;
			margin-bottom: 15px;
			border-radius: 5px;
		}
	</style>
</head>

<body>
	<div>
		<!-- Header -->
		<table width="100%" style="margin-bottom:15px;">
			<tr>
				<td width="50%">
					<img src="file:///<?= str_replace('\\', '/', WWW_ROOT) ?>logo.jpg" height="60px" />
				</td>
				<td width="50%" style="text-align: right;">
					<span style="font-size: 16px; font-weight: bold; color: #0066CC;">RAPPORT PRODUIT</span><br />
					<span style="font-size: 11px; font-family: sans;">Généré le: <?= date("d/m/Y H:i") ?></span><br />
					<span style="font-size: 11px; font-family: sans; font-weight: bold;">
						Période:
						<?= ($startDate && $endDate) ? h($startDate) . ' au ' . h($endDate) : 'Toutes les dates' ?>
					</span>
				</td>
			</tr>
		</table>

		<!-- Product Title Box -->
		<div class="product-title-box">
			<h2 style="margin: 0; color: #0066CC;"><?= h($product->title) ?></h2>
		</div>

		<!-- General Info & Stock -->
		<div style="width: 100%;">
			<div class="info-box" style="float: left;">
				<h4
					style="margin-top: 0; margin-bottom: 8px; border-bottom: 1px solid #CCC; padding-bottom: 3px; font-family: sans;">
					Informations Générales</h4>
				<div class="info-row"><span class="info-label">Catégorie:</span>
					<?= $product->has('category') ? h($product->category->title) : '-' ?></div>
				<div class="info-row"><span class="info-label">Fournisseur:</span>
					<?= $product->has('supplier') ? h($product->supplier->name) : '-' ?></div>
				<div class="info-row"><span class="info-label">Prix d'achat:</span>
					<?= number_format($product->buyingprice, 2, ',', ' ') ?> DH</div>
			</div>

			<div class="info-box" style="float: right;">
				<h4
					style="margin-top: 0; margin-bottom: 8px; border-bottom: 1px solid #CCC; padding-bottom: 3px; font-family: sans;">
					Statut de Stock</h4>
				<?php
				$realStock = 0;
				if (!empty($product->whproducts)) {
					foreach ($product->whproducts as $whp) {
						if ($whp->has('warehouse') && $whp->warehouse && $whp->warehouse->whnature_id == 1) {
							$realStock += $whp->quantity;
						}
					}
				}
				$totalValue = $realStock * $product->buyingprice;
				?>
				<div class="info-row"><span class="info-label">Stock Réel:</span> <strong><?= $realStock ?></strong>
					unités</div>
				<div class="info-row"><span class="info-label">Valeur de Stock:</span>
					<strong><?= number_format($totalValue, 2, ',', ' ') ?> DH</strong>
				</div>
				<div class="info-row"><span class="info-label">Statut:</span>
					<?= $product->statut ? 'Actif' : 'Inactif' ?></div>
			</div>
		</div>

		<div style="clear: both;"></div>

		<!-- Supplier Orders Table -->
		<h3>Commandes Fournisseurs & Réceptions</h3>
		<table class="table">
			<thead>
				<tr>
					<th>N° Commande</th>
					<th>N° Réception</th>
					<th>Date</th>
					<th>Quantité</th>
					<th>Prix Unitaire</th>
					<th>Prix Total</th>
				</tr>
			</thead>
			<tbody>
				<?php if (!empty($product->supporderproducts)): ?>
					<?php foreach ($product->supporderproducts as $sop): ?>
						<tr>
							<td style="font-weight: bold;">
								<?= $sop->has('supplierorder') ? h($sop->supplierorder->code) : '-' ?>
							</td>
							<td><?= $sop->has('receipt') ? h($sop->receipt->code) : '-' ?></td>
							<td><?= h($sop->created) ?></td>
							<td>
								<?= h($sop->quantity) ?>
							</td>
							<td><?= number_format($sop->price, 2, ',', ' ') ?> DH</td>
							<td><?= number_format($sop->quantity * $sop->price, 2, ',', ' ') ?> DH</td>
						</tr>
					<?php endforeach; ?>
				<?php else: ?>
					<tr>
						<td colspan="5" style="text-align: center; color: #777; font-style: italic;">Aucune commande ou
							réception enregistrée pour cette période.</td>
					</tr>
				<?php endif; ?>
			</tbody>
		</table>

		<!-- Slips Table -->
		<h3>Bons de Conditionnement</h3>
		<table class="table">
			<thead>
				<tr>
					<th>N° Bon</th>
					<th>Date</th>
					<th>Quantité</th>
					<th>Prix Unitaire</th>
					<th>Prix Total</th>
				</tr>
			</thead>
			<tbody>
				<?php if (!empty($product->slipproducts)): ?>
					<?php foreach ($product->slipproducts as $sp): ?>
						<tr>
							<td style="font-weight: bold;"><?= $sp->has('slip') ? h($sp->slip->code) : '-' ?></td>
							<td><?= h($sp->created) ?></td>
							<td><?= h($sp->quantity) ?></td>
							<td><?= number_format($sp->price, 2, ',', ' ') ?> DH</td>
							<td><?= number_format($sp->quantity * $sp->price, 2, ',', ' ') ?> DH</td>
						</tr>
					<?php endforeach; ?>
				<?php else: ?>
					<tr>
						<td colspan="3" style="text-align: center; color: #777; font-style: italic;">Aucun bon de
							conditionnement enregistré pour cette période.</td>
					</tr>
				<?php endif; ?>
			</tbody>
		</table>

		<?php
		$totalReceiptsQty = 0;
		$totalReceiptsValue = 0;
		if (!empty($product->supporderproducts)) {
			foreach ($product->supporderproducts as $sop) {
				if ($sop->receipt_id !== null) {
					$totalReceiptsQty += $sop->quantity;
					$totalReceiptsValue += $sop->quantity * $sop->price;
				}
			}
		}

		$totalSlipsQty = 0;
		$totalSlipsValue = 0;
		if (!empty($product->slipproducts)) {
			foreach ($product->slipproducts as $sp) {
				$totalSlipsQty += $sp->quantity;
				$totalSlipsValue += $sp->quantity * ($sp->price > 0 ? $sp->price : $product->buyingprice);
			}
		}

		$netDifference = $totalReceiptsQty - $totalSlipsQty;
		$netValueDifference = $totalReceiptsValue - $totalSlipsValue;
		?>

		<!-- Summary Calculation Table -->
		<div style="margin-top: 20px; page-break-inside: avoid;">
			<table style="width: 100%; border-collapse: collapse; font-family: sans; font-size: 11px;">
				<tr>
					<td style="width: 35%;"></td>
					<td style="width: 65%;">
						<table
							style="width: 100%; border: 1.5px solid #0066CC; border-radius: 4px; background-color: #F4F9FD; padding: 8px;">
							<thead>
								<tr>
									<th
										style="text-align: left; padding: 4px; color: #0066CC; font-size: 11px; font-family: sans; border-bottom: 1px solid #0066CC;">
										Indicateur</th>
									<th
										style="text-align: right; padding: 4px; color: #0066CC; font-size: 11px; font-family: sans; border-bottom: 1px solid #0066CC; width: 30%;">
										Quantité</th>
									<th
										style="text-align: right; padding: 4px; color: #0066CC; font-size: 11px; font-family: sans; border-bottom: 1px solid #0066CC; width: 40%;">
										Valeur (DH)</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td style="font-weight: bold; padding: 4px 4px 4px 0; color: #333;">Réceptions :
									</td>
									<td style="text-align: right; font-weight: bold; padding: 4px; color: #28A745;">
										<?= $totalReceiptsQty ?>
									</td>
									<td style="text-align: right; font-weight: bold; padding: 4px; color: #28A745;">
										<?= number_format($totalReceiptsValue, 2, ',', ' ') ?> DH
									</td>
								</tr>
								<tr>
									<td style="font-weight: bold; padding: 4px 4px 4px 0; color: #333;">Bons :</td>
									<td style="text-align: right; font-weight: bold; padding: 4px; color: #DC3545;">
										<?= $totalSlipsQty ?>
									</td>
									<td style="text-align: right; font-weight: bold; padding: 4px; color: #DC3545;">
										<?= number_format($totalSlipsValue, 2, ',', ' ') ?> DH
									</td>
								</tr>
								<tr style="border-top: 1px solid #0066CC;">
									<td
										style="font-weight: bold; padding: 6px 4px 4px 0; color: #0066CC; font-size: 11px;">
										Différence :</td>
									<td
										style="text-align: right; font-weight: bold; padding: 6px 4px 4px 4px; color: #0066CC; font-size: 11px;">
										<?= $netDifference ?>
									</td>
									<td
										style="text-align: right; font-weight: bold; padding: 6px 4px 4px 4px; color: #0066CC; font-size: 11px;">
										<?= number_format($netValueDifference, 2, ',', ' ') ?> DH
									</td>
								</tr>
							</tbody>
						</table>
					</td>
				</tr>
			</table>
		</div>
	</div>

	<!-- Footer -->
	<htmlpagefooter name="myFooter1">
		<table width="100%">
			<tr>
				<td width="50%" style="font-style: italic; font-size: 9px; color: #777;">
					Rapport produit - Réf: <?= h($product->reference) ?>
				</td>
				<td width="50%" style="text-align: right; font-style: italic; font-size: 9px; color: #777;">
					Page {PAGENO}/{nbpg}
				</td>
			</tr>
		</table>
	</htmlpagefooter>
</body>

</html>