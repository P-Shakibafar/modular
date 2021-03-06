<?php

namespace InterNACHI\Modular\Tests\Concerns;

use InterNACHI\Modular\Console\Commands\Make\MakeModule;

trait TestsMakeCommands
{
	protected function assertModuleCommandResults(string $command, array $arguments, string $expected_path, array $expected_substrings)
	{
		$module_name = 'test-module';
		
		$this->artisan(MakeModule::class, [
			'name' => $module_name,
			'--accept-default-namespace' => true,
		])->assertExitCode(0);
		
		$this->artisan($command, array_merge([
			'--module' => $module_name,
		], $arguments))->assertExitCode(0);
		
		$full_path = $this->getModulePath($module_name, $expected_path);
		
		$this->assertFileExists($full_path);
		
		$contents = $this->filesystem()->get($full_path);
		
		foreach ($expected_substrings as $substring) {
			$this->assertStringContainsString($substring, $contents);
		}
	}
	
	protected function assertBaseCommandResults(string $command, array $arguments, string $expected_path, array $expected_substrings)
	{
		$this->artisan($command, $arguments)->assertExitCode(0);
		
		$full_path = $this->getBasePath().$this->normalizeDirectorySeparators($expected_path);
		
		$this->assertFileExists($full_path);
		
		$contents = $this->filesystem()->get($full_path);
		
		foreach ($expected_substrings as $substring) {
			$this->assertStringContainsString($substring, $contents);
		}
	}
}
