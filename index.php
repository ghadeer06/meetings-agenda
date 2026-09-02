<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/functions.php';

$data = load_agendas();
$agendas = $data['agendas'];
// الأحدث أولًا
$agendas = array_reverse($agendas);
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>أجندة اجتماع</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@600;700;800&family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/style.css">
</head>
<body>

<header class="topbar">
    <div class="topbar-inner">
        <div class="brand">
            <span class="brand-mark" aria-hidden="true"></span>
            <div>
                <h1>أجندة اجتماع</h1>
                <p class="brand-sub">قائمة بنود الاجتماع ومتابعتها</p>
            </div>
        </div>
        <button type="button" class="btn btn-primary" id="openAddAgenda">+ إضافة أجندة</button>
    </div>
</header>

<main class="page">

    <?php if (count($agendas) > 3): ?>
    <div class="search-row">
        <input type="text" id="searchInput" class="search-input" placeholder="ابحث عن أجندة...">
    </div>
    <?php endif; ?>

    <?php if (empty($agendas)): ?>
        <div class="empty-state">
            <p>لا توجد أجندات بعد.</p>
            <p class="muted">اضغط "إضافة أجندة" لإنشاء أول أجندة اجتماع.</p>
        </div>
    <?php else: ?>
        <div class="agenda-grid" id="agendaGrid">
            <?php foreach ($agendas as $agenda):
                $total = count($agenda['items']);
                $doneCount = count(array_filter($agenda['items'], fn($i) => $i['done']));
            ?>
            <div class="agenda-card" data-title="<?= h($agenda['title']) ?>">
                <a class="agenda-card-link" href="agenda.php?id=<?= h($agenda['id']) ?>" aria-label="فتح أجندة: <?= h($agenda['title']) ?>"></a>
                <div class="agenda-card-top">
                    <h2><?= h($agenda['title']) ?></h2>
                    <form method="post" action="actions.php" class="inline-delete" onsubmit="return confirm('حذف هذه الأجندة بكل بنودها؟');">
                        <input type="hidden" name="action" value="delete_agenda">
                        <input type="hidden" name="agenda_id" value="<?= h($agenda['id']) ?>">
                        <button type="submit" class="icon-btn" title="حذف الأجندة" aria-label="حذف الأجندة">✕</button>
                    </form>
                </div>
                <div class="agenda-card-meta">
                    <?php if ($total === 0): ?>
                        <span class="badge badge-empty">لا توجد بنود</span>
                    <?php else: ?>
                        <span class="badge"><?= $doneCount ?> من <?= $total ?> مكتمل</span>
                    <?php endif; ?>
                </div>
                <span class="agenda-card-date"><?= h(format_date($agenda['created_at'])) ?></span>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</main>

<footer class="site-footer">
    <p class="footer-year"><?= date('Y') ?></p>
</footer>

<!-- نافذة إضافة أجندة -->
<div class="modal-overlay" id="addAgendaModal">
    <div class="modal-box">
        <h3>إضافة أجندة جديدة</h3>
        <form method="post" action="actions.php">
            <input type="hidden" name="action" value="add_agenda">
            <label for="title">عنوان الأجندة</label>
            <input type="text" id="title" name="title" placeholder="مثال: ترقية، نقل، قرارات" required autofocus>
            <div class="modal-actions">
                <button type="button" class="btn btn-ghost" id="cancelAddAgenda">إلغاء</button>
                <button type="submit" class="btn btn-primary">إضافة</button>
            </div>
        </form>
    </div>
</div>

<script src="assets/script.js"></script>
</body>
</html>
