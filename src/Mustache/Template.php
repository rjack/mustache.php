<?php

/*
 * This file is part of Mustache.php.
 *
 * (c) 2012 Justin Hileman
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mustache;

/**
 * Abstract Mustache Template class.
 *
 * @abstract
 */
abstract class Template {

	/**
	 * @var \Mustache\Mustache
	 */
	protected $mustache;

	/**
	 * Mustache Template constructor.
	 *
	 * @param \Mustache\Mustache $mustache
	 */
	public function __construct(Mustache $mustache) {
		$this->mustache = $mustache;
	}

	/**
	 * Mustache Template instances can be treated as a function and rendered by simply calling them:
	 *
	 *     $m = new Mustache;
	 *     $tpl = $m->loadTemplate('Hello, {{ name }}!');
	 *     echo $tpl(array('name' => 'World')); // "Hello, World!"
	 *
	 * @see \Mustache\Template::render
	 *
	 * @param mixed $context Array or object rendering context (default: array())
	 *
	 * @return string Rendered template
	 */
	public function __invoke($context = array()) {
		return $this->render($context);
	}

	/**
	 * Render this template given the rendering context.
	 *
	 * @param mixed $context Array or object rendering context (default: array())
	 *
	 * @return string Rendered template
	 */
	public function render($context = array()) {
		return $this->renderInternal($this->prepareContextStack($context));
	}

	/**
	 * Internal rendering method implemented by Mustache Template concrete subclasses.
	 *
	 * This is where the magic happens :)
	 *
	 * @abstract
	 *
	 * @param \Mustache\Context $context
	 *
	 * @return string Rendered template
	 */
	abstract public function renderInternal(Context $context);

	/**
	 * Helper method to prepare the Context stack.
	 *
	 * Adds the Mustache HelperCollection to the stack's top context frame if helpers are present.
	 *
	 * @param mixed $context Optional first context frame (default: null)
	 *
	 * @return \Mustache\Context
	 */
	protected function prepareContextStack($context = null) {
		$stack = new Context;

		$helpers = $this->mustache->getHelpers();
		if (!$helpers->isEmpty()) {
			$stack->push($helpers);
		}

		if (!empty($context)) {
			$stack->push($context);
		}

		return $stack;
	}
}
