<?php
namespace Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

use Codeception\Exception\MalformedLocatorException;
use Codeception\Util\Locator;
use Facebook\WebDriver\Exception\InvalidSelectorException;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverElement;

class Acceptance extends \Codeception\Module
{

	/**
	 * Define custom actions here
	 * @param $selector
	 * @return number of elements
	 */
	public function getNumberOfElements($selector) {
		return count($this->matchVisible($selector));
	}

	/**
	 * @param $selector
	 * @return array
	 */
	protected function matchVisible($selector)
	{
		/** @var \Facebook\WebDriver\Remote\RemoteWebDriver $webDriver */
		$webDriver =  $this->getModule('WebDriver')->webDriver;
		$els = $this->match($webDriver, $selector);
		$nodes = array_filter(
			$els,
			function (WebDriverElement $el) {
				return $el->isDisplayed();
			}
		);
		return $nodes;
	}

	/**
	 * @param $page
	 * @param $selector
	 * @param bool $throwMalformed
	 * @return array
	 */
	protected function match($page, $selector, $throwMalformed = true)
	{
		if (is_array($selector)) {
			try {
				return $page->findElements($this->getStrictLocator($selector));
			} catch (InvalidSelectorException $e) {
				throw new MalformedLocatorException(key($selector) . ' => ' . reset($selector), "Strict locator");
			}
		}
		if ($selector instanceof WebDriverBy) {
			try {
				return $page->findElements($selector);
			} catch (InvalidSelectorException $e) {
				throw new MalformedLocatorException(sprintf("WebDriverBy::%s('%s')", $selector->getMechanism(), $selector->getValue()), 'WebDriver');
			}
		}
		$isValidLocator = false;
		$nodes = [];
		try {
			if (Locator::isID($selector)) {
				$isValidLocator = true;
				$nodes = $page->findElements(WebDriverBy::id(substr($selector, 1)));
			}
			if (empty($nodes) and Locator::isCSS($selector)) {
				$isValidLocator = true;
				$nodes = $page->findElements(WebDriverBy::cssSelector($selector));
			}
			if (empty($nodes) and Locator::isXPath($selector)) {
				$isValidLocator = true;
				$nodes = $page->findElements(WebDriverBy::xpath($selector));
			}
		} catch (InvalidSelectorException $e) {
			throw new MalformedLocatorException($selector);
		}
		if (!$isValidLocator and $throwMalformed) {
			throw new MalformedLocatorException($selector);
		}
		return $nodes;
	}

	/**
	 * @param array $by
	 * @return WebDriverBy
	 */
	protected function getStrictLocator(array $by)
	{
		$type = key($by);
		$locator = $by[$type];
		switch ($type) {
			case 'id':
				return WebDriverBy::id($locator);
			case 'name':
				return WebDriverBy::name($locator);
			case 'css':
				return WebDriverBy::cssSelector($locator);
			case 'xpath':
				return WebDriverBy::xpath($locator);
			case 'link':
				return WebDriverBy::linkText($locator);
			case 'class':
				return WebDriverBy::className($locator);
			default:
				throw new MalformedLocatorException(
					"$by => $locator",
					"Strict locator can be either xpath, css, id, link, class, name: "
				);
		}
	}

}
