<?php
/**
 * CustomPHPCS_Sniffs_ControlStructures_AssignmentInConditionSniff.
 *
 * Checks that assignments in conditions are not used.
 *
 * @category PHP
 * @package  PHP_CodeSniffer
 * @author   Alexander Obuhovich <aik.bold@gmail.com>
 * @license  https://github.com/aik099/CodingStandard/blob/master/LICENSE BSD 3-Clause
 * @link     https://github.com/aik099/CodingStandard
 */

namespace CustomPHPCS\Sniffs\ControlStructures;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

class AssignmentInConditionSniff implements Sniff
{
    /**
     * A list of tokenizers this sniff supports.
     *
     * @var array
     */
    public $supportedTokenizers = ['PHP'];

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return [
            T_IF,
            T_ELSEIF,
        ];

    }//end register()


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param  File    $phpcsFile All the tokens found in the document.
     * @param  integer $stackPtr  The position of the current token in
     *                            the stack passed in $tokens.
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        if (isset($tokens[$stackPtr]['parenthesis_opener']) === false) {
            return;
        }

        $equalOperator = $phpcsFile->findNext(
            T_EQUAL,
            $tokens[$stackPtr]['parenthesis_opener'],
            $tokens[$stackPtr]['parenthesis_closer']
        );

        if ($equalOperator === false) {
            return;
        }

        $phpcsFile->addWarning(
            'Assignment in "' . $tokens[$stackPtr]['content'] . '" control structure may be a mistake',
            $equalOperator,
            'Warning'
        );

    }//end process()
}//end class
