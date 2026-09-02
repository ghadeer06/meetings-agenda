<?php

$customName = $_GET['name'] ?? '';


$sections = [

    'promotions' => [
        'title' => 'الترقيات',
        'description' => 'قائمة بأسماء المرشحين للترقية',
        'names' => [
            ['name' => 'أحمد محمد العتيبي', 'department' => 'إدارة الموارد البشرية', 'position' => 'أخصائي أول', 'status' => 'قيد المراجعة'],
            ['name' => 'سارة عبدالله الزهراني', 'department' => 'إدارة المالية', 'position' => 'محاسب أول', 'status' => 'قيد المراجعة'],
            ['name' => 'نورة خالد الدوسري', 'department' => 'إدارة تقنية المعلومات', 'position' => 'مطوّر برامج', 'status' => 'مقبول مبدئيًا'],
            ['name' => 'فيصل بندر الشهري', 'department' => 'إدارة العمليات', 'position' => 'مشرف عمليات', 'status' => 'قيد المراجعة'],
            ['name' => 'منى صالح الحربي', 'department' => 'إدارة التسويق', 'position' => 'أخصائي تسويق أول', 'status' => 'مقبول مبدئيًا'],
            ['name' => 'عبدالله ماجد القحطاني', 'department' => 'إدارة المشاريع', 'position' => 'منسق مشاريع', 'status' => 'قيد المراجعة']
        ]
    ],


    'transfers' => [
        'title' => 'النقل',
        'description' => 'قائمة طلبات النقل',
        'names' => [
            ['name' => 'محمد أحمد', 'department' => 'إدارة الموارد البشرية', 'position' => 'موظف', 'status' => 'قيد المراجعة'],
            ['name' => 'ريم خالد', 'department' => 'إدارة المالية', 'position' => 'محاسب', 'status' => 'مقبول مبدئيًا'],
            ['name' => 'عبدالعزيز علي', 'department' => 'إدارة العمليات', 'position' => 'مشرف', 'status' => 'قيد المراجعة']
        ]
    ],


    'approvals' => [
        'title' => 'الإقرارات',
        'description' => 'قائمة الإقرارات',
        'names' => [
            ['name' => 'سلمان محمد', 'department' => 'الإدارة العامة', 'position' => 'موظف', 'status' => 'بانتظار الاعتماد'],
            ['name' => 'نوف عبدالله', 'department' => 'إدارة الموارد البشرية', 'position' => 'أخصائي', 'status' => 'تم الاعتماد']
        ]
    ],


    'appointments' => [
        'title' => 'التعيينات',
        'description' => 'قائمة المرشحين للتعيين',
        'names' => [
            ['name' => 'خالد صالح', 'department' => 'إدارة تقنية المعلومات', 'position' => 'مطور', 'status' => 'مرشح'],
            ['name' => 'هند سعد', 'department' => 'إدارة المالية', 'position' => 'محاسب', 'status' => 'قيد المراجعة']
        ]
    ],


    'nominations' => [
        'title' => 'الترشيحات',
        'description' => 'قائمة الترشيحات',
        'names' => [
            ['name' => 'عبدالله فهد', 'department' => 'إدارة المشاريع', 'position' => 'مدير مشروع', 'status' => 'مرشح'],
            ['name' => 'لطيفة محمد', 'department' => 'إدارة التسويق', 'position' => 'أخصائي', 'status' => 'مرشح']
        ]
    ],


    'decisions' => [
        'title' => 'القرارات',
        'description' => 'القرارات المطروحة في الاجتماع',
        'names' => [
            ['name' => 'قرار رقم 001', 'department' => 'الإدارة العامة', 'position' => 'قرار إداري', 'status' => 'للمناقشة'],
            ['name' => 'قرار رقم 002', 'department' => 'إدارة الموارد البشرية', 'position' => 'قرار تنظيمي', 'status' => 'للاعتماد']
        ]
    ]

];

// إذا كانت أجندة مضافة من المستخدم
if ($type === 'custom' && $customName !== '') {

    $current = [
        'title' => $customName,
        'description' => 'تفاصيل أجندة الاجتماع',
        'names' => []
    ];

} else {

    if (!isset($sections[$type])) {
        $type = 'promotions';
    }

    $current = $sections[$type];
}

?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= htmlspecialchars($current['title']) ?></title>

    <link rel="stylesheet" href="style.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">

</head>


<body>

<div class="page">


    <!-- الهيدر -->
    <header class="header">

        <div class="header-title">

            <div class="header-icon">
                📋
            </div>

            <h1>أجندة الاجتماع</h1>

        </div>


        <a href="index.php" class="back-top">
            ← العودة إلى أجندة الاجتماع
        </a>

    </header>



    <!-- المحتوى -->
    <main class="section-main">


        <div class="section-title">

            <h2>
                <?= htmlspecialchars($current['title']) ?>
            </h2>

            <div class="line">
                <span></span>
                <b>•</b>
                <span></span>
            </div>

            <p>
                <?= htmlspecialchars($current['description']) ?>
            </p>

        </div>



        <!-- جدول الأسماء -->

        <div class="table-container">

            <table>

                <thead>

                    <tr>
                        <th>م</th>
                        <th>اسم الموظف</th>
                        <th>الإدارة</th>
                        <th>المنصب الحالي</th>
                        <th>الحالة</th>
                    </tr>

                </thead>


                <tbody>

                <?php foreach ($current['names'] as $index => $person): ?>

                    <tr>

                        <td>
                            <span class="number">
                                <?= $index + 1 ?>
                            </span>
                        </td>

                        <td class="employee-name">
                            <?= htmlspecialchars($person['name']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($person['department']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($person['position']) ?>
                        </td>

                        <td>

                            <span class="status">
                                <span class="status-dot"></span>

                                <?= htmlspecialchars($person['status']) ?>

                            </span>

                        </td>

                    </tr>

                <?php endforeach; ?>

                </tbody>

            </table>

        </div>



        <!-- زر العودة -->

        <div class="back-container">

            <a href="index.php" class="back-button">
                ← العودة إلى أجندة الاجتماع
            </a>

        </div>


    </main>



    <footer>
        © 2024 جميع الحقوق محفوظة
    </footer>

</div>

</body>

</html>