<?php
declare(strict_types = 1);

namespace NaisCodingStandard\Sniffs\Commenting;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use SlevomatCodingStandard\Helpers\DocCommentHelper;
use SlevomatCodingStandard\Helpers\FunctionHelper;
use SlevomatCodingStandard\Helpers\ClassHelper;

use function sprintf;

/**
 * Class RequiredCommentsSniff - kontrola komentare u funkce pro CS
 */
class RequiredCommentsSniff implements Sniff
{
    public const CODE_COMMENT_REQUIRED = 'CommentRequired';

    /**
     * @return mixed[]
     */
    public function register() : array
    {
        return [
            T_FUNCTION,
            T_CLASS,
        ];
    }

    /**
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
     * @param \PHP_CodeSniffer\Files\File $phpcsFile
     * @param int $definitionPointer
     */
    public function process(File $phpcsFile, $definitionPointer) : void
    {
        $hasDocComment = DocCommentHelper::hasDocComment($phpcsFile, $definitionPointer);

        if ($hasDocComment === true) {
            return;
        }

        $token = $phpcsFile->getTokens()[$definitionPointer];
        $type = 'no_type';
        $name = 'no_name';

        if ($token['code'] === T_FUNCTION) {
            $name = FunctionHelper::getName($phpcsFile, $definitionPointer);
            $type = 'function';
        } elseif ($token['code'] === T_CLASS) {
            $name = ClassHelper::getName($phpcsFile, $definitionPointer);
            $type = 'class';
        }

        $phpcsFile->addError(
            sprintf('Documentation comment missing for %s - %s.', $type, $name),
            $definitionPointer,
            self::CODE_COMMENT_REQUIRED
        );
    }
}
