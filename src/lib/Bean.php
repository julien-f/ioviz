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
abstract class Bean extends Base
{
	const T_BOOLEAN  = 1;
	const T_DATETIME = 2;
	const T_INTEGER  = 3;
	const T_STRING   = 4;

	const T_RELATION = 0;

	/**
	 *
	 */
	final function __construct(array $attrs = null, Manager $manager = null)
	{
		parent::__construct();

		$this->_attrs   = (array) $attrs;
		$this->_manager = $manager;

		$class = get_class($this);
		if (isset(self::$_props[$class]))
		{
			return;
		}

		self::$_props[$class] = call_user_func(array($class, '_initProperties'));
		foreach (self::$_props[$class] as $name => &$prop)
		{
			if (isset($prop['nullable']) && $prop['nullable'])
			{
				$prop['nullable'] = true;
			}
			else
			{
				unset($prop['nullable']);
			}

			if ($name === 'id')
			{
				trigger_error(
					'“id” is reserved to the uniq identifier',
					E_USER_ERROR
				);
			}

			if ($prop['type'] === self::T_RELATION)
			{
				if (!isset($prop['class']))
				{
					trigger_error(
						'“class” entry required for '.$class.'->'.$name,
						E_USER_ERROR
					);
				}

				self::$_props[$class][$name.'_id'] = array(
					'type' => self::T_INTEGER,
					'get'  => true,
					'set'  => true,
				);
				if (isset($prop['nullable']))
				{
					self::$_props[$class][$name.'_id']['nullable'] = true;
				}
			}
			elseif (isset($prop['class']))
			{
				trigger_error(
					'“class” entry is reserved for relations '.$class.'->'.$name,
					E_USER_ERROR
				);
			}

			$prop['get'] = $prop['set'] = true;
		}

		self::$_props[$class]['id'] = array(
			'type' => self::T_INTEGER,
			'get'  => true,
		);
	}

	/**
	 *
	 */
	final function __get($name)
	{
		// If already fetched, returns it.
		if (isset($this->_attrs[$name])
		    || array_key_exists($name, $this->_attrs))
		{
			return $this->_attrs[$name];
		}

		$class = get_class($this);

		// If no such property, fall backs on parent method.
		if (!isset(self::$_props[$class][$name]['get']))
		{
			return parent::__get($name);
		}

		// If it is a relation, gets the related object.
		if (self::$_props[$class][$name]['type'] === self::T_RELATION)
		{
			$class = self::$_props[$class][$name]['class'];

			$props = $this->_manager->search(
				strtolower($class),
				array('id' => $this->_attrs[$name.'_id']),
				1
			);
			$object = new $class(reset($props));

			return ($this->_attrs[$name] = $object);
		}

		trigger_error(
			'unset property '.$class.'->'.$name,
			E_USER_ERROR
		);
	}

	/**
	 *
	 */
	final function __set($name, $value)
	{
		$class = get_class($this);

		// If no such property, fall backs on parent method.
		if (!isset(self::$_props[$class][$name]['set']))
		{
			parent::__set($name, $value);
		}

		$type = self::$_props[$class][$name]['type'];

		if ($value === null)
		{
			if (!isset(self::$_props[$class][$name]['nullable']))
			{
				trigger_error(
					$class.'->'.$name.' is not nullable',
					E_USER_ERROR
				);
			}
		}
		elseif ($type === self::T_BOOLEAN)
		{
			$value = (boolean) $value;
		}
		elseif ($type === self::T_DATETIME)
		{
			$value = new DateTime(
				is_int($value)
				? '@'.$value
				: $value
			);
		}
		elseif ($type === self::T_INTEGER)
		{
			$value = (int) $value;
		}

		$this->_attrs[$name] = $value;
	}

	/**
	 *
	 */
	final function __isset($name)
	{
		Gallic::notImplemented();
	}

	/**
	 * @param Manager|null $manager
	 */
	final function setManager(Manager $manager = null)
	{
		$this->_manager = $manager;
	}

	/**
	 * @return Manager|null
	 */
	final function getManager()
	{
		return $this->_manager;
	}

	/**
	 *
	 */
	final function save(Manager $manager = null)
	{
		isset($manager)
			or $manager = $this->_manager;

		if (isset($this->_attrs['id']))
		{
			$manager->update(
				strtolower(get_class($this)),
				$this->_attrs,
				array('id' => $this->_attrs['id'])
			);
		}
		else
		{
			$manager->create(
				strtolower(get_class($this)),
				$this->_attrs
			);
		}
	}

	/**
	 * @var Manager|null
	 */
	private $_manager;

	/**
	 * @var array
	 */
	private $_attrs;

	/**
	 * @var array
	 */
	private static $_props;
}
