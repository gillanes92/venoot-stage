<?php

// define('DB_SERVER', 'venoot.cowuiuqfuiyl.us-west-2.rds.amazonaws.com');
// define('DB_NAME', 'venoot');
// define('DB_USER', 'venootrootuser');
// define('DB_PASSWORD', '74Il#JD4rXhxcTDg');

// define('DB_SERVER', 'localhost');
// define('DB_NAME', 'venoot');
// define('DB_USER', 'postgres');
// define('DB_PASSWORD', 'localpass');

define('DB_SERVER', 'localhost');
define('DB_USER', 'venoot');
define('DB_PASSWORD', 'Jp@c6O5PKwEOk12Y');
define('DB_NAME', 'ebdb');

$conexion = pg_connect("host=" . DB_SERVER . " port=5432 dbname=" . DB_NAME . " user=" . DB_USER . " password=" . DB_PASSWORD . "");

$strRequest = $_POST["qry"];
$jsondata = array();

if ($strRequest == 'saveForm') {
    $ary = explode("$", $_POST["save"]);
    $qry =  "delete from css_events where id_template = '" . $_POST["prnTemplate"] . "' and id_evento=" . $_POST["prnidevento"];
    pg_query($conexion, $qry) or die('La consulta fallo: ' . pg_last_error() . $qry);
    $sql = "";
    foreach ($ary as $valor) {
        echo "entre";
        $fila = explode("|", $valor);
        $sql .= "insert into css_events(id_template, id_evento,id_elemento, propiedades) values ";
        $sql .= "('" . $_POST["prnTemplate"] . "'," . $_POST["prnidevento"] . "," . $fila[0] . ",'" . $fila[1] . "');";
    }
    pg_query($conexion, $sql) or die('La consulta fallo: ' . pg_last_error() . $sql);
}

if ($strRequest == 'getForm') {
    $qry = "select count(*) cantidad from css_events where id_template='" . $_POST["prnTemplate"] . "' AND id_evento=" . $_POST["prnidevento"];
    $resultado = pg_query($conexion, $qry) or die('La consulta fallo: ' . pg_last_error() . $qry);
    $fila = pg_fetch_array($resultado);
    if ($fila["cantidad"] == 0) {
        $qry = "select * from elementos where id_template='" . $_POST["prnTemplate"] . "'  order by desc_elemento, description";
        $jsondata["type"] = "new";
    } else {
        $qry = "SELECT el.id_elemento, el.desc_elemento, el.description, el.tag, el.identificador, css.propiedades from elementos el, css_events css where el.id_template=css.id_template and el.id_elemento=css.id_elemento and css.id_template='" . $_POST["prnTemplate"] . "' and css.id_evento=" . $_POST["prnidevento"] . " order by el.desc_elemento, el.description ";
        $jsondata["type"] = "custom";
    }
    $resultado = pg_query($conexion, $qry) or die('La consulta fallo: ' . pg_last_error() . $qry);
    $html_resultado = "";
    $elements = "";
    $canvas = "";
    $canvas_property = "";
    $canvas_count = 0;
    $last_canvas = "";
    $last_identificador = "";
    $identificador_count = 0;
    $colors_id = "";
    $listid = "";

    $last_group = "";
    $sw_div = true;
    while ($fila = pg_fetch_array($resultado)) {
        if ($html_resultado != '' && $last_group != $fila["desc_elemento"]) $html_resultado .= "</div>";
        if ($last_group == '' || ($last_group != '' && $last_group != $fila["desc_elemento"])) {
            // SI TAG = # no poner link
            if ($fila["tag"] != "#") $html_resultado .= "<a href='" . $fila["tag"] . "'>";

            $html_resultado .= "<input type='button' class='editor_btn' id='button_change' onclick='show_fnc(" . $fila["id_elemento"] . ")' value='" . $fila["desc_elemento"] . "'>";

            if ($fila["tag"] != "#") $html_resultado .= "</a>";

            $html_resultado .= "<div id='btn_fnc_" . $fila["id_elemento"] . "' style='display:none'>";

            $last_group = $fila["desc_elemento"];
            $sw_div = false;
        }

        $html_resultado .= "<table>";
        $html_resultado .= "<tr><td colspan=2><strong>" . $fila["description"] . "</strong></td></tr>";

        $ary = explode(";", $fila["propiedades"]);
        foreach ($ary as $key) {
            if ($key == "") continue;

            $ta_id = "";
            $color_id = "";
            $sel_id = "";
            $fw_id = "";
            $ta_id = "";
            if ($fila["identificador"] != $last_identificador) {
                $last_identificador = $fila["identificador"];
                $canvas .= $fila["identificador"] . "|";
                $canvas_count = $canvas_count + 1;
            }
            $texto_propiedad = "";
            $propiedad = explode(":", $key);
            // echo $fila['id_elemento'] . ": " . $propiedad[0] . " => " . $propiedad[1] . " $$";
            $valor_propiedad = $propiedad[1];

            if ($propiedad[0] != '') {
                $canvas_property .= $propiedad[0] . "|";
                $identificador_count = $identificador_count + 1;
            }

            switch ($propiedad[0]) {
                case 'color':
                    $color_id = "col_" . ($canvas_count - 1) . "_" . ($identificador_count - 1) . "_" . $fila["id_elemento"];
                    $texto_propiedad = "Color";
                    $valor_propiedad = "<input size=7 type='text' id ='" . $color_id . "' value='" . $propiedad[1] . "'>";
                    $colors_id .= $color_id . '|';
                    break;
                case 'background-color':
                    $color_id = "col_" . ($canvas_count - 1) . "_" . ($identificador_count - 1) . "_" . $fila["id_elemento"];
                    $texto_propiedad = "Color Fondo";
                    $valor_propiedad = "<input size=7 type='text' id ='" . $color_id . "' value='" . $propiedad[1] . "'>";
                    $colors_id .= $color_id . '|';
                    break;
                case 'font-size':
                    $sel_id = "sel_" . ($canvas_count - 1) . "_" . ($identificador_count - 1) . "_" . $fila["id_elemento"];
                    $sizes = '9,10,12,14,16,18,24,36,58';
                    $arr_sizes = explode(",", $sizes);
                    $valor_propiedad = "<select style='width:68px' id='" . $sel_id . "' onchange='ch_val(" . ($canvas_count - 1) . "," . ($identificador_count - 1) . ", this.value)'>";
                    foreach ($arr_sizes as $siz) {
                        $sel = "";
                        if ($siz . "px" == $propiedad[1]) {
                            $sel = "selected";
                        }
                        $valor_propiedad .= "<option value='" . $siz . "px' " . $sel . ">" . $siz . "px</option>";
                    }
                    $valor_propiedad .= "</select>";
                    $texto_propiedad = "Tama�o Fuente";
                    break;
                case 'font-style':
                    $sel_id = "sel_" . ($canvas_count - 1) . "_" . ($identificador_count - 1) . "_" . $fila["id_elemento"];
                    $styles = 'inherit,italic,normal,oblique';
                    $arr_styles = explode(",", $styles);
                    $valor_propiedad = "<select style='width:68px' id='" . $sel_id . "' onchange='ch_val(" . ($canvas_count - 1) . "," . ($identificador_count - 1) . ", this.value)'>";
                    foreach ($arr_styles as $sty) {
                        $sel = "";
                        if ($sty == $propiedad[1]) {
                            $sel = "selected";
                        }
                        $valor_propiedad .= "<option value='" . $sty . "' " . $sel . ">" . $sty . "</option>";
                    }
                    $valor_propiedad .= "</select>";
                    $texto_propiedad = "Estilo Fuente";
                    break;
                case 'text-decoration':
                    $fw_id = "td_" . ($canvas_count - 1) . "_" . ($identificador_count - 1) . "_" . $fila["id_elemento"];
                    $decoration = 'none,blink,inherit,line-through,overline,underline';
                    $texto_propiedad = "Decoraci�n";
                    $arr_decoration = explode(",", $decoration);
                    $valor_propiedad = "<select style='width:68px' id='" . $fw_id . "' onchange='ch_val(" . ($canvas_count - 1) . "," . ($identificador_count - 1) . ", this.value)'>";
                    foreach ($arr_decoration as $deco) {
                        $sel = "";
                        if ($deco == $propiedad[1]) {
                            $sel = "selected";
                        }
                        $valor_propiedad .= "<option value='" . $deco . "' " . $sel . ">" . $deco . "</option>";
                    }
                    $valor_propiedad .= "</select>";
                    break;
                case 'font-weight':
                    $fw_id = "fw_" . ($canvas_count - 1) . "_" . ($identificador_count - 1) . "_" . $fila["id_elemento"];
                    $sizes = '100,200,300,400,500,600,700,800,900,bold,normal,inherit,lighter';
                    $texto_propiedad = "Ancho Fuente";
                    $arr_sizes = explode(",", $sizes);
                    $valor_propiedad = "<select style='width:68px' id='" . $fw_id . "' onchange='ch_val(" . ($canvas_count - 1) . "," . ($identificador_count - 1) . ", this.value)'>";
                    foreach ($arr_sizes as $siz) {
                        $sel = "";
                        if ($siz == $propiedad[1]) {
                            $sel = "selected";
                        }
                        $valor_propiedad .= "<option value='" . $siz . "' " . $sel . ">" . $siz . "</option>";
                    }
                    $valor_propiedad .= "</select>";
                    break;
                case 'text-align':
                    $texto_propiedad = "Alineaci�n";
                    $ta_id = "ta_" . ($canvas_count - 1) . "_" . ($identificador_count - 1) . "_" . $fila["id_elemento"];
                    $valor_propiedad = "<select style='width:68px' id='" . $ta_id . "' onchange='ch_val(" . ($canvas_count - 1) . "," . ($identificador_count - 1) . ", this.value)'>";
                    if ($propiedad[1] == 'left') {
                        $valor_propiedad .= "<option value='left' selected>Izquierda</option>";
                    } else {
                        $valor_propiedad .= "<option value='left'>Izquierda</option>";
                    }
                    if ($propiedad[1] == 'center') {
                        $valor_propiedad .= "<option value='center' selected>Centro</option>";
                    } else {
                        $valor_propiedad .= "<option value='center'>Centro</option>";
                    }
                    if ($propiedad[1] == 'right') {
                        $valor_propiedad .= "<option value='right' selected>Derecha</option>";
                    } else {
                        $valor_propiedad .= "<option value='right'>Derecha</option>";
                    }
                    $valor_propiedad .= "</select>";
                    break;
            }
            if ($texto_propiedad != '') {
                $html_resultado .= "<tr><td width='150px'>" . utf8_encode($texto_propiedad) . "</td><td width='71px'>" . $valor_propiedad . "</td></tr>";
                if ($listid != '') {
                    $listid .= '|';
                }
                $listid .=  $ta_id . $color_id . $sel_id . $fw_id;
            }
        }
        $html_resultado .= "<tr><td colspan=2></td></tr>";
        $html_resultado .= "</table>";
        $elements .= $fila["id_elemento"] . ";";
    }
    $jsondata["data"] = $html_resultado;
    $jsondata["elements"] = $elements;
    $jsondata["canvas"] = $canvas;
    $jsondata["canvas_property"] = $canvas_property;
    $jsondata["colors_id"] = $colors_id;
    $jsondata["listid"] = $listid;
    header('Content-type: application/json; charset=utf-8');
    echo json_encode($jsondata, JSON_FORCE_OBJECT);
}
