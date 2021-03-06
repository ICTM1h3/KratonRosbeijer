<?php

require_once 'dompdf/lib/html5lib/Parser.php';
// require_once './dompdf-master/lib/php-font-lib/src/FontLib/Autoloader.php';
// require_once './dompdf-master/lib/php-svg-lib/src/autoload.php';
require_once 'dompdf/src/Autoloader.php';
Dompdf\Autoloader::register();

use Dompdf\Dompdf;

ob_start();
include '0_insight_menu.php';
$html = ob_get_clean();
set_time_limit(300);
$dompdf = new Dompdf();
$dompdf->load_html($html);
$dompdf->render();
$dompdf->stream();