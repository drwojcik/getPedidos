<?php

namespace Doctrine\Tests\Common\Annotations\Fixtures;

/**
 *
 * @ignore Annotation("IgnoreAnnotationClass")
 */
class ClassWithIgnoreAnnotation {
	/**
	 * @IgnoreAnnotationClass
	 */
	public $foo;
}
