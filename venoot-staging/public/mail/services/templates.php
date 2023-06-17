<?php

$conexion = pg_connect("host=localhost port=5432 dbname=ebdb user=venoot password=Jp@c6O5PKwEOk12Y");

//$conexion = pg_connect("host=venoot.cowuiuqfuiyl.us-west-2.rds.amazonaws.com port=5432 dbname=venoot user=venootrootuser password=74Il#JD4rXhxcTDg") or die('No se ha podido conectar: ' . pg_last_error());;

$strRequest = $_GET["qry"];
$jsondata = array();

if ($strRequest == 'gettplst') {
    $i = 0;
    $data = "";
    $qry = "select id_template,to_char(tpl_date, 'DD-MM-YYYY HH24:MI') fecha, tpl_name, tpl, tpl_type, case when tpl_type=0 then 'Sistema' else 'Personalizado' end tipo from  tpl_events where tpl_id_event =" . $_GET["prnIdEvent"] . " or tpl_type=0";
    $resultado = pg_query($conexion, $qry);
    $templates = array();
    while ($row = pg_fetch_array($resultado)) {
        $templates[] = array('id_template' => $row["id_template"], 'fecha' => $row["fecha"], 'tpl_name' => $row["tpl_name"], 'tpl_type' => $row["tpl_type"], 'tipo' => $row["tipo"]);
    }
    $jsondata["data"] = $templates;
    $jsondata["qry"] = $qry;

    header("Access-Control-Allow-Origin: *");
    header('Content-type: application/json; charset=utf-8');
    echo json_encode($jsondata, JSON_FORCE_OBJECT);
}

if ($strRequest == 'gettpl') {
    $data = "";
    $qry = "select id_template,to_char(tpl_date, 'DD-MM-YYYY HH24:MI') fecha, tpl_name, tpl, tpl_type, case when tpl_type=0 then 'Sistema' else 'Personalizado' end tipo from  tpl_events where tpl_id_event =" . $_GET["prnIdEvent"] . " or tpl_type=0";
    $resultado = pg_query($conexion, $qry) or die('La consulta fallo: ' . pg_last_error());
    while ($row = pg_fetch_array($resultado)) {
        $data .= $row["id_template"] . "|";
        $data .= $row["fecha"] . "|";
        $data .= $row["tpl_name"] . "|";
        $data .= $row["tpl_type"] . "|";
        $data .= $row["tipo"] . "#";
    }
    $jsondata["data"] = substr($data, 0, strlen($data) - 1);
    header("Access-Control-Allow-Origin: *");
    header('Content-type: application/json; charset=utf-8');
    echo json_encode($jsondata, JSON_FORCE_OBJECT);
}
if ($strRequest == 'gettplbyid') {
    $data = "";
    $qry = "select tpl,css, tpl_type from  tpl_events where id_template ='" . $_GET["prnIdtpl"] . "'";
    $resultado = pg_query($conexion, $qry) or die('La consulta fallo: ' . pg_last_error());
    $row = pg_fetch_array($resultado);

    $template = ($row["tpl"]);
    $css = ($row["css"]);

    $jsondata["tpl"] = "<!DOCTYPE html><html><body>" . $template . "</body></html>";
    $jsondata["css"] = $css;
    header("Access-Control-Allow-Origin: *");
    header('Content-type: application/json; charset=utf-8');
    echo json_encode($jsondata, JSON_FORCE_OBJECT);
}

if ($strRequest == 'gethtmlbyid') {
    $data = "";
    $qry = "select tpl,css from  tpl_events where id_template ='" . $_GET["prnIdtpl"] . "'";
    $resultado = pg_query($conexion, $qry) or die('La consulta fallo: ' . pg_last_error());
    $row = pg_fetch_array($resultado);
    $jsondata["tpl"] = ($row["tpl"]);
    $jsondata["css"] = ($row["css"]);
    echo "<style>" . $jsondata["css"] . "</style>" . $jsondata["tpl"];
}

if (isset($_POST["qry"]) && $_POST["qry"] == 'saveas') {
    $jsondata["id"] = "";
    if ($_POST["id"] == '') {
        $guidnuew = trim(getGUID(), '{}');
        $qry = "insert into tpl_events (id_template, tpl_id_event, tpl_type, tpl_name, tpl_date, tpl, css) values ";
        $qry .= "('" . $guidnuew . "'," . $_POST["eventoID"] . ",1,'" . $_POST["name"] . "',now(),'" . ($_POST["html"]) . "','" . ($_POST["css"]) . "')";
        $jsondata["id"] = $guidnuew;
    } else {
        $qry = "update tpl_events set tpl_name ='" . $_POST["name"] . "', tpl = '" . ($_POST["html"]) . "', css='" . ($_POST["css"]) . "' where id_template='" . $_POST["id"] . "'";
    }
    pg_query($conexion, $qry) or die('La consulta fallo: ' . pg_last_error());
    header('Content-type: application/json; charset=utf-8');
    echo json_encode($jsondata, JSON_FORCE_OBJECT);
    die();
}

function getGUID()
{
    if (function_exists('com_create_guid')) {
        return com_create_guid();
    } else {
        mt_srand((float) microtime() * 10000); //optional for php 4.2.0 and up.
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45); // "-"
        $uuid = chr(123) // "{"
            . substr($charid, 0, 8) . $hyphen
            . substr($charid, 8, 4) . $hyphen
            . substr($charid, 12, 4) . $hyphen
            . substr($charid, 16, 4) . $hyphen
            . substr($charid, 20, 12)
            . chr(125); // "}"
        return $uuid;
    }
}
