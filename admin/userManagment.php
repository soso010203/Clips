<!-- User Story number 11 and 12-->

<?php
include __DIR__ . '/../actions/userManagementAction.php';
?>


<body>
<div class="container py-4">
    <h2>User Management</h2>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

<!-- User Story number 10-->
    <?php foreach ($messages as $m): ?>
        <div class="alert alert-<?php echo htmlspecialchars($m['type']); ?>"><?php echo htmlspecialchars($m['text']); ?></div>
    <?php endforeach; ?>

    <?php if (empty($users)): ?>
        <div class="alert alert-info">Keine Benutzer gefunden.</div>

    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>user</th>
                        <th>mail</th>
                        <th>firstname</th>
                        <th>lastname</th>
                        <th>role</th>
                        <th>delete</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($users as $u): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($u['id']); ?></td>
                        <td><?php echo htmlspecialchars($u['username']); ?></td>
                        <td><?php echo htmlspecialchars($u['email']); ?></td>
                        <td><?php echo htmlspecialchars($u['firstname']); ?></td>
                        <td><?php echo htmlspecialchars($u['lastname']); ?></td>
                        <td><?php echo htmlspecialchars($u['role']); ?></td>
                        
                        <!-- User Story number 11-->
                        <!-- delete other accounts -->
                        <td>
                            <?php if ($u['id'] == ($_SESSION['user']['id'] ?? 0)): ?>
                                <span class="text-muted">Eigenes Konto</span>
                            <?php else: ?>
                                <form method="post" style="display:inline" onsubmit="return confirm('Benutzer wirklich löschen?');">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="delete_id" value="<?php echo htmlspecialchars($u['id']); ?>">
                                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">
                                    <button type="submit" class="btn btn-sm btn-danger">Löschen</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

</div>
</body>
