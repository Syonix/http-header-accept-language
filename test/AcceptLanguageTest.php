<?php

use PHPUnit\Framework\TestCase;
use Syonix\Http\Header\AcceptLanguage\AcceptLanguage;

class AcceptLanguageTest
	extends TestCase
{
	public function parseProvider()
	{
		return [
			['da, en-gb;q=0.8, en;q=0.7', ['da', 'en-gb', 'en']],
			['en;q=0.7, en-gb;q=0.8, da', ['da', 'en-gb', 'en']],
			['en;q=0.7, en-gb;q=0.8, da, *', ['da', 'en-gb', 'en', '*']],
			['*, en;q=0.9, en-US', ['en-US', 'en', '*']],
			['cc, aa, bb', ['cc', 'aa', 'bb']],
			['de-CH, fr;q=0.9, en;q=0.8, de;q=0.7, *;q=0.5', ['de-CH', 'fr', 'en', 'de', '*']],
		];
	}

	public function matchProvider()
	{
		return [
			['da, en-gb;q=0.8, en;q=0.7', null, 'da'],
			['en-gb;q=0.8, en;q=0.7', null, 'en-gb'],
			['en-us;q=0.8, en;q=0.7', null, 'en'],
			['de-CH;q=0.7, de-DE;q=0.8', null, 'de-CH'],
			['*', null, 'da'],
			['*', 'de-CH', 'de-CH'],
		];
	}

	/**
	 * @dataProvider parseProvider
	 *
	 * @param string $in
	 * @param array  $expected
	 */
	public function testParse(string $in, array $expected): void
	{
		$this->assertSame($expected, AcceptLanguage::parse($in));
	}

	/**
	 * @dataProvider matchProvider
	 *
	 * @param string $in
	 * @param        $default
	 * @param string $expected
	 */
	public function testMatch(string $in, $default, string $expected): void
	{
		$locales = ['da', 'en-gb', 'en', 'de-CH'];
		$this->assertSame($expected, AcceptLanguage::match($in, $locales, $default));

		$this->expectException(RuntimeException::class);
		$this->assertSame($expected, AcceptLanguage::match('fr-FR, fr', $locales));
	}
}
