<?php

namespace Jnjxp\Bootstrap;

use ScssPhp\ScssPhp\Compiler;
use ScssPhp\ScssPhp\Formatter\Crunched;

class Compile extends AbstractCommand
{
    public function execute($root = null) : bool
    {
        $root = $this->getRoot($root);
        $config = $this->getConfig($root);
        $this->assertValidConfig($config);
        $compiler = $this->getCompiler($config);
        $this->output($config, $compiler);

        return true;
    }

    protected function assertValidConfig(array $config) : void
    {
        if (! is_file($config['source']) || ! is_readable($config['source'])) {
            throw new \InvalidArgumentException('Invalid Source: ' . $config['source']);
        }

        $dest = dirname($config['destination']);
        if (! is_dir($dest) || ! is_writable($dest)) {
            throw new \InvalidArgumentException('Invalid destination: ' . $config['destination']);
        }

        if (! class_exists($config['format'])) {
            throw new \InvalidArgumentException('Invalid format: ' . $config['format']);
        }
    }

    protected function getCompiler(array $config) : Compiler
    {
        $compiler = new Compiler();
        $compiler->setFormatter($config['format']);

        $compiler->setImportPaths(
            [
                dirname($config['source']),
                $config['root'] . '/vendor/twbs/bootstrap/scss/'
            ]
        );

        return $compiler;
    }

    protected function output(array $config, Compiler $compiler) : bool
    {
        return false !== file_put_contents(
            $config['destination'],
            $compiler->compile(file_get_contents($config['source']))
        );
    }
}
