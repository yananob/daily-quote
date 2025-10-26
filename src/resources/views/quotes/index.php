<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="row">
    <div class="col">
        <form action="/" method="GET" class="mb-4">
            <div class="input-group">
                <input type="text" name="keyword" class="form-control" placeholder="Search by message..." value="<?php echo htmlspecialchars($keyword ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                <div class="input-group-append">
                    <button type="submit" class="btn btn-primary">Search</button>
                </div>
            </div>
        </form>
        <div class="table-responsive">
            <form action="/quotes/create">
                <button type="submit" class="btn btn-sm btn-primary">Add</button>
            </form>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Message</th>
                        <th>Author</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($quotes as $quote): ?>
                    <tr>
                        <td><a href="/quotes/<?php echo htmlspecialchars($quote->id, ENT_QUOTES, 'UTF-8'); ?>/edit"><?php echo htmlspecialchars($quote->id, ENT_QUOTES, 'UTF-8'); ?></a></td>
                        <td><?php echo htmlspecialchars($quote->message, ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($quote->author, ENT_QUOTES, 'UTF-8'); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php if ($prevCursor): ?>
            <a href="/?prev_cursor=<?php echo htmlspecialchars($prevCursor, ENT_QUOTES, 'UTF-8'); ?>&keyword=<?php echo htmlspecialchars($keyword ?? '', ENT_QUOTES, 'UTF-8'); ?>">Previous</a>
        <?php endif; ?>
        <?php if ($nextCursor): ?>
            <a href="/?cursor=<?php echo htmlspecialchars($nextCursor, ENT_QUOTES, 'UTF-8'); ?>&keyword=<?php echo htmlspecialchars($keyword ?? '', ENT_QUOTES, 'UTF-8'); ?>">Next</a>
        <?php endif; ?>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>