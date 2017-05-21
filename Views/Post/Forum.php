<?php $this->render("Local/Header", ["title"=> "Forum", "description"=>"Please select a board"]); ?>


<div class="forum-title">
    <div class="pull-right forum-desc">
        <samll>Total posts: 320,800</samll>
    </div>
    <h3>General subjects</h3>
</div>

<?php foreach ($boards as $board) : ?>
    <div class="forum-item">
        <div class="row">
            <div class="col-md-9">
                <div class="forum-icon">
                    <i class="fa fa-shield"></i>
                </div>
                <a href="/pestblog/Barebones-SMF/index.php/Forum/Board/<?= $board['id_board'] ?>/<?= urlencode($board['name']) ?>" class="forum-item-title"><?= $board['name'] ?></a>
                <div class="forum-sub-title"><?= $board['description'] ?></div>
            </div>
            <div class="col-md-1 forum-info">
                        <span class="views-number">
                            1216
                        </span>
                <div>
                    <small>Views</small>
                </div>
            </div>
            <div class="col-md-1 forum-info">
                        <span class="views-number">
                            368
                        </span>
                <div>
                    <small>Topics</small>
                </div>
            </div>
            <div class="col-md-1 forum-info">
                        <span class="views-number">
                            140
                        </span>
                <div>
                    <small>Posts</small>
                </div>
            </div>
        </div>
    </div>

<?php endforeach; ?>



<div class="forum-title">
    <div class="pull-right forum-desc">
        <small>Total posts: 17,800,600</small>
    </div>
    <h3>Other subjects</h3>
</div>


<?php $this->render("Local/Footer", []); ?>

