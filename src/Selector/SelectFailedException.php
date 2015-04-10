<?php

/*
 * This file is part of the webmozart/expression package.
 *
 * (c) Bernhard Schussek <bschussek@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Webmozart\Expression\Selector;

use RuntimeException;

/**
 * Thrown when {@link Selector::select()} fails.
 *
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class SelectFailedException extends RuntimeException
{
}
