<?php
$fecha = $_GET['fecha'] ?? null;

if (!$fecha) {
    echo json_encode([]);
    exit;
}

// Día de la semana: 1 = lunes, 7 = domingo
$diaSemana = date('N', strtotime($fecha));

$horas = [];

// Martes cerrado → no hay horas
if ($diaSemana == 2) {
    echo json_encode($horas);
    exit;
}

// Entre semana y domingo → 13:30 a 15:30 cada 15 min
if (in_array($diaSemana, [1,3,4,7])) {
    for ($t = strtotime('13:30'); $t <= strtotime('15:30'); $t += 15*60) {
        $horas[] = date('H:i', $t);
    }
}

// Viernes y sábado → 13:30 a 15:30 + 20:30 a 22:30
if (in_array($diaSemana, [5,6])) {
    // Mañana
    for ($t = strtotime('13:30'); $t <= strtotime('15:30'); $t += 15*60) {
        $horas[] = date('H:i', $t);
    }
    // Noche
    for ($t = strtotime('20:30'); $t <= strtotime('22:30'); $t += 15*60) {
        $horas[] = date('H:i', $t);
    }
}

echo json_encode($horas);
