<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$action = $_POST['action'] ?? '';
$data = load_agendas();

function clean_editor_html(string $html): string
{
    $allowedTags = '<b><strong><i><em><u><ul><ol><li><p><div><br>';

    $html = strip_tags($html, $allowedTags);

    return trim($html);
}

switch ($action) {

    case 'add_agenda':

        $title = trim($_POST['title'] ?? '');

        if ($title !== '') {

            $data['agendas'][] = [
                'id' => generate_id(),
                'title' => $title,
                'created_at' => date('c'),
                'items' => [],
                'text' => '',
            ];

            save_agendas($data);
        }

        header('Location: index.php');
        exit;

    case 'delete_agenda':

        $id = $_POST['agenda_id'] ?? '';

        $data['agendas'] = array_values(
            array_filter(
                $data['agendas'],
                fn($a) => $a['id'] !== $id
            )
        );

        save_agendas($data);

        header('Location: index.php');
        exit;

    case 'add_item':

        $agendaId = $_POST['agenda_id'] ?? '';
        $text = clean_editor_html($_POST['text'] ?? '');

        foreach ($data['agendas'] as &$agenda) {

            if ($agenda['id'] === $agendaId && $text !== '') {

                if (!isset($agenda['items'])) {
                    $agenda['items'] = [];
                }

                $agenda['items'][] = [
                    'id' => generate_id(),
                    'text' => $text,
                    'done' => false,
                    'created_at' => date('c'),
                ];

                break;
            }
        }

        unset($agenda);

        save_agendas($data);

        header(
            'Location: agenda.php?id=' . urlencode($agendaId)
        );

        exit;

    case 'update_item':

        $agendaId = $_POST['agenda_id'] ?? '';
        $itemId = $_POST['item_id'] ?? '';
        $text = clean_editor_html($_POST['text'] ?? '');

        if ($text !== '') {

            foreach ($data['agendas'] as &$agenda) {

                if ($agenda['id'] !== $agendaId) {
                    continue;
                }

                foreach ($agenda['items'] as &$item) {

                    if ($item['id'] === $itemId) {

                        $item['text'] = $text;

                        break;
                    }
                }

                unset($item);
                break;
            }

            unset($agenda);

            save_agendas($data);
        }

        header(
            'Location: agenda.php?id=' . urlencode($agendaId)
        );

        exit;

    case 'toggle_item':

        $agendaId = $_POST['agenda_id'] ?? '';
        $itemId = $_POST['item_id'] ?? '';

        foreach ($data['agendas'] as &$agenda) {

            if ($agenda['id'] === $agendaId) {

                foreach ($agenda['items'] as &$item) {

                    if ($item['id'] === $itemId) {
                        $item['done'] = !$item['done'];
                        break;
                    }
                }

                unset($item);
                break;
            }
        }

        unset($agenda);

        save_agendas($data);

        header(
            'Location: agenda.php?id=' . urlencode($agendaId)
        );

        exit;

    case 'delete_item':

        $agendaId = $_POST['agenda_id'] ?? '';
        $itemId = $_POST['item_id'] ?? '';

        foreach ($data['agendas'] as &$agenda) {

            if ($agenda['id'] === $agendaId) {

                $agenda['items'] = array_values(
                    array_filter(
                        $agenda['items'],
                        fn($i) => $i['id'] !== $itemId
                    )
                );

                break;
            }
        }

        unset($agenda);

        save_agendas($data);

        header(
            'Location: agenda.php?id=' . urlencode($agendaId)
        );

        exit;

    case 'save_page':

        $agendaId = $_POST['agenda_id'] ?? '';
        $text = clean_editor_html($_POST['text'] ?? '');

        foreach ($data['agendas'] as &$agenda) {

            if ($agenda['id'] === $agendaId) {

                $agenda['text'] = $text;

                break;
            }
        }

        unset($agenda);

        save_agendas($data);

        header(
            'Location: agenda.php?id=' . urlencode($agendaId)
        );

        exit;

    default:

        header('Location: index.php');
        exit;
}
