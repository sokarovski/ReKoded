<?php

/**
 * =============================================================================
 * @file core/LibraryBuilder.php
 * 
 * Builds and caches list of libraries to be used in the autoloader
 * 
 */

namespace RE;

class RepositoryBuilder {
    
    
    function buildAndSave() {
        
        $repository = $this->buildFileList();
        $repository['classes'] = $this->extractClasses($repository['classes']);
        
        $output  = '<?php' . PHP_EOL;
        $output .= $this->buildPhpArray('classes', $repository['classes']);
        $output .= $this->buildPhpArray('views', $repository['views']);
        
        if (!file_exists(APP . 'cache/'))
            mkdir(APP.'cache/', 0777, true);
        
        file_put_contents(APP . 'cache/repository.php', $output);
        
        return $repository;
    }
    
    function buildPhpArray($repositoryName,$arr) {
        
        $str = '';
        
        $str = '$__repository[\''.$repositoryName.'\'] = array(' . PHP_EOL;
        foreach ($arr as $ck => $cf) {
            $str .= '	\'' . $ck . '\' => \'' . $cf . '\',' . PHP_EOL;
        }
        $str .= ');' . PHP_EOL;
        
        return $str;
    }


    private function buildFileList() {
        
        $files = array();
        
        $files['classes'] = array();
        $files['views'] = array();
        
        $this->scanDirectory($files, APP);
        $this->scanDirectory($files, CORE);
        
        return $files;
        
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
     * Scans a directory for libraries and returns them as an array
     * @param string $dir the directory that needs to be scanned
     * @return array the array with the libraries
     */
    private function scanDirectory(&$files, $dir) {
        
        $dir_files = scandir($dir);
        
        foreach ($dir_files as $file) {

            if ($file{0} == '.') continue;

            if (is_dir($dir . $file)) {
                
                $this->scanDirectory($files, $dir.$file.'/');
               
            } else {
                
                $parts = explode('.', $file);
                if (count($parts) >= 2 && array_pop($parts) == 'php') {
                    
                    if (array_pop($parts) == 'view') { 
                        $files['views'][$file] = $dir . $file;
                    } else {
                        $files['classes'][] = $dir . $file;
                    }
                    $files[ array_pop($parts) == 'view' ? 'views':'classes'][] = $dir . $file;
                    
                }
                
            }
        }
        
    }

}