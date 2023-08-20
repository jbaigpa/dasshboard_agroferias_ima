<?php

# ---- ReporteService---- 2022/10/10
# ------------- JBENAVIDES (PISOFAREJULU) ---------
#-----------------------------------------
date_default_timezone_set('America/Panama');
include("connection.php");

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    $message = htmlentities("Solo se admite el método POST");
}

// Obtener parámetros de DataTables
$start = $_POST['start'];
$length = $_POST['length'];
$searchValue = $_POST['search']['value'];
$fechadesde = $_POST['fechadesde']; //
$fechahasta = $_POST['fechahasta']; //
$soloDia = $_GET['dia']; //

// Construir la consulta SQL
$query_sample = "SELECT id, nombre, correo FROM usuarios WHERE (nombre LIKE '%$searchValue%' OR correo LIKE '%$searchValue%') LIMIT $start, $length";
$hoy = date('Y-m-d');

if(!empty($soloDia)){    
    $query = "SELECT t1.id, t1.cedula, t1.producto, t1.lugar_compra, t1.fecha_compra, t1.precio, t1.vendedor, t1.supervisor, t2.nombre as vendedor_nombre, device FROM ventas as t1 JOIN operadores as t2 on t1.vendedor = t2.cedula WHERE (t1.cedula LIKE '%$searchValue%' OR t1.lugar_compra LIKE '%$searchValue%' OR t2.nombre LIKE '%$searchValue%' OR t1.device LIKE '%$searchValue%') AND t1.fecha_compra = '$hoy'";
}else{
    $query = "SELECT t1.id, t1.cedula, t1.producto, t1.lugar_compra, t1.fecha_compra, t1.precio, t1.vendedor, t1.supervisor, t2.nombre as vendedor_nombre, device FROM ventas as t1 JOIN operadores as t2 on t1.vendedor = t2.cedula WHERE (t1.cedula LIKE '%$searchValue%' OR t1.lugar_compra LIKE '%$searchValue%' OR t2.nombre LIKE '%$searchValue%' OR t1.device LIKE '%$searchValue%') ";

    // Agregar búsqueda por fecha si se proporcionó una fecha
    if (!empty($searchDate)) {
        // Supongamos que la columna se llama "fecha" en la base de datos
        $query .= " AND t1.fecha_compra = '$searchDate'";
    }
}

$query .= " ORDER BY t1.fecha_compra DESC LIMIT $start, $length";
  

// Ejecutar la consulta
$result = $con->query($query);

// Obtener resultados como un array asociativo
$results = [];
while ($row = $result->fetch_assoc()) {
  $row["accion"] = "<a href='verReporte.php?id=".$row['id']."' class='btn btn-primary btn-sm' title='Ver'><i class='fas fa-eye'></i></a>" ;
  $results[] = $row;
}

// Obtener el total de registros (sin filtro)
if(!empty($soloDia)){
    $totalRecordsQuery = "SELECT COUNT(*) as count FROM ventas WHERE fecha_compra = '$hoy'";
}else{
    $totalRecordsQuery = "SELECT COUNT(*) as count FROM ventas";
}
    $totalRecordsResult = $con->query($totalRecordsQuery);
    $totalRecords = $totalRecordsResult->fetch_assoc()['count'];

// Obtener el total de registros después de aplicar el filtro
if(!empty($soloDia)){    
    $totalFilteredQuery = "SELECT COUNT(*) as count FROM ventas WHERE (cedula LIKE '%$searchValue%' OR device LIKE '%$searchValue%') AND fecha_compra = '$hoy'";
}else{
    $totalFilteredQuery = "SELECT COUNT(*) as count FROM ventas WHERE cedula LIKE '%$searchValue%' OR device LIKE '%$searchValue%'";
    if (!empty($searchDate)) {
        // Supongamos que la columna se llama "fecha" en la base de datos
        $totalFilteredQuery .= " AND fecha_compra = '$searchDate'";
    }
}

    $totalFilteredResult = $con->query($totalFilteredQuery);
    $totalFiltered = $totalFilteredResult->fetch_assoc()['count'];

// Construir la respuesta JSON
$response = array(
  "draw" => intval($_POST['draw']),
  "recordsTotal" => $totalRecords,
  "recordsFiltered" => $totalFiltered,
  "data" => $results
);

// Devolver la respuesta como JSON
echo json_encode($response);

// Cerrar la conexión
$con->close();

?>
