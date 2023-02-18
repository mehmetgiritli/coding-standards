<?php
/**
 * Ensure variable assignment occurs outside of an inline ternary condition.
 */

namespace CustomPHPCS\Sniffs\ControlStructures;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

class TernaryConditionVariableAssignmentSniff implements Sniff {
    const ASSIGNMENT_OPERATORS = [
        T_AND_EQUAL,
        T_CONCAT_EQUAL,
        T_DIV_EQUAL,
        T_EQUAL,
        T_MINUS_EQUAL,
        T_MOD_EQUAL,
        T_MUL_EQUAL,
        T_OR_EQUAL,
        T_PLUS_EQUAL,
        T_POW_EQUAL,
        T_SL_EQUAL,
        T_SR_EQUAL,
        T_XOR_EQUAL,
    ];

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

        $end     = null;
        $exclude = false;
        $value   = null;
        $local   = true;

        $inlineThenExpression = $phpcsFile->findNext(T_VARIABLE, $stackPtr + 1, $end, $exclude, $value, $local);

        if (!empty($inlineThenExpression)) {
            $inlineThenOperator = $phpcsFile->findNext(self::ASSIGNMENT_OPERATORS, $inlineThenExpression, $end, $exclude, $value, $local);
            $inlineThenVar      = $tokens[$inlineThenExpression]['content'];

            if (!empty($inlineThenOperator)) {
                $inlineElse = $phpcsFile->findNext(T_INLINE_ELSE, $inlineThenOperator, $end, $exclude, $value, $local);

                if (!empty($inlineElse)) {
                    $inlineElseExpression = $phpcsFile->findNext(T_VARIABLE, $inlineElse, $end, $exclude, $inlineThenVar, $local);

                    if (!empty($inlineElseExpression)) {
                        $inlineElseOperator = $phpcsFile->findNext(self::ASSIGNMENT_OPERATORS, $inlineElseExpression, $end, $exclude, $value, $local);

                        if (!empty($inlineElseOperator)) {
                            $phpcsFile->addError(
                                "The result of the inline condition should be assigned to {$inlineThenVar} outside of the ternary condition.",
                                $stackPtr,
                                'AssignVariableOutside'
                            );
                        }
                    }
                }
            }
        }
    }
}
