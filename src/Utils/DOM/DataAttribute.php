<?php declare (strict_types = 1);

namespace Wavevision\Utils\DOM;

use Nette\SmartObject;
use Nette\Utils\Html;
use Wavevision\Utils\Strings;

final class DataAttribute
{

	use SmartObject;

	private string $currentName;

	private string $currentValue;

	public function __construct(string $name, ?string $prefix = null)
	{
		$this->currentName = Strings::camelCaseToDashCase($prefix ? "data-$prefix-$name" : "data-$name");
		$this->currentValue = '';
	}

	public function __toString(): string
	{
		return $this->asString();
	}

	/**
	 * @param mixed $value
	 * @return array<string, string>
	 */
	public function asArray($value = null): array
	{
		return [$this->currentName => $this->value($value)];
	}

	/**
	 * @param Html<mixed> $element
	 * @return Html<mixed>
	 */
	public function assign(Html $element): Html
	{
		$element->setAttribute($this->currentName, $this->currentValue);
		return $element;
	}

	/**
	 * @param mixed $value
	 */
	public function asString($value = null): string
	{
		return sprintf('%s="%s"', $this->currentName, $this->value($value));
	}

	public function name(): string
	{
		return $this->currentName;
	}

	/**
	 * @param mixed $value
	 */
	public function value($value = null): string
	{
		if ($value !== null) {
			$this->currentValue = (string)$value;
		}
		return $this->currentValue;
	}

}
