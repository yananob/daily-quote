<?php require __DIR__ . '/../layouts/header.php'; ?>

<form action="/quotes" method="post">
    <dl class="form-list">
        <dt><label class="form-label">Message</label></dt>
        <dd><textarea name="message" class="form-control" rows="5"><?php echo htmlspecialchars($quote->message ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea></dd>
        <dt><label class="form-label">Author</label></dt>
        <dd><input type="text" name="author" class="form-control" value="<?php echo htmlspecialchars($quote->author ?? '', ENT_QUOTES, 'UTF-8'); ?>"></dd>
        <dt><label class="form-label">Source</label></dt>
        <dd><input type="text" name="source" class="form-control" value="<?php echo htmlspecialchars($quote->source ?? '', ENT_QUOTES, 'UTF-8'); ?>"></dd>
        <dt><label class="form-label">Source link</label></dt>
        <dd><input type="text" name="source_link" class="form-control" value="<?php echo htmlspecialchars($quote->source_link ?? '', ENT_QUOTES, 'UTF-8'); ?>"></dd>
    </dl>
    <button type="submit" class="btn btn-primary">Store</button>
    <a href="/" class="btn btn-warning">Back</a>
</form>

<?php require __DIR__ . '/../layouts/footer.php'; ?>