<div class="form-group">
    <?php if(!empty($obj["label"])): ?>
        <label for="<?= $obj["id"] ?>"><?= $obj["label"] ?></label>
    <?php endif ?>
    <input type="text"
           value="<?= $obj['value'] ?>"
           name="<?= $obj["name"] ?>"
           required=""
           class="form-control <?= $obj["class"] ?>"
           id="<?= $obj["id"] ?>"
           placeholder="<?= $obj["placeholder"] ?>">
</div>