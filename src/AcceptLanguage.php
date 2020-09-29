<?php

namespace Syonix\Http\Header\AcceptLanguage;

use RuntimeException;

class AcceptLanguage
{
	const REGEX = '/([a-z*]{1,8}(?:-[a-z0-9]{1,8})?)(?:;q=([0-9].[0-9]+))?/i';

	/**
	 * @param string $in
	 *
	 * @return array
	 */
	public static function parse(string $in)
	{
		$locales = [];
		foreach (explode(',', $in) as $locale) {
			preg_match(self::REGEX, trim($locale), $matches);

			$q = $matches[2] ?? 1;
			$locale = $matches[1] ?? null;

			if ($locale === null || $locale === '*')
				continue;

			$locales[$q][$locale] = (float)$q;
		}

		// Sort, but keep order within same quality value
		ksort($locales);
		$locales = array_reverse($locales, true);

		$result = [];
		foreach ($locales as $locs)
			$result = array_merge($result, array_keys($locs));

		if (strpos($in, '*') !== false)
			$result[] = '*';

		return $result;
	}

	/**
	 * @param string $in
	 * @param array  $locales
	 * @param null   $default
	 *
	 * @return mixed|string|null
	 * @throws RuntimeException
	 */
	public static function match(string $in, array $locales, $default = null)
	{
		$matches = self::parse($in);

		$stems = [];
		foreach ($matches as $match)
			if (strpos($match, '-') !== false)
				$stems[] = substr($match, 0, strpos($match, '-'));

		$merged = array_unique(array_merge($matches, $stems));
		foreach ($merged as $match)
			foreach ($locales as $locale)
				if (strtolower($match) === strtolower($locale))
					return $locale;

		if (!in_array('*', $matches))
			throw new RuntimeException('Failed to match Content-Languages. Available values: ' . implode(', ', $locales));

		if ($default === null)
			$default = reset($locales);

		return $default;
	}
}
