<?php
$this->render("Local/Header", ["title"=> $posts[0]['subject']]);
?>

    <div class="forum-post-info">
        <h4><span class="text-navy"><i class="fa fa-globe"></i> General discussion</span> - Announcements - <span class="text-muted">Free talks</span></h4>
    </div>
<?php foreach ($posts as $post) : ?>

    <div class="media">
        <a class="forum-avatar" href="#">
            <img src="img/a1.jpg" class="img-circle" alt="image">
            <div class="author-info">
                <strong><?= $post['member']['member_name'] ?></strong><br/>
                <strong>Posts:</strong> <?= $post['member']['posts'] ?><br/>
                <strong>Joined:</strong> <?= date("M d, Y", $post['member']['date_registered']) ?><br/>
            </div>
        </a>
        <div class="media-body">
            <h4 class="media-heading"><?= $post['subject'] ?></h4>
            <?php $parser->parse($post['body']); echo $parser->getAsHtml(); ?>
            <?= ($post['member']['personal_text']) ?>
        </div>
    </div>


<?php endforeach; ?>
<div class="forum-post-info">
<?= $form ?>
</div>
<?php $this->render("Local/Footer", []); ?>