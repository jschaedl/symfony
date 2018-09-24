<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Finder\Iterator;

use Symfony\Component\Finder\Glob;

/**
 * FilenameFilterIterator filters files by patterns (a regexp, a glob, or a string).
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class FilenameFilterIterator extends MultiplePcreFilterIterator
{
    private $isCaseSensitive;

    public function __construct(\Iterator $iterator, array $matchPatterns, array $noMatchPatterns, bool $isCaseSensitive = true)
    {
        $this->isCaseSensitive = $isCaseSensitive;

        parent::__construct($iterator, $matchPatterns, $noMatchPatterns);
    }

    /**
     * Filters the iterator values.
     *
     * @return bool true if the value should be kept, false otherwise
     */
    public function accept()
    {
        return $this->isAccepted($this->current()->getFilename());
    }

    /**
     * Converts glob to regexp.
     *
     * PCRE patterns are left unchanged.
     * Glob strings are transformed with Glob::toRegex().
     *
     * @param string $str Pattern: glob or regexp
     *
     * @return string regexp corresponding to a given glob or regexp
     */
    protected function toRegex($str)
    {
        return $this->isRegex($str) ? $str.($this->isCaseSensitive ? '' : 'i') : Glob::toRegex($str, true, true, '#', $this->isCaseSensitive);
    }
}
