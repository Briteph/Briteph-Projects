# Safe Eval

Safely evaluate code expressions in PHP, without using `eval`. [ExpressionLanguage](https://github.com/symfony/expression-language) is most likely better at this, but SafeEval still supports PHP 5, for the projects that need it.

## Usage
```php
use Layered\SafeEval\SafeEval;

$safeEval = new SafeEval;

echo $safeEval->eval('1 + 2');
var_dump($safeEval->eval('1 or 0'));
```
