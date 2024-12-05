<?php

namespace App\Helpers;

use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Application;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;

class ModelHelper
{
    public static function getModels(string $namespace = ''): Collection
    {
        $models = collect(File::allFiles(app_path()))
            ->map(function ($item) {
                $path = $item->getRelativePathName();
                /** @var Application */
                $container = Container::getInstance();
                $class = sprintf('\%s%s',
                    $container->getNamespace(),
                    strtr(substr($path, 0, strrpos($path, '.')), '/', '\\'));

                return $class;
            })
            ->filter(function ($class) use ($namespace) {
                $valid = false;

                if (class_exists($class)) {
                    $reflection = new \ReflectionClass($class);
                    $valid = $reflection->isSubclassOf(Model::class) &&
                        ! $reflection->isAbstract();

                    if ($valid && $namespace) {
                        $valid = $reflection->getNamespaceName() === $namespace;
                    }
                }

                return $valid;
            });

        return $models->values();
    }
}
