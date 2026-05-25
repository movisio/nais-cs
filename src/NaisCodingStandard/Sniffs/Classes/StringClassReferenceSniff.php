<?php
declare(strict_types = 1);

namespace NaisCodingStandard\Sniffs\Classes;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * Kontrola třídy jako řetězce a náhrada za ::class.
 */
class StringClassReferenceSniff implements Sniff
{
    /**
     * @return array<int, int|string>
     */
    public function register() : array
    {
        return [T_CONSTANT_ENCAPSED_STRING];
    }

    /**
     * @param File $phpcsFile
     */
    public function process(File $phpcsFile, int $stackPtr) : void
    {
        $tokens = $phpcsFile->getTokens();
        if (!str_contains($tokens[$stackPtr]['content'], '\\')) {
            return;
        }

        // Remove quotes from string
        $className = str_replace(['"', "'"], '', $tokens[$stackPtr]['content']);
        if ($className === '\\') {
            return;
        }

        if (
            class_exists($className)
            || interface_exists($className)
            || trait_exists($className)
            || enum_exists($className)
        ) {
            $error = 'String "%s" contains class reference, use ::class instead';
            $data = [$className];
            $phpcsFile->addError($error, $stackPtr, 'Found', $data);
        }
    }
}
