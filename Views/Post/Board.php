<?php $this->render("Local/Header", ["title"=> $board['name']]); ?>
<ul>
    <?php foreach ($posts as $post) : ?>
        <div class="forum-item">
            <div class="row">
                <div class="col-md-9">
                    <div class="forum-icon">
                        <i class="fa fa-shield"></i>
                    </div>
                    <a href="/pestblog/Barebones-SMF/index.php/Forum/Thread/<?= $post['id_topic'] ?>/<?= urlencode($post['message'][0]['subject']) ?>">
                        <?= $post['message'][0]['subject'] ?>
                    </a>
                    <div class="forum-sub-title"><?= $board['description'] ?></div>
                </div>
                <div class="col-md-3 forum-info">

                </div>

            </div>
        </div>
    <?php endforeach; ?>
</ul>
<?php $this->render("Local/Footer", []);  ?>

