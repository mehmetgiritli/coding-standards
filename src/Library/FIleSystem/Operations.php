<?php

namespace Library\FileSystem\Operations;

class Operations
{
    public static function removeFileDirectory($path)
    {
        if (file_exists($path)) {
            $dir = opendir($path);
            while (($file = readdir($dir)) !== false) {
                if (($file != '.') && ($file != '..')) {
                    $full = $path . '/' . $file;
                    if (is_dir($full)) {
                        self::removeFileDirectory($full);
                    } else {
                        unlink($full);
                    }
                }
            }

            closedir($dir);
            rmdir($path);
        }
    }
}
