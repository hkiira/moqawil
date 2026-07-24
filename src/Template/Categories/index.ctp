<?php
$isSub = ($id == 2);

if (isset($category)) {
    $this->assign('title', 'Catégories rattachées à la famille : ' . h($category->title));
    $this->assign('categoryid', $category->id);
} else {
    $this->assign('title', $isSub ? 'Gestion des Sous-Familles & Catégories' : 'Gestion des Familles Principales');
}

$newButtonUrl = $this->Url->build(['action' => 'add', $id, $type]);
$newButtonText = $isSub ? 'Nouvelle Catégorie' : 'Nouvelle Famille';

$actionBtn = '<a href="' . $newButtonUrl . '" class="btn btn-light-primary font-weight-bolder btn-sm">
    <i class="fas fa-plus mr-2"></i> ' . $newButtonText . '
</a>';
$this->assign('actionsubh', $actionBtn);
$this->assign('js', 'categories');

$td = '<th style="width: 30px;"></th>
       <th>Nom</th>
       <th>Catégorie Parente</th>
       <th>Statut</th>
       <th>Actions</th>';
$this->assign('td', $td);

$this->extend('/Common/index');
?>

<?php $url = (isset($category)) ? $this->Url->build(['action' => 'search', $id, $type, $category->id]) : $this->Url->build(['action' => 'search', $id, $type]); ?>
<?= $this->Html->scriptStart(['block' => 'script_bottom']) ?>
var HOST_URL = "<?php echo $url; ?>";
<?= $this->Html->scriptEnd(); ?>