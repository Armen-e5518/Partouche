<?php
$items = [

    ['label' => 'Clients', 'icon' => 'user-circle', 'url' => ['/clients']],
    ['label' => 'Groups', 'icon' => 'object-group', 'url' => ['/groups']],
    ['label' => 'Attachments', 'icon' => 'list', 'url' => ['/all-attachments']],

    ['label' => 'Notifications', 'icon' => 'envelope-o', 'url' => ['/notifications']],
    ['label' => 'User notifications', 'icon' => 'envelope-square', 'url' => ['/user-notifications']],
    ['label' => 'Contacts', 'icon' => 'volume-control-phone', 'url' => ['/contacts']],
//    ['label' => 'Group notifications', 'icon' => 'list', 'url' => ['/group-notifications']],
];
if (!Yii::$app->user->isGuest && Yii::$app->user->identity->id == \backend\models\search\UserSearch::ADMIN_ID) {
    array_unshift($items,
        ['label' => 'Users', 'icon' => 'users', 'url' => ['/users']]);
}
?>


<aside class="main-sidebar">
    <section class="sidebar">
        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget' => 'tree'],
                'items' => $items,
            ]
        ) ?>

    </section>

</aside>
