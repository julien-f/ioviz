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

$results = $ioviz->getResults();
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">

    <title>IoViz</title>

    <script type="text/javascript" src="js/jquery.js"></script>

    <link href="css/bootstrap.css" rel="stylesheet" media="screen" />
    <!--<script src="js/bootstrap.js"></script>-->

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/bootstrap-responsive.css" rel="stylesheet">

    <link rel="stylesheet" href="css/font-awesome.css" />

    <link rel="stylesheet" href="css/flotr2.css" type="text/css" />
    <script type="text/javascript" src="js/flotr2.js"></script>

    <script type="text/javascript" src="js/ioviz.js"></script>
  </head>
  <body>
    <div class="container">
      <h1>IoViz</h1>
      <h2>Existing graphs</h2>
<?php
$n = count($results);
if ($n === 0)
{
	echo '<p class="text-warning">No graphs.</p>';
}
else
{
	echo  '<p><em>', $n, '</em> graphs.</p><ul>';
	foreach ($results as $result)
	{
		$title = ($result->group !== null)
			? $result->group->name.' - '
			: ''
			;

		$title .= $result->benchmark->name.' - ';

		$title .= $result->date->format('c');

		echo
			'<li><a href="graph.php?id=', $result->id, '">',
			htmlspecialchars($title),
			'</a> <a class="btn btn-mini btn-danger" title="Delete this result." href="delete.php?id=',
			$result->id, '"><i class="icon-remove"></i></a></li>';
	}
	echo '</ul>';
}
?>
      <h2>Import a new graph</h2>
      <form action="import.php" method="post" enctype="multipart/form-data">
        <label>Benchmark</label>
        <select name="benchmark">
<?php
$benchmarks = $ioviz->getBenchmarks();
foreach ($benchmarks as $benchmark)
{
	echo
		'<option value="', $benchmark->id, '">',
		htmlspecialchars($benchmark->name),
		'</option>';
}
?>
        </select>
        <label>IOZone result file</label>
        <div><input type="file" name="file" /></div>
        <input type="submit" value="Import" />
      </form>
    </div>
  </body>
</html>
