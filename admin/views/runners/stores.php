<table class="table">
    <tr class="row">
        <th class="thead-light">ID</th>
        <th class="thead-light">Магазин</th>
        <th class="thead-light">Адрес</th>
    </tr>
    <?php foreach ($stores as $store): ?>
    <tr class="row">
        <td class="col col-3">
            <?= $store->id ?>
        </td>
        <td class="col col-3">
            <?= $store->title ?>
        </td>
        <td class="col col-3">
            <?= $store->address ?>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
