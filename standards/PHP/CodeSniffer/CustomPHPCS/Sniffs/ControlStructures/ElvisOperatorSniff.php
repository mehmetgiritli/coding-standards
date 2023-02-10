<?php
/**
 * Where applicable make use of the Elvis operator.
 */

namespace CustomPHPCS\Sniffs\ControlStructures;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

class ElvisOperatorSniff implements Sniff {
    /**
     * @inheritDoc
     */
    public function register() {
        return [T_INLINE_THEN];
    }

    /**
     * @param  File    $phpcsFile
     * @param  integer $stackPtr The current token index.
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr) {
        $tokens = $phpcsFile->getTokens();

        $contentBefore = [];
        $contentAfter  = [];

        $inlineElse = $phpcsFile->findNext(T_INLINE_ELSE, $stackPtr + 1);

        for ($i = $inlineElse - 1; $i > $stackPtr; $i--) {
            if (!in_array($tokens[$i]['type'], ['T_WHITESPACE', 'T_OPEN_PARENTHESIS', 'T_CLOSE_PARENTHESIS'])) {
                $contentAfter[$i] = $tokens[$i]['content'];
            } else {
                $contentAfter[$i] = null;
            }
        }

        for ($j = $stackPtr - 1; $j >= 0; $j--) {
            $tokenContent = $tokens[$j]['content'];
            $tokenType    = $tokens[$j]['type'];

            if ((in_array($tokenType, ['T_DOUBLE_ARROW', 'T_EQUAL', 'T_RETURN'])) || ($tokenType === 'T_WHITESPACE' && $tokenContent !== ' ')) {
                break;
            }

            if (!in_array($tokenType, ['T_WHITESPACE', 'T_OPEN_PARENTHESIS', 'T_CLOSE_PARENTHESIS'])) {
                $contentBefore[] = $tokenContent;
            }
        }

        if (array_values($contentBefore) === array_values(array_filter($contentAfter, 'strlen'))) {
            $fix = $phpcsFile->addFixableError(
                'Code has been found that should make use of the Elvis Operator (?:).',
                $stackPtr,
                'MakeUseOf'
            );

            if ($fix === true) {
                foreach (array_keys($contentAfter) as $token) {
                    $phpcsFile->fixer->replaceToken($token, '');
                }
            }
        }
    }
}
