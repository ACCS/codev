<?php // content="text/plain; charset=utf-8"

# WARNING: Never ever put an 'echo' in this file, the graph won't be displayed !

/*
    This file is part of CoDevTT.

    CoDevTT is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    CoDev-Timetracking is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with CoDevTT.  If not, see <http://www.gnu.org/licenses/>.
*/

   # WARN: this avoids the display of some PHP errors...
   error_reporting(E_ALL ^ E_NOTICE);
date_default_timezone_set('Europe/Paris');


require_once '../path.inc.php';

require_once "tools.php";
require_once "mysql_config.inc.php";
require_once "mysql_connect.inc.php";
require_once "internal_config.inc.php";
#include_once "constants.php";

require_once ('jpgraph.php');
require_once ('jpgraph_gantt.php');

require_once ('gantt_manager.class.php');


# ###########################"


$teamid = isset($_GET['teamid']) ? $_GET['teamid'] : 2;
$startT = isset($_GET['startT']) ? $_GET['startT'] : date2timestamp("2011-08-01");
$endT   = isset($_GET['endT']) ? $_GET['endT'] : date2timestamp("011-12-30");

/*
$teamid = 2;
$startT = date2timestamp("2011-08-01");
$endT   = date2timestamp("2011-12-30");
*/

$gantManager = new GanttManager($teamid, $startT, $endT);
$graph = $gantManager->getGanttGraph();

// display graph
$graph->Stroke();

?>