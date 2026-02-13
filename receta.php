<?php

header('Content-Type: application/json');

$url = "https://www.themealdb.com/api/json/v1/1/random.php";
$response = file_get_contents($url);

if ($response === FALSE) {
    echo json_encode(["error" => "No se pudo conectar con la API"]);
    exit;
}

$data = json_decode($response, true);
$receta = $data['meals'][0];

$resultado = [
    "titulo" => $receta['strMeal'],
    "imagen" => $receta['strMealThumb'],
    "descripcion" => $receta['strInstructions'],
    "ingredientes" => []
];

// Recorrer ingredientes dinámicamente
for ($i = 1; $i <= 20; $i++) {
    $ingrediente = $receta["strIngredient$i"];
    $medida = $receta["strMeasure$i"];

    if (!empty($ingrediente)) {
        $resultado["ingredientes"][] = trim($ingrediente . " " . $medida);
    }
}

echo json_encode($resultado);
