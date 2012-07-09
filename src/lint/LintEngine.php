<?php


class LintEngine extends ArcanistLintEngine {

    public function buildLinters() {
        $linters = array();

        $paths = $this->getPaths();

        $library_linter = new ArcanistPhutilLibraryLinter();
        $linters[] = $library_linter;

        foreach ($paths as $path) {
            $library_linter->addPath($path);
        }

        // Remaining linters operate on file contents and ignore removed files.
        foreach ($paths as $key => $path) {
            if (!$this->pathExists($path)) {
                unset($paths[$key]);
            }
            if (preg_match('@^externals/@', $path)) {
                // Third-party stuff lives in /externals/; don't run lint engines
                // against it.
                unset($paths[$key]);
            }
        }

        $generated_linter = new ArcanistGeneratedLinter();
        $linters[] = $generated_linter;

        $nolint_linter = new ArcanistNoLintLinter();
        $linters[] = $nolint_linter;

        $text_linter = new ArcanistTextLinter();
        $text_linter->setMaxLineLength(120);
        $text_linter->setCustomSeverityMap(
            array(
                ArcanistTextLinter::LINT_BAD_CHARSET
                => ArcanistLintSeverity::SEVERITY_DISABLED,
                ArcanistTextLinter::LINT_EOF_NEWLINE
                => ArcanistLintSeverity::SEVERITY_DISABLED,
                ArcanistTextLinter::LINT_LINE_WRAP
                => ArcanistLintSeverity::SEVERITY_DISABLED,
            ));
        $linters[] = $text_linter;

        $spelling_linter = new ArcanistSpellingLinter();
        $linters[] = $spelling_linter;
        foreach ($paths as $path) {
            $is_text = false;
            if (preg_match('/\.(php|js|hpp|cpp|l|y)$/', $path)) {
                $is_text = true;
            }
            if ($is_text) {
                $generated_linter->addPath($path);
                $generated_linter->addData($path, $this->loadData($path));

                $nolint_linter->addPath($path);
                $nolint_linter->addData($path, $this->loadData($path));

                $text_linter->addPath($path);
                $text_linter->addData($path, $this->loadData($path));

                $spelling_linter->addPath($path);
                $spelling_linter->addData($path, $this->loadData($path));
            }
        }

        $name_linter = new ArcanistFilenameLinter();
        $linters[] = $name_linter;
        foreach ($paths as $path) {
            $name_linter->addPath($path);
        }

        $xhpast_linter = new ArcanistXHPASTLinter();
        $xhpast_linter->setCustomSeverityMap(
            array(
                ArcanistXHPASTLinter::LINT_CLASS_FILENAME_MISMATCH
                => ArcanistLintSeverity::SEVERITY_DISABLED,
            ));
        $license_linter = new ArcanistApacheLicenseLinter();
        $linters[] = $xhpast_linter;
        $linters[] = $license_linter;
        foreach ($paths as $path) {
            if (preg_match('/\.php$/', $path)) {
                $xhpast_linter->addPath($path);
                $xhpast_linter->addData($path, $this->loadData($path));
            }
        }

        foreach ($paths as $path) {
            if (preg_match('/\.(php|cpp|hpp|l|y)$/', $path)) {
                if (!preg_match('@^externals/@', $path)) {
                    $license_linter->addPath($path);
                    $license_linter->addData($path, $this->loadData($path));
                }
            }
        }

        return $linters;
    }

}
