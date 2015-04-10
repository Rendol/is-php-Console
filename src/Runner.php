<?php
/**
 * Console Runner component
 *
 * @link http://infinity-systems.ru/
 */
namespace InfinitySystems\Console;

/**
 * Class ConsoleRunner
 * @package InfinitySystems\Console
 * @author Igor Sapegin aka Rendol <sapegin.in@gmail.com>
 */
class Runner
{
	/**
	 * @var string
	 */
	public $app;

	/**
	 * @var string
	 */
	public $php;

	/**
	 * Construct
	 * @param array $properties
	 */
	public function __construct($properties = [])
	{
		foreach ($properties as $name => $value) {
			$this->$name = $value;
		}
	}

	/**
	 * Запуск консольной комманды в двух режимах Wait/Background
	 *
	 * @param $cmd
	 * @param bool $background
	 * @return string
	 */
	public function run($cmd, $background = false)
	{
		$line = '';

		if ($this->isWindows()) {
			if ($background) {
				$line .= 'start ';
				//$line .= '/b ';
			}
		}

		$line .= $this->php . ' ' . $this->app . ' ';
		$line .= $cmd;

		if (!$this->isWindows() && $background) {
			if (!strpos($cmd, '>')) {
				$line .= ' > /dev/null ';
			}
			$line .= ' 2>/dev/null & ';
		}

		$out = array();
		if ($this->isWindows() && $background) {
			$handle = popen($line, 'r');
			if ($handle === FALSE) {
				die("Unable to execute $line");
			}
			pclose($handle);
		} else {
			exec($line, $out);
		}
		return implode(PHP_EOL, $out) . PHP_EOL;
	}

	/**
	 * Function to check operating system
	 *
	 * @return bool
	 */
	public function isWindows()
	{
		if (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN')
			return true;
		else
			return false;
	}
}
