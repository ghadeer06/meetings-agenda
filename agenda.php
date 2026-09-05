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

$pageText = $agenda['text'] ?? '';
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title><?= h($agenda['title']) ?></title>

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link
        href="https://fonts.googleapis.com/css2?family=Cairo:wght@600;700;800&family=Tajawal:wght@400;500;700&display=swap"
        rel="stylesheet"
    >

    <link
        rel="stylesheet"
        href="assets/style.agenda.css"
    >
</head>

<body>

<header class="topbar">
    <div class="topbar-inner">

        <div class="brand">
            <div class="brand-logo">
                <img src="logo.png" alt="Logo">
            </div>
        </div>

        <div class="brand-center">
            <h1><?= h($agenda['title']); ?></h1>
            <div class="brand-sub">أجندتك الخاصة</div>
        </div>

    </div>
</header>


<main class="page agenda-page">

    <div class="agenda-editor-card">

        <div class="editor-heading">

            <div class="agenda-title-box">

                <h2>
                    محتوى الأجندة
                </h2>

                <p>
                    اكتب ملاحظاتك ومحتوى الاجتماع هنا
                </p>

            </div>

        </div>

        <div class="word-editor">

            <div
                class="editor-toolbar"
                id="editorToolbar"
            >

                <button
                    type="button"
                    class="editor-btn"
                    data-command="undo"
                    title="تراجع"
                >
                    ↶
                </button>

                <button
                    type="button"
                    class="editor-btn"
                    data-command="redo"
                    title="إعادة"
                >
                    ↷
                </button>

                <span class="editor-sep"></span>

                <select
                    id="fontSizeSelect"
                    class="editor-select"
                    title="حجم الخط"
                >

                  

                    <option value="1">
                        1 
                    </option>

                    <option value="2">
                        2
                    </option>

                    <option value="3">
                        3
                    </option>

                    <option value="4">
                        4
                    </option>

                    <option value="5" selected>
                        5
                    </option>

                    <option value="6">
                        6                    
                    </option>

                    <option value="7">
                        7
                    </option>

                </select>

                <span class="editor-sep"></span>

                <button
                    type="button"
                    class="editor-btn editor-format"
                    data-command="bold"
                    title="غامق"
                >
                    <strong>B</strong>
                </button>

                <button
                    type="button"
                    class="editor-btn editor-format"
                    data-command="italic"
                    title="مائل"
                >
                    <em>I</em>
                </button>

                <button
                    type="button"
                    class="editor-btn editor-format"
                    data-command="underline"
                    title="تحته خط"
                >
                    <u>U</u>
                </button>

                <span class="editor-sep"></span>

                <button
                    type="button"
                    class="editor-btn"
                    data-command="insertUnorderedList"
                    title="قائمة نقطية"
                >
                    ☷
                </button>

                <button
                    type="button"
                    class="editor-btn"
                    data-command="insertOrderedList"
                    title="قائمة مرقمة"
                >
                    1.
                </button>

                <span class="editor-sep"></span>

                <button
                    type="button"
                    class="editor-btn"
                    data-command="justifyRight"
                    title="محاذاة لليمين"
                >
                    ☰
                </button>

                <button
                    type="button"
                    class="editor-btn"
                    data-command="justifyCenter"
                    title="توسيط"
                >
                    ≡
                </button>

                <button
                    type="button"
                    class="editor-btn"
                    data-command="justifyLeft"
                    title="محاذاة لليسار"
                >
                    ☰
                </button>

                <span class="editor-sep"></span>

                <button
                    type="button"
                    class="editor-btn"
                    data-command="indent"
                    title="زيادة المسافة"
                >
                    ⇥
                </button>

                <button
                    type="button"
                    class="editor-btn"
                    data-command="outdent"
                    title="تقليل المسافة"
                >
                    ⇤
                </button>

                <span class="editor-sep"></span>

                <button
                    type="button"
                    class="editor-btn clear-format"
                    data-command="removeFormat"
                    title="إزالة التنسيق"
                >
                    Tx
                </button>

            </div>

            <div
                id="itemEditor"
                class="editor-area is-locked"
                contenteditable="false"
                dir="rtl"
                data-placeholder="اضغط «تعديل» للبدء بالكتابة..."
            ><?= $pageText ?></div>

        </div>

        <div class="page-actions">

          <a href="index.php" class="btn-back btn-return">عودة</a>


            <button
                type="button"
                id="saveBtn"
                class="btn-back btn-save"
            >
                حفظ
            </button>

            <button
                type="button"
                id="editBtn"
                class="btn-back btn-edit"
            >
                تعديل
            </button>

        </div>

    </div>

</main>

<form
    method="post"
    action="actions.php"
    id="itemForm"
    class="hidden-form"
>

    <input
        type="hidden"
        name="action"
        value="save_page"
    >

    <input
        type="hidden"
        name="agenda_id"
        value="<?= h($agenda['id']) ?>"
    >

    <input
        type="hidden"
        name="text"
        id="text"
        value=""
    >

</form>

<footer class="site-footer">

    <p class="footer-year">
        <?= date('Y') ?>
    </p>

</footer>

<script>
document.addEventListener('DOMContentLoaded', () => {

    const editor = document.getElementById('itemEditor');
    const editBtn = document.getElementById('editBtn');
    const saveBtn = document.getElementById('saveBtn');
    const form = document.getElementById('itemForm');
    const hiddenText = document.getElementById('text');
    const toolbar = document.getElementById('editorToolbar');
    const toolbarButtons = toolbar.querySelectorAll('.editor-btn');
    const fontSizeSelect = document.getElementById('fontSizeSelect');

    let isEditing = false;
    editor.setAttribute('contenteditable', 'false');
    editor.classList.add('is-locked');
    toolbarButtons.forEach((btn) => { btn.disabled = true; });
    if (fontSizeSelect) fontSizeSelect.disabled = true;

    editor.addEventListener('keydown', (e) => {
        if (!isEditing) e.preventDefault();
    });
    editor.addEventListener('paste', (e) => {
        if (!isEditing) e.preventDefault();
    });
    editor.addEventListener('input', (e) => {
        if (!isEditing) e.preventDefault();
    });

    // تفعيل كل أزرار شريط الأدوات (غامق، مائل، قوائم، محاذاة، تراجع...)
    toolbarButtons.forEach((btn) => {
        btn.addEventListener('click', () => {
            if (btn.disabled) return;
            const cmd = btn.getAttribute('data-command');
            editor.focus();
            document.execCommand(cmd, false, null);
        });
    });

    // تفعيل قائمة حجم الخط
    if (fontSizeSelect) {
        fontSizeSelect.addEventListener('change', () => {
            if (fontSizeSelect.disabled) return;
            editor.focus();
            document.execCommand('fontSize', false, fontSizeSelect.value);
        });
    }

    editBtn.addEventListener('click', () => {

        if (isEditing) {

            isEditing = false;
            editor.setAttribute('contenteditable', 'false');
            editor.classList.add('is-locked');
            editor.classList.remove('is-editing');
            editBtn.textContent = 'تعديل';
            toolbarButtons.forEach((btn) => { btn.disabled = true; });
            if (fontSizeSelect) fontSizeSelect.disabled = true;

        } else {

            isEditing = true;
            editor.setAttribute('contenteditable', 'true');
            editor.classList.remove('is-locked');
            editor.classList.add('is-editing');
            editBtn.textContent = 'إغلاق التعديل';
            toolbarButtons.forEach((btn) => { btn.disabled = false; });
            if (fontSizeSelect) fontSizeSelect.disabled = false;
            editor.focus();
        }
    });

    saveBtn.addEventListener('click', () => {
        if (!isEditing) return;
        hiddenText.value = editor.innerHTML;
        form.submit();
    });

});
</script>



</body>

</html>
