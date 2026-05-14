<?php

if (!function_exists('useJsonFixture')) {
    function useJsonFixture($filename)
    {
        $directory = 'tests/Fixtures';
        $json = file_get_contents($directory . '/' . $filename);
        return json_decode($json, true);
    }
}
if (!function_exists('parameterize')) {
    function parameterize($string)
    {
        if (strpos($string, '+') !== false) {
            $string = str_replace(
                '+',
                'plus',
                strtolower(preg_replace('/[^a-zA-Z0-9 -]/', '', trim($string))),
            );
        } else {
            $string = strtolower(preg_replace('/[^a-zA-Z0-9 -]/', '', trim($string)));
        }

        $string = str_replace(' ', '-', $string);
        return $string;
    }
}
