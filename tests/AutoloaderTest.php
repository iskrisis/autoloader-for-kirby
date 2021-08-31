<?php
declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Bnomei\Autoloader;
use PHPUnit\Framework\TestCase;

final class AutoloaderTest extends TestCase
{
    private $dir;

    public function setUp(): void
    {
        $this->dir = __DIR__ . '/site/plugins/example';
    }

    public function testSingleton()
    {
        // create
        $autoloader = Autoloader::singleton(['dir' => $this->dir]);
        $this->assertInstanceOf(Autoloader::class, $autoloader);

        // from static cached
        $autoloader = Autoloader::singleton(['dir' => $this->dir]);
        $this->assertInstanceOf(Autoloader::class, $autoloader);
    }

    public function testGlobalHelper()
    {
        $autoloader = autoloader($this->dir);
        $this->assertInstanceOf(Autoloader::class, $autoloader);
    }

    public function testBlueprints()
    {
        $autoloader = autoloader($this->dir);
        $blueprints = $autoloader->blueprints();

        $this->assertIsArray($blueprints);
        $this->assertArrayHasKey('files/touch', $blueprints);
        $this->assertArrayHasKey('pages/default', $blueprints);
        $this->assertArrayHasKey('pages/isphp', $blueprints);
        $this->assertArrayNotHasKey('page/isconf', $blueprints);
        $this->assertArrayHasKey('users/admin', $blueprints);
        $this->assertFileExists($blueprints['files/touch']);
        $this->assertFileExists($blueprints['pages/isphp']);
        $this->assertFileExists($blueprints['pages/default']);
        $this->assertFileExists($blueprints['users/admin']);
    }

    public function testCollections()
    {
        $autoloader = autoloader($this->dir);
        $collections = $autoloader->collections();

        $this->assertIsArray($collections);
        $this->assertArrayHasKey('colle', $collections);
        $this->assertIsCallable($collections['colle']);
    }

    public function testControllers()
    {
        $autoloader = autoloader($this->dir);
        $controllers = $autoloader->controllers();

        $this->assertIsArray($controllers);
        $this->assertArrayHasKey('default', $controllers);
        $this->assertIsCallable($controllers['default']);
    }

    public function testModels()
    {
        $autoloader = autoloader($this->dir);
        $models = $autoloader->models();

        $this->assertIsArray($models);
        $this->assertArrayHasKey('somepage', $models);
        $this->assertArrayHasKey('otherpage', $models);
        $this->assertTrue(class_exists('SomeName\\SomePage'));
        $this->assertTrue(class_exists('OtherPage'));
    }

    public function testSnippets()
    {
        $autoloader = autoloader($this->dir);
        $snippets = $autoloader->snippets();

        $this->assertIsArray($snippets);
        $this->assertArrayHasKey('snippet1', $snippets);
        $this->assertArrayNotHasKey('snippet1.config', $snippets);
        $this->assertArrayHasKey('sub/snippet2', $snippets);
        $this->assertFileExists($snippets['snippet1']);
        $this->assertFileExists($snippets['sub/snippet2']);
    }

    public function testTemplates()
    {
        $autoloader = autoloader($this->dir);
        $templates = $autoloader->templates();

        $this->assertIsArray($templates);
        $this->assertArrayHasKey('default', $templates);
        $this->assertArrayNotHasKey('default.blade', $templates);
        $this->assertFileExists($templates['default']);
    }
}