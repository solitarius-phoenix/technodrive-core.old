<?php

namespace Technodrive\Core\Traits;

trait FileTrait
{

    protected function scanDir(string $path, array $excludes=[]): array
    {
        $dir = scanDir($path);
        $excludes = array_keys(array_flip(array_merge(['.', '..'], $excludes)));
        $modules = [];
        foreach ($dir as $fileName) {
            if(!in_array($fileName, $excludes)){
                $modules[] = $fileName;
            }
        }
        return $modules;
    }

}