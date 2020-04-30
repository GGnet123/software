<aside class="main-sidebar">
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= $directoryAsset ?>/img/user2-160x160.jpg" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p><?= Yii::$app->user->identity->username ?></p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>
            <?= dmstr\widgets\Menu::widget(
                [
                    'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
                    'items' => [
                        [
                            'label' => 'Categories',
                            'url' => ['/categories/index'],
                            'icon' => 'table'
                        ],
                        [
                            'label' => 'Products',
                            'url' => ['/products/index'],
                            'icon' => 'product-hunt'
                        ],
                        [
                            'label' => 'Orders',
                            'url' => ['/orders/index'],
                            'icon' => 'shopping-cart'
                        ],
                        [
                            'label' => 'Runners',
                            'url' => ['/runners-admin/index'],
                            'icon' => 'car'
                        ],
                        [
                            'label' => 'Stores',
                            'url' => ['/stores/index'],
                            'icon' => 'home'
                        ],
                        [
                            'label' => 'Auction',
                            'url' => ['/auction/index'],
                            'icon' => 'money'
                        ],
                        [
                            'label' => 'Users',
                            'url' => ['/user/index'],
                            'icon' => 'user-plus'
                        ],

                    ],
                ]
            ) ?>
    </section>

</aside>
