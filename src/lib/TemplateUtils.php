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
final class TemplateUtils
{
	static function url(Gallic_Template $tpl, array $parameters)
	{
		$url = $parameters['to'].'.php'; unset($parameters['to']);

		if ($parameters)
		{
			foreach ($parameters as $key => &$value)
			{
				$value = $key.'='.urlencode($value);
			}
			$url .= '?'.implode('&', $parameters);
		}
		return $url;
	}

	private function __construct()
	{}
}
