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

/**
 *
 */
final class IoViz extends Base
{
	/**
	 *
	 */
	function __construct(DI $di)
	{
		parent::__construct();

		$this->_di = $di;
	}

	/**
	 *
	 */
	function getResult($id)
	{
		$manager = $this->_di->get('manager');

		$results = $manager->search(
			'result',
			array('id' => $id),
			1
		);

		if (empty($results))
		{
			throw new Exception('no such result');
		}

		return new Result(reset($results), $manager);
	}

	/**
	 *
	 */
	function saveResult($benchmark_id, $group_id, $data)
	{
		$result = new Result;
		$result->benchmark_id = $benchmark_id;
		$result->group_id     = $group_id;
		$result->date         = time();
		$result->data         = json_encode($this->_di->get('parser.iozone')->parse($data));
		$result->comment      = '';

		$result->save($this->_di->get('manager'));

		return $result;
	}

	/**
	 * @var DI
	 */
	private $_di;
}
