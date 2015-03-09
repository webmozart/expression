<?php

/*
 * This file is part of the webmozart/criteria package.
 *
 * (c) Bernhard Schussek <bschussek@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Webmozart\Criteria\Atom;

/**
 * Checks that a field value has a given prefix.
 *
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class StartsWith extends Atom
{
    /**
     * @var string
     */
    private $acceptedPrefix;

    /**
     * Creates the criterion.
     *
     * @param string $fieldName      The field name.
     * @param string $acceptedPrefix The accepted prefix.
     */
    public function __construct($fieldName, $acceptedPrefix)
    {
        parent::__construct($fieldName);

        $this->acceptedPrefix = $acceptedPrefix;
    }

    /**
     * Returns the accepted prefix.
     *
     * @return string The accepted prefix.
     */
    public function getAcceptedPrefix()
    {
        return $this->acceptedPrefix;
    }

    /**
     * {@inheritdoc}
     */
    protected function matchValue($value)
    {
        return 0 === strpos($value, $this->acceptedPrefix);
    }
}
