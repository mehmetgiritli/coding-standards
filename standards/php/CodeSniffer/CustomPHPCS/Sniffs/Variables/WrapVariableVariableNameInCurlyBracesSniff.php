<?php
/**
 * Require variable variables to be wrapped in curly braces.
 */

namespace CustomPHPCS\Sniffs\Variables;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

class WrapVariableVariableNameInCurlyBracesSniff implements Sniff {
    /**
     * @inheritDoc
     */
    public function register() {
        return [T_VARIABLE];
    }

    /**
     * @param  File    $phpcsFile
     * @param  integer $stackPtr The current token index.
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr) {
        $tokens        = $phpcsFile->getTokens();
        $previousToken = $phpcsFile->findPrevious(T_DOLLAR, $stackPtr - 1, null, true);

        if (!empty($previousToken)) {
            $previousContent = $tokens[$stackPtr - 1]['content'];

            if ($previousContent === '$') {
                $variable = $tokens[$stackPtr]['content'];

                $fix = $phpcsFile->addFixableError(
                    '$' . $variable . ' must be wrapped in curly braces e.g. ${' . $variable . '}',
                    $stackPtr,
                    'WrapInCurlyBraces'
                );

                if ($fix) {
                    $phpcsFile->fixer->replaceToken($stackPtr, '{' . $variable . '}');
                }
            }
        }
    }
}
