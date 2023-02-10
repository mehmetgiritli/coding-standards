<?php

namespace CustomPHPCS\Sniffs\Usage;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Util\Tokens;

class ForbiddenFunctionsSniff implements Sniff {
    /**
     * Function => Replacement
     *
     * @var string[]
     */
    private $functions = [
        'join'            => 'implode',
        'create_function' => false,
        'dd'              => false,
        'die'             => false,
        'dump'            => false,
        'each'            => false,
        'exit'            => false,
        'mb_parse_str'    => false,
        'parse_str'       => false,
        'print_r'         => false,
        'var_dump'        => false,
    ];

    /**
     * Number of arguments to be forbidden
     *
     * @var integer[]
     */
    private $functionsArgCount = [
        'parse_str'    => 1,
        'mb_parse_str' => 1,
    ];

    /**
     * @inheritDoc
     */
    public function register() {
        return [T_STRING];
    }

    /**
     * @param  File    $phpcsFile
     * @param  integer $stackPtr The current token index.
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr) {
        $tokens = $phpcsFile->getTokens();

        // Check if the function is one of the bad ones
        $funcName = $tokens[$stackPtr]['content'];
        if (!isset($this->functions[$funcName])) {
            return;
        }

        $ignore = [
            T_DOUBLE_COLON    => true,
            T_OBJECT_OPERATOR => true,
            T_FUNCTION        => true,
            T_CONST           => true,
        ];

        // Check to make sure it's a PHP function (not $this->, etc.)
        $prevToken = $phpcsFile->findPrevious(T_WHITESPACE, ($stackPtr - 1), null, true);
        if (isset($ignore[$tokens[$prevToken]['code']])) {
            return;
        }
        $nextToken = $phpcsFile->findNext(Tokens::$emptyTokens, $stackPtr + 1, null, true);
        if ($tokens[$nextToken]['code'] !== T_OPEN_PARENTHESIS) {
            return;
        }

        // Check argument count
        if (isset($this->functionsArgCount[$funcName])) {
            $argCount = $this->argCount($phpcsFile, $nextToken);
            if ($argCount !== $this->functionsArgCount[$funcName]) {
                // Nothing to replace
                return;
            }
        }

        $replacement = $this->functions[$funcName];
        if ($replacement) {
            $fix = $phpcsFile->addFixableError(
                "Use $replacement() instead of {$funcName}",
                $stackPtr,
                $funcName
            );
            if ($fix) {
                $phpcsFile->fixer->replaceToken($stackPtr, $replacement);
            }
        } else {
            if (isset($this->functionsArgCount[$funcName])) {
                $phpcsFile->addError(
                    '%s should not be used with %s argument(s)',
                    $stackPtr,
                    $funcName,
                    [$funcName, $this->functionsArgCount[$funcName]]
                );
            } else {
                $phpcsFile->addError(
                    "{$funcName} should not be used",
                    $stackPtr,
                    $funcName
                );
            }
        }
    }

    /**
     * Return the number of arguments between the $parenthesis as opener and its closer
     * Ignoring commas between brackets to support nested argument lists
     *
     * @param  File    $phpcsFile
     * @param  integer $parenthesis The parenthesis token index.
     * @return integer
     */
    private function argCount(File $phpcsFile, $parenthesis) {
        $tokens = $phpcsFile->getTokens();
        if (!isset($tokens[$parenthesis]['parenthesis_closer'])) {
            return 0;
        }

        $end      = $tokens[$parenthesis]['parenthesis_closer'];
        $next     = $phpcsFile->findNext(Tokens::$emptyTokens, $parenthesis + 1, $end, true);
        $argCount = 0;

        if ($next !== false) {
            // Something found, there is at least one argument
            $argCount++;

            $searchTokens = [
                T_OPEN_CURLY_BRACKET,
                T_OPEN_SQUARE_BRACKET,
                T_OPEN_PARENTHESIS,
                T_COMMA,
            ];
            while ($next !== false) {
                switch ($tokens[$next]['code']) {
                    case T_OPEN_CURLY_BRACKET:
                    case T_OPEN_SQUARE_BRACKET:
                    case T_OPEN_PARENTHESIS:
                        if (isset($tokens[$next]['parenthesis_closer'])) {
                            // jump to closing parenthesis to ignore commas between opener and closer
                            $next = $tokens[$next]['parenthesis_closer'];
                        }
                        break;
                    case T_COMMA:
                        $argCount++;
                        break;
                }

                $next = $phpcsFile->findNext($searchTokens, $next + 1, $end);
            }
        }

        return $argCount;
    }
}
