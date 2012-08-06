<?php
/*
   This file is part of CoDev-Timetracking.

   CoDev-Timetracking is free software: you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation, either version 3 of the License, or
   (at your option) any later version.

   CoDev-Timetracking is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with CoDev-Timetracking.  If not, see <http://www.gnu.org/licenses/>.
*/

# WARN: this avoids the display of some PHP errors...
error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED);

date_default_timezone_set("Europe/Paris");

# WARN: order of these includes is important.
require_once('lib/log4php/Logger.php');
if (NULL == Logger::getConfigurationFile()) {
    Logger::configure(dirname(__FILE__).'/../log4php.xml');
    $logger = Logger::getLogger("header");
    $logger->info("LOG activated !");

    // test
    #echo "configure LOG ".Logger::getConfigurationFile()."</br>";
    #echo "configure LOG ".Logger::getConfigurationClass()."</br>";
    #echo "configure LOG header exists: ".$logger->exists("header")."</br>";
}

/**
 * handle uncaught exceptions
 * @param Exception $e
 */
function exception_handler(Exception $e) {
   global $logger;
   echo "<span style='color:red'>ERROR: Please contact your CodevTT administrator</span>";
   $logger->error("UNCAUGHT EXCEPTION : ".$e->getMessage());
   $logger->error("UNCAUGHT EXCEPTION stack-trace:\n".$e->getTraceAsString());
}
set_exception_handler('exception_handler');

include_once('include/mysql_config.inc.php');
include_once('classes/sqlwrapper.class.php');

$connection = SqlWrapper::createInstance($db_mantis_host, $db_mantis_user, $db_mantis_pass, $db_mantis_database);
$connection->sql_query('SET CHARACTER SET utf8');
$connection->sql_query('SET NAMES utf8');
$bugtracker_link = $connection->getLink();


?>
