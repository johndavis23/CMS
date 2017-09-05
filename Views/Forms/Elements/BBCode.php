<div class="form-group">
    <?php

    use App\Classes\Registry;

    Registry::get()->registerJS(' $(window).load(function() {
            $(function() {
                $("#'.$obj["id"].'").wysibb();
            })
        })');

    ?>


    <?php if(!empty($obj["label"])): ?>
        <label for="<?= $obj["id"] ?>"><?= $obj["label"] ?></label>
    <?php endif ?>
    <textarea   id   = "<?= $obj["id"] ?>"
                name = "<?= $obj["name"] ?>"
                class= "<?= $obj["class"] ?>"
    ><?= $obj['value'] ?></textarea>

</div>