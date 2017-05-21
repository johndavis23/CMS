<div class="form-group">
    <?php if(!empty($obj["id"])): ?>
        <label for="<?= $obj["id"] ?>"><?= $obj["label"] ?></label>
    <?php endif; ?>
    <input type="password"
           name="<?= $obj["name"] ?>"
           required=""
           class="form-control <?= $obj["class"] ?>"
           id="<?= $obj["id"] ?>"
           placeholder="<?= $obj["placeholder"] ?>">
</div>