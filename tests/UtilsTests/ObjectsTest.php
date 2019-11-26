<?php declare (strict_types = 1);

namespace Wavevision\UtilsTests;

use PHPUnit\Framework\TestCase;
use stdClass;
use Wavevision\Utils\Objects;
use Wavevision\Utils\Tokenizer\Tokenizer;

/**
 * @covers \Wavevision\Utils\Objects
 */
class ObjectsTest extends TestCase
{

	public function testGetClassName(): void
	{
		$this->assertEquals('Tokenizer', Objects::getClassName(new Tokenizer()));
	}

	public function testGetIfNotNull(): void
	{
		$mock = $this->getMockBuilder(stdClass::class)
			->addMethods(['getYoMama'])
			->getMock();
		$mock->method('getYoMama')
			->willReturn('mama');
		$this->assertEquals('mama', Objects::getIfNotNull($mock, 'yoMama'));
		$this->assertEquals(null, Objects::getIfNotNull(null, 'yoMama'));
	}

	public function testGetNamespace(): void
	{
		$this->assertEquals('Wavevision\Utils', Objects::getNamespace(new Tokenizer()));
	}

	public function testHasGetter(): void
	{
		$this->assertTrue(
			Objects::hasGetter(
				$this->getMockBuilder(stdClass::class)
					->addMethods(['getSomething'])
					->getMock(),
				'something'
			)
		);
		$this->assertFalse(Objects::hasGetter(new stdClass(), 'something'));
	}

	public function testHasSetter(): void
	{
		$this->assertTrue(
			Objects::hasSetter(
				$this->getMockBuilder(stdClass::class)
					->addMethods(['setSomething'])
					->getMock(),
				'something'
			)
		);
		$this->assertFalse(Objects::hasSetter(new stdClass(), 'something'));
	}

	public function testSet(): void
	{
		$mock = $this->getMockBuilder(stdClass::class)
			->addMethods(['setYoMama'])
			->getMock();
		$mock->method('setYoMama')
			->willReturnSelf();
		$this->assertSame($mock, Objects::set($mock, 'yoMama', null));
	}

	public function testToArray(): void
	{
		$mock = $this->getMockBuilder(stdClass::class)
			->addMethods(['getYoMama', 'getYo'])
			->getMock();
		$mock->method('getYoMama')->willReturn('chewbacca');
		$mock->method('getYo')->willReturn('yo');
		$this->assertEquals(
			[
				'yoMama' => 'chewbacca',
				'yoPapa' => 'kenobi',
				'yo' => 'yo',
			],
			Objects::toArray(
				$mock,
				['yoMama'],
				[
					'yoPapa' => 'kenobi',
					'yo' => function ($yo) {
						return $yo;
					},
				]
			)
		);
	}

	public function testCopyAttributes(): void
	{
		$source = $this->getMockBuilder(stdClass::class)
			->addMethods(['getA'])
			->getMock();
		$source->expects($this->once())->method('getA')->willReturn('1');
		$target = $this->getMockBuilder(stdClass::class)
			->addMethods(['setA'])
			->getMock();
		$target->expects($this->once())->method('setA')->with('1');
		Objects::copyAttributes($source, $target, ['A']);
	}

}
