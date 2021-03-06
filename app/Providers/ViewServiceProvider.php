<?php

namespace GW2Heroes\Providers;

use GW2Heroes\Providers\View\BladeCompiler;
use Illuminate\View\Engines\CompilerEngine;
use Illuminate\View\ViewServiceProvider as ServiceProvider;

class ViewServiceProvider extends ServiceProvider {
    /**
     * Register our custom blade compiler instead of the default one.
     *
     * @param \Illuminate\View\Engines\EngineResolver $resolver
     */
    public function registerBladeEngine($resolver) {
        $app = $this->app;

        // The Compiler engine requires an instance of the CompilerInterface, which in
        // this case will be the Blade compiler, so we'll first create the compiler
        // instance to pass into the engine so it can compile the views properly.
        $app->singleton('blade.compiler', function ($app) {
            $cache = $app['config']['view.compiled'];

            return new BladeCompiler($app['files'], $cache);
        });

        $resolver->register('blade', function () use ($app) {
            return new CompilerEngine($app['blade.compiler'], $app['files']);
        });
    }

}
