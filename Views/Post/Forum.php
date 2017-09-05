<?php $this->render("Local/Header", ["title"=> "Forum", "description"=>"Please select a board"]); ?>
<?php
function formatLarge($num) {
    $x = round($num);
    $x_number_format = number_format($x);
    $x_array = explode(',', $x_number_format);
    $x_parts = array('k', 'm', 'b', 't');
    $x_count_parts = count($x_array) - 1;
    $x_display = $x;
    $x_display = $x_array[0] . ((int) $x_array[1][0] !== 0 ? '.' . $x_array[1][0] : '');
    $x_display .= $x_parts[$x_count_parts - 1];
    return $x_display;
}
?>
<?php use App\Util\UrlUtils; ?>
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
                <a href="<?= UrlUtils::getControllerUrl('Forum/Board'); ?><?= $board['id_board'] ?>/<?= urlencode($board['name']) ?>" class="forum-item-title"><?= $board['name'] ?></a>
                <div class="forum-sub-title"><?= $board['description'] ?></div>
            </div>
            <div class="col-md-1 forum-info">

            </div>
            <div class="col-md-1 forum-info">
                        <span class="views-number">
                             <?= formatLarge($board['num_topics']) ?>
                        </span>
                <div>
                    <small>Topics</small>
                </div>
            </div>
            <div class="col-md-1 forum-info">
                        <span class="views-number">
                            <?= formatLarge($board['num_posts']) ?>
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

