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
 * Checks that a field value has a given suffix.
 *
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class EndsWith extends Atom
{
    /**
     * @var string
     */
    private $acceptedSuffix;

    /**
     * Creates the criterion.
     *
     * @param string $fieldName      The field name.
     * @param string $acceptedSuffix The accepted suffix.
     */
    public function __construct($fieldName, $acceptedSuffix)
    {
        parent::__construct($fieldName);

        $this->acceptedSuffix = $acceptedSuffix;
    }

    /**
     * Returns the accepted suffix.
     *
     * @return string The accepted suffix.
     */
    public function getAcceptedSuffix()
    {
        return $this->acceptedSuffix;
    }

    /**
     * {@inheritdoc}
     */
    protected function matchValue($value)
    {
        return $this->acceptedSuffix === substr($value, -strlen($this->acceptedSuffix));
    }
}
