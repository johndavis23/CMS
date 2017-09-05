<?php
use App\Classes\Model;
use App\Util\UrlUtils;

$this->render("Local/Header", ["title"=> $board['name']]); ?>
<?= $board['description'] ?>
<ul>
    <?php foreach ($posts as $post) : ?>
        <div class="forum-item">
            <div class="row">
                <div class="col-md-9">
                    <div class="forum-icon">
                        <i class="fa fa-shield"></i>
                    </div>
                    <a href="<?= UrlUtils::getControllerUrl('Forum/Thread'); ?><?= $post['id_topic'] ?>/<?= urlencode($post['message'][0]['subject']) ?>">
                        <?= $post['message']['subject'] ?>
                    </a>
                    <div class="forum-sub-title">
                        Started by <?php

                        $model = new Model('smf_members','id_member','smf');
                        $users = $model->readWithId($post['message']['id_member']);
                        ?>
                        <?= $users['member_name'] ?>

                    </div>
                </div>
                <div class="col-md-3 forum-info">
                    7 Replies<br >
                    57 Views<br >
                </div>

            </div>
        </div>
    <?php endforeach; ?>
</ul>
<nav aria-label="Page navigation">
    <ul class="pagination">
        <li>
            <a href="#" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
            </a>
        </li>
        <?php for($i = 0; $i < $pages; $i++): ?>
            <li><a href="?page=<?= $i ?>"><?= $i ?></a></li>
        <?php endfor; ?>
        <li>
            <a href="#" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
            </a>
        </li>
    </ul>
</nav>
<?php $this->render("Local/Footer", []);  ?>

