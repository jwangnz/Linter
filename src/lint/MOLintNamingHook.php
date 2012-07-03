<?php class MOLintNamingHook extends ArcanistXHPASTLintNamingHook
{
    private static function isUpperCamelCaseWithUnderscore($symbol) {
        $parts = explode("_", $symbol);
        foreach ($parts as $part) {
            if (! ArcanistXHPASTLintNamingHook::isUpperCamelCase($part)) {
                return false;
            }
        }
        return true;
    }

    public function lintSymbolName($type, $name, $default) {
        switch ($type) {
            case 'class':
            case 'interface':
                if (! self::isUpperCamelCaseWithUnderscore($name)) {
                    return 'Follow naming conventions: '.$type.' should be named using ' .
                        'UpperCase_With_UnderScores.';
                } else {
                    return null;
                }
                break;
            case 'constant':
                //if (! ArcanistXHPASTLintNamingHook::isUppercaseWithUnderscores($name)) {
                    //return 'Follow naming conventions: '.$type.' should be named using ' .
                        //'Uppercase_With_Underscores.';
                //} else {
                    //return null;
                //}
                //break;
            case 'function':
            case 'method':
                //$name = ltrim($name, '_');
                //if (! ArcanistXHPASTLintNamingHook::isLowerCamelCase($name)) {
                    //return 'Follow naming conventions: '.$type.' should be named using ' .
                        //'lowerCamelCase.';
                //} else {
                    //return null;
                //}
                //break;
            case 'parameter':
            case 'member':
            case 'variable':
                //if (! ArcanistXHPASTLintNamingHook::isLowerCamelCase(
                    //ArcanistXHPASTLintNamingHook::stripPHPVariable($name))) {
                    //return 'Follow naming conventions: '.$type.' should be named using ' .
                        //'lowerCamelCase.';
                //} else {
                    //return null;
                //}
                //break;
            default:
                return $default;
        }
    }
}
