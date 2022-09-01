<?php

namespace Nwidart\Modules\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

class SymlinkMakeCommand extends Command
{

    /**
     * The name of argument name.
     *
     * @var string
     */
    protected $argumentName = 'name';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:make-symlink';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a symbolic link from "{module}/public" to "public/{module}" and add Storage symbolic link';

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['module', InputArgument::OPTIONAL, 'The name of module will be used.'],
        ];
    }

    /**
     * Get the module name.
     *
     * @return string
     */
    public function getModule()
    {
        $module = $this->argument('module') ?: app('modules')->getUsedNow();

        $module = app('modules')->findOrFail($module);

        return $module;
    }

    /**
     * Execute the console command.
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function handle()
    {
        $link = public_path($this->getModule()->getLowerName());
        $target = module_path($this->getModule()->getStudlyName(), '/public/');

        if (file_exists($link)) {
            $this->error('The "public/'.$this->getModule()->getLowerName().'" directory already exists.');
        } else {
            $this->laravel->make('files')->link($target, $link);
            $this->info('The [public/'.$this->getModule()->getLowerName().'] directory has been linked.');
        }

        $this->info('The link have been created.');
    }
}
