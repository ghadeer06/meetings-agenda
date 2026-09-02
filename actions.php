<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$action = $_POST['action'] ?? '';
$data = load_agendas();

switch ($action) {

    // إضافة أجندة جديدة
    case 'add_agenda':
        $title = trim($_POST['title'] ?? '');
        if ($title !== '') {
            $data['agendas'][] = [
                'id' => generate_id(),
                'title' => $title,
                'created_at' => date('c'),
                'items' => [],
            ];
            save_agendas($data);
        }
        header('Location: index.php');
        exit;

    // حذف أجندة كاملة
    case 'delete_agenda':
        $id = $_POST['agenda_id'] ?? '';
        $data['agendas'] = array_values(array_filter(
            $data['agendas'],
            fn($a) => $a['id'] !== $id
        ));
        save_agendas($data);
        header('Location: index.php');
        exit;

    // إضافة بند إلى أجندة
    case 'add_item':
        $agendaId = $_POST['agenda_id'] ?? '';
        $text = trim($_POST['text'] ?? '');
        foreach ($data['agendas'] as &$agenda) {
            if ($agenda['id'] === $agendaId && $text !== '') {
                $agenda['items'][] = [
                    'id' => generate_id(),
                    'text' => $text,
                    'done' => false,
                    'created_at' => date('c'),
                ];
            }
        }
        unset($agenda);
        save_agendas($data);
        header('Location: agenda.php?id=' . urlencode($agendaId));
        exit;

    // تبديل حالة البند (منجز / غير منجز)
    case 'toggle_item':
        $agendaId = $_POST['agenda_id'] ?? '';
        $itemId = $_POST['item_id'] ?? '';
        foreach ($data['agendas'] as &$agenda) {
            if ($agenda['id'] === $agendaId) {
                foreach ($agenda['items'] as &$item) {
                    if ($item['id'] === $itemId) {
                        $item['done'] = !$item['done'];
                    }
                }
                unset($item);
            }
        }
        unset($agenda);
        save_agendas($data);
        header('Location: agenda.php?id=' . urlencode($agendaId));
        exit;

    // حذف بند من الأجندة
    case 'delete_item':
        $agendaId = $_POST['agenda_id'] ?? '';
        $itemId = $_POST['item_id'] ?? '';
        foreach ($data['agendas'] as &$agenda) {
            if ($agenda['id'] === $agendaId) {
                $agenda['items'] = array_values(array_filter(
                    $agenda['items'],
                    fn($i) => $i['id'] !== $itemId
                ));
            }
        }
        unset($agenda);
        save_agendas($data);
        header('Location: agenda.php?id=' . urlencode($agendaId));
        exit;

    default:
        header('Location: index.php');
        exit;
}