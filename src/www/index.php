<?php
/**
 * This file is a part of IoViz.
 *
 * IoViz is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * IoViz is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with IoViz. If not, see <http://www.gnu.org/licenses/>.
 *
 * @author Julien Fontanet <julien.fontanet@isonoe.net>
 * @license http://www.gnu.org/licenses/gpl-3.0-standalone.html GPLv3
 *
 * @package IoViz
 */

$ioviz = require(dirname(__FILE__).'/../bootstrap.php');

$result = $ioviz->getResult(2);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
  <head>
    <link rel="stylesheet" href="style.css" type="text/css" />
    <script type="text/javascript" src="js/flotr2.js"></script>
    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/ioviz.js"></script>
  </head>
  <body>
    <h1><?php echo htmlspecialchars($result->benchmark->name); ?></h1>
    <div id="ioviz"></div>
    <script type="text/javascript">
// <![CDATA[
<?php
$data = $result->getData();
$ticks = json_encode($data['Reclen']); unset($data['Reclen']);

echo "var ticks = $ticks;", PHP_EOL;

foreach ($data as $title => $data)
{
	$benchmark = json_encode($result->benchmark->name);
	$title     = json_encode($title);
	$data      = json_encode($data);

	echo "create_graph($benchmark, $title, ticks, $data);", PHP_EOL;
}
?>
// ]]>
    </script>
  </body>
</html>
