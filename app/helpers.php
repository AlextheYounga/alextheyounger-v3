<?php

if (! function_exists('useJsonFixture')) {
    function useJsonFixture($filename) {
        $directory = 'tests/Fixtures';
        $json = file_get_contents($directory.'/'.$filename);
        return json_decode($json, true);
    }
}