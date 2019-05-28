<?php
declare(strict_types = 1);

namespace NaisCodingStandard\Sniffs\Classes;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

/**
 * Class StringClassReferenceSniff - kontrola tridy jako stringu a jeji nahrazeni za ::class
 */
class StringClassReferenceSniff implements Sniff
{
    /**
     * @return array
     */
    public function register() : array
    {
        return [T_CONSTANT_ENCAPSED_STRING];
    }

    /**
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
     * @param File $phpcsFile
     * @param int $stackPtr
     */
    public function process(File $phpcsFile, $stackPtr) : void
    {
        $tokens = $phpcsFile->getTokens();
        if (strpos($tokens[$stackPtr]['content'], '\\') !== false) {
            // Remove quotes from string
            $className = str_replace(['"', "'"], '', $tokens[$stackPtr]['content']);
            if ($className === '\\') {
                return;
            }
            if (class_exists($className) || interface_exists($className) || trait_exists($className)) {
                $error = 'String "%s" contains class reference, use ::class instead';
                $data = [$className];
                $phpcsFile->addError($error, $stackPtr, 'Found', $data);
            }
        }
    }
}
