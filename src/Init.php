<?php

namespace Jnjxp\Bootstrap;

use ScssPhp\ScssPhp\Formatter\Crunched;

class Init extends AbstractCommand
{
    protected $files = [
        'source' => ['style.scss', '_variables.scss'],
        'destination' => ['ignore' => '.gitignore']
    ];

    public function execute($root = null) : bool
    {
        $root = $this->getRoot($root);
        $config = $this->getConfig($root);

        $boilerplate = dirname(__DIR__) . '/resources/boilerplate';

        foreach ($this->files as $pkg => $files) {
            $destination = dirname($config[$pkg]);
            $this->mkdir($destination);
            $this->setup($boilerplate, $destination, $files);
        }

        return true;
    }

    protected function mkdir($dir)
    {
        if (is_dir($dir)) {
            echo "$dir exists\n";
            return;
        }
        echo "Creating $dir\n";
        return mkdir($dir, 0755, true);
    }

    protected function setup(string $boilerplate, string $location, array $files)
    {
        foreach ($files as $src => $dest) {
            $src = is_numeric($src) ? $dest : $src;
            $source = "$boilerplate/$src";
            $destination = "$location/$dest";
            if (! file_exists($destination)) {
                echo "Creating $destination\n";
                copy($source, $destination);
            } else {
                echo "$destination exists\n";
            }
        }
    }
}
