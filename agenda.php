<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/functions.php';

$data = load_agendas();
$id = $_GET['id'] ?? '';
$agenda = find_agenda($data, $id);

if (!$agenda) {
    header('Location: index.php');
    exit;
}

$items = array_reverse($agenda['items']);
$total = count($agenda['items']);
$doneCount = count(array_filter($agenda['items'], fn($i) => $i['done']));
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= h($agenda['title']) ?> - أجندة اجتماع</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@600;700;800&family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/style.css">
</head>
<body>

<header class="topbar">
    <div class="topbar-inner">
        <a href="index.php" class="btn btn-ghost">→ عودة</a>
        <div class="brand brand-center">
            <h1><?= h($agenda['title']) ?></h1>
            <?php if ($total > 0): ?>
                <p class="brand-sub"><?= $doneCount ?> من <?= $total ?> بندًا مكتمل</p>
            <?php endif; ?>
        </div>
        <button type="button" class="btn btn-primary" id="openAddItem">+ إضافة</button>
    </div>
</header>

<main class="page page-narrow">

    <?php if ($total > 0): ?>
    <div class="progress-track" aria-hidden="true">
        <div class="progress-fill" style="width: <?= (int) round(($doneCount / $total) * 100) ?>%"></div>
    </div>
    <?php endif; ?>

    <?php if (empty($items)): ?>
        <div class="empty-state">
            <p>لا توجد بنود في هذه الأجندة بعد.</p>
            <p class="muted">اضغط "إضافة" لإدراج أول بند.</p>
        </div>
    <?php else: ?>
        <ul class="item-list">
            <?php foreach ($items as $item): ?>
            <li class="item-row <?= $item['done'] ? 'is-done' : '' ?>">
                <form method="post" action="actions.php" class="item-toggle-form">
                    <input type="hidden" name="action" value="toggle_item">
                    <input type="hidden" name="agenda_id" value="<?= h($agenda['id']) ?>">
                    <input type="hidden" name="item_id" value="<?= h($item['id']) ?>">
                    <button type="submit" class="check-btn" aria-label="تبديل الحالة">
                        <?= $item['done'] ? '✓' : '' ?>
                    </button>
                </form>
                <div class="item-body">
                    <span class="item-text"><?= h($item['text']) ?></span>
                    <span class="item-date"><?= h(format_date($item['created_at'])) ?></span>
                </div>
                <form method="post" action="actions.php" onsubmit="return confirm('حذف هذا البند؟');">
                    <input type="hidden" name="action" value="delete_item">
                    <input type="hidden" name="agenda_id" value="<?= h($agenda['id']) ?>">
                    <input type="hidden" name="item_id" value="<?= h($item['id']) ?>">
                    <button type="submit" class="icon-btn" title="حذف البند" aria-label="حذف البند">✕</button>
                </form>
            </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

</main>

<!-- نافذة إضافة بند -->
<div class="modal-overlay" id="addItemModal">
    <div class="modal-box">
        <h3>إضافة بند جديد</h3>
        <form method="post" action="actions.php">
            <input type="hidden" name="action" value="add_item">
            <input type="hidden" name="agenda_id" value="<?= h($agenda['id']) ?>">
            <label for="text">نص البند</label>
            <input type="text" id="text" name="text" placeholder="مثال: مناقشة طلب النقل" required autofocus>
            <div class="modal-actions">
                <button type="button" class="btn btn-ghost" id="cancelAddItem">إلغاء</button>
                <button type="submit" class="btn btn-primary">إضافة</button>
            </div>
        </form>
    </div>
</div>

<script src="assets/script.js"></script>
</body>
</html>