<?php
declare(strict_types=1);

define('DATA_FILE', __DIR__ . '/../data/agendas.json');

function load_agendas(): array
{
    if (!file_exists(DATA_FILE)) {
        save_agendas(['agendas' => []]);
    }
    $raw = file_get_contents(DATA_FILE);
    $data = json_decode($raw, true);
    if (!is_array($data) || !isset($data['agendas'])) {
        $data = ['agendas' => []];
    }
    return $data;
}

function save_agendas(array $data): void
{
    file_put_contents(DATA_FILE, json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
}

 
function generate_id(): string
{
    return bin2hex(random_bytes(6));
}

function find_agenda(array $data, string $id): ?array
{
    foreach ($data['agendas'] as $agenda) {
        if ($agenda['id'] === $id) {
            return $agenda;
        }
    }
    return null;
}

function h(string $text): string
{
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}

function format_date(string $isoDate): string
{
    $ts = strtotime($isoDate);
    if (!$ts) {
        return '';
    }
    return date('Y/m/d - H:i', $ts);
}
