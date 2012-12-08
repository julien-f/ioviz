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

function create_graph(benchmark, title, ticks, data)
{
	'use strict';

	var container = $('<div class="flotr"></div>');
	container.appendTo($('#ioviz'));

	var ticks = [[0,'4'],[1,'8'],[2,'16'],[3,'32'],[4,'64'],[5,'128'],[6,'256'],[7,'512'],[8,'1024'],[9,'2048'],[10,'4096'],[11,'8192'],];

	var tmp = [];
	for (var run in data)
	{
		var tmp2 = [];
		for (var i in data[run])
		{
			tmp2.push([i, data[run][i] / 1024]);
		}

		tmp.push({
			'label': run,
			'data':  tmp2,
		});
	}
	data = tmp;

	Flotr.draw(container[0], data, {
		'xaxis': {
			'ticks': ticks,
		},
		'grid': {
			'verticalLines': true,
			'backgroundColor': {
				'colors': [[0, '#fff'],[1, '#eee']],
				'start': 'top',
				'end': 'bottom'
			}
		},
		'legend': {position: 'ne'},
		'spreadsheet': {show: true},
		'title': title,
		'subtitle': benchmark,
	});
}
