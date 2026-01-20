<!-- User Story number 12 and 13-->

<?php
include_once __DIR__ . '/../actions/postManagementAction.php';

?>
<div class="container py-4">
    <h1 class="mb-4">Post Management</h1>


    
    <?php foreach ($messages as $m): ?>
        <div class="alert alert-<?php echo htmlspecialchars($m['type']); ?>"><?php echo htmlspecialchars($m['text']); ?></div>
    <?php endforeach; ?>

    <?php if (empty($posts)): ?>
        <div class="alert alert-info">Keine Posts gefunden.</div>
    <?php else: ?>

<!-- User Story number 12 -->
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>user</th>
                        <th>post</th>
                        <th>date</th>
                        <th>delete</th>
                    </tr>
                </thead>

                <tbody>
                <?php foreach ($posts as $p): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($p['id']); ?></td>

                        <td><?php echo htmlspecialchars($p['username'] ?? ('#' . ($p['user_id'] ?? 'unknown'))); ?></td>
                        
                        <td style="max-width:220px;">
                            <?php if (!empty($p['file_path'])): ?>
                                <img src="<?php echo htmlspecialchars($p['file_path'], ENT_QUOTES, 'UTF-8'); ?>" alt="Vorschau" style="max-height:80px; max-width:200px; object-fit:cover;">
                            <?php else: ?>
                                <span class="text-muted">Keine Datei</span>
                            <?php endif; ?>
                        </td>

                        <td><?php echo htmlspecialchars($p['created_at']); ?></td>
                        
                        <!-- User Story number 13 -->
                        <td>
                            <form method="post" onsubmit="return confirm('Post wirklich löschen?');" style="display:inline">
                                <input type="hidden" name="action" value="delete_post">
                                <input type="hidden" name="delete_id" value="<?php echo htmlspecialchars($p['id']); ?>">
                                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                                <button class="btn btn-sm btn-danger">Löschen</button>
                            </form>
                        </td>

                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
</body>
</html>