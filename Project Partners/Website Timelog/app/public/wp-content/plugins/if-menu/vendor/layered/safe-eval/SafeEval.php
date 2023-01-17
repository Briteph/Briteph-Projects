<?php
namespace Layered\SafeEval;

class SafeEval {

	protected $operators;

	public function __construct() {

		$this->operators = [
			'+'	=>	function($left, $right) {
				return $left + $right;
			},
			'-'	=>	function($left, $right) {
				return $left - $right;
			},
			'*'	=>	function($left, $right) {
				return $left * $right;
			},
			'/'	=>	function($left, $right) {
				return $left / $right;
			},
			'and'	=>	function($left, $right) {
				return (bool) $left && $right;
			},
			'or'	=>	function($left, $right) {
				return (bool) $left || $right;
			}
		];

	}

	public function evaluate($expression) {
		$tree = explode(' ', $expression);

		while (count($tree) > 1) {
			foreach ($tree as $i => $part) {
				if (isset($this->operators[$part])) {
					$tree[$i] = call_user_func($this->operators[$part], $tree[$i - 1], $tree[$i + 1]);
					unset($tree[$i - 1], $tree[$i + 1]);
					$tree = array_values($tree);
					break;
				}
			}
		}

		return array_shift($tree);
	}

}
