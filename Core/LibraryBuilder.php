<?php

/**
 * =============================================================================
 * @file core/LibraryBuilder.php
 * 
 * Builds and caches list of libraries to be used in the autoloader
 * 
 */
class LibraryBuilder {

    /**
     * Scans all folders for libraries and returns them as an array with the key
     * representing the name of the library and tha value representing the path
     * @return array The array with libraries 
     */
    private function build() {
        $libraries = array_merge(
                $this->scanDirectory(APP), 
                $this->scanDirectory(CORE)
        );
        return $libraries;
    }

    private function getPhpClasses($phpcode) {
        $classes = array();

        $namespace = 0;
        $tokens = token_get_all($phpcode);
        $count = count($tokens);
        $dlm = false;
        for ($i = 2; $i < $count; $i++) {
            if ((isset($tokens[$i - 2][1]) && ($tokens[$i - 2][1] == "phpnamespace" || $tokens[$i - 2][1] == "namespace")) ||
                    ($dlm && $tokens[$i - 1][0] == T_NS_SEPARATOR && $tokens[$i][0] == T_STRING)) {
                if (!$dlm)
                    $namespace = 0;
                if (isset($tokens[$i][1])) {
                    $namespace = $namespace ? $namespace . "\\" . $tokens[$i][1] : $tokens[$i][1];
                    $dlm = true;
                }
            } elseif ($dlm && ($tokens[$i][0] != T_NS_SEPARATOR) && ($tokens[$i][0] != T_STRING)) {
                $dlm = false;
            }
            if (($tokens[$i - 2][0] == T_CLASS || (isset($tokens[$i - 2][1]) && $tokens[$i - 2][1] == "phpclass"))
                    && $tokens[$i - 1][0] == T_WHITESPACE && $tokens[$i][0] == T_STRING) {
                $class_name = $tokens[$i][1];
                if (!isset($classes[$namespace]))
                    $classes[$namespace] = array();
                $classes[$namespace][] = $class_name;
            }
        }
        return $classes;
    }
    
    private function extractClasses($arr) {
        $res = array();
        foreach ($arr as $file) {
            $filens = $this->getPhpClasses(file_get_contents($file));
            foreach ($filens as $ns => $classes) {
                foreach ($classes as $class) {
                    $res[$ns ? $ns.'\\'.$class : $class] = $file;
                }
            }
        }
        return $res;
    }

    /**
     * Saves the cache file with the libraries
     * @return array The array with libraries
     */
    function buildAndSave() {
        $arr = $this->build();
        
        $carr = $this->extractClasses($arr);

        $str = '<?php' . PHP_EOL . '$libraries = array(' . PHP_EOL;
        foreach ($carr as $ck => $cf) {
            $str .= '	\'' . $ck . '\' => \'' . $cf . '\',' . PHP_EOL;
        }
        $str .= ');';
        file_put_contents(APP . 'cache/libraries.php', $str);
        return $carr;
    }

    /**
     * Scans a directory for libraries and returns them as an array
     * @param string $dir the directory that needs to be scanned
     * @return array the array with the libraries
     */
    private function scanDirectory($dir) {
        $arr = scandir($dir);
        $rarr = array();
        foreach ($arr as $f) {

            if ($f{0} == '.') {
                continue;
            }

            if (is_dir($dir . $f)) {
                $rarr += $this->scanDirectory($dir . $f . '/');
            } else {
                $fa = explode('.', $f);
                if (count($fa) == 2 && array_pop($fa) == 'php') {
                    $rarr[$dir . $f] = $dir . $f;
                }
            }
        }
        return $rarr;
    }

}