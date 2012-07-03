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
            case 'function':
            case 'method':
            case 'parameter':
            case 'member':
            case 'variable':
            default:
                return $default;
        }
    }
}
