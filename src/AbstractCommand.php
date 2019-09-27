<?php

namespace Jnjxp\Bootstrap;

use ScssPhp\ScssPhp\Formatter\Crunched;

abstract class AbstractCommand
{

    protected function getRoot(string $root = null) : string
    {
        $default =  __DIR__ . '/../../../../';
        return realpath($root ?: $default);
    }

    protected function getConfig($root) : array
    {
        $config = [
            'root'        => $root,
            'source'      => $root . '/resources/style/style.scss',
            'destination' => $root . '/data/css/bootstrap.css',
            'format'      => Crunched::class
        ];

        $required = array_keys($config);

        $cfg = $root . '/config/bootstrap-css.php';
        if (file_exists($cfg)) {
            $config = array_merge($config, include $cfg);
        }

        foreach ($required as $require) {
            if (! is_string($config[$require])) {
                throw new \InvalidArgumentException('Invalid config key: ' . $require);
            }
        }

        return $config;
    }
}
