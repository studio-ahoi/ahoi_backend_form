<?php
/**
 * Form element Element
 *
 * @version 1.0 rev 131227
 * @author Daniel Weitenauer
 * @copyright (c) 2013 studio ahoi
 */

namespace ahoi\Form\Elements;

interface ElementInterface
{
	public function __construct($id, $attributes);
	public function id();
	public function attributes();
	public function value();
	public function allowed();
	public function debug($debug);
	public function method($method = 'post');
	public function parse();
	public function parseAttributes();
	public function label();
	public function populate();
	public function post();
	public function get();
	public function submission();
	public function validate();
}
