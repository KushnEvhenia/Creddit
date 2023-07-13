<ul class="list-group" style='width: 100%;'>
    <li class="list-group-item d-flex justify-content-between align-items-center">
        <a href=<?= '/account/view?id=' . $model->id ?>><?= $model->name ?></a>
        <span class="badge badge-primary badge-pill">
            <?= $model->current_amount ?>
        </span>
    </li>
</ul>