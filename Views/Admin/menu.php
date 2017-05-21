<?php
require_once('Config/admin.php');
use App\Util\UrlUtils;
?>

<ul class="sidebar-menu">
    <?php foreach($admin as $key => $item) : ?>
        <li class="header"><?= $key ?></li>
        <?php foreach ($item as $menuKey => $menuItem) : ?>
            <?php if (array_key_exists("action", $menuItem)) : ?>
                <li><a href="<?= UrlUtils::getControllerUrl($menuItem['action']) ?>"><i class="<?= $menuItem['icon'] ?>"></i>
                        <span><?= $menuKey ?></span></a></li>
            <?php endif; ?>
            <?php if (array_key_exists("submenu", $menuItem)) : ?>
                <li class="treeview">
                    <a href="#"><i class="<?= $menuItem['icon'] ?>"></i>
                        <span><?= $menuKey ?></span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <?php foreach ( $menuItem['submenu'] as $subMenuKey => $subMenu) : ?>
                            <li><a href="<?= UrlUtils::getControllerUrl($subMenu['action']) ?>"><?= $subMenuKey ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </li>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endforeach; ?>
</ul>