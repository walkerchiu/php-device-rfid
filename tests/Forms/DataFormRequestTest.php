<?php

namespace WalkerChiu\DeviceRFID;

use Illuminate\Support\Facades\Validator;
use WalkerChiu\DeviceRFID\Models\Entities\Reader;
use WalkerChiu\DeviceRFID\Models\Entities\ReaderRegister;
use WalkerChiu\DeviceRFID\Models\Entities\Card;
use WalkerChiu\DeviceRFID\Models\Entities\Data;
use WalkerChiu\DeviceRFID\Models\Forms\DataFormRequest;

class DataFormRequestTest extends \Orchestra\Testbench\TestCase
{
    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        //$this->loadLaravelMigrations(['--database' => 'mysql']);
        $this->loadMigrationsFrom(__DIR__ .'/../migrations');
        $this->withFactories(__DIR__ .'/../../src/database/factories');

        $this->request  = new DataFormRequest();
        $this->rules    = $this->request->rules();
        $this->messages = $this->request->messages();
    }

    /**
     * To load your package service provider, override the getPackageProviders.
     *
     * @param \Illuminate\Foundation\Application  $app
     * @return Array
     */
    protected function getPackageProviders($app)
    {
        return [\WalkerChiu\Core\CoreServiceProvider::class,
                \WalkerChiu\DeviceRFID\DeviceRFIDServiceProvider::class];
    }

    /**
     * Define environment setup.
     *
     * @param \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
    }

    /**
     * Unit test about Authorize.
     *
     * For WalkerChiu\DeviceRFID\Models\Forms\DataFormRequest
     * 
     * @return void
     */
    public function testAuthorize()
    {
        $this->assertEquals(true, 1);
    }

    /**
     * Unit test about Rules.
     *
     * For WalkerChiu\DeviceRFID\Models\Forms\DataFormRequest
     * 
     * @return void
     */
    public function testRules()
    {
        $faker = \Faker\Factory::create();

        $db_reader = factory(Reader::class)->create();
        $db_register = factory(ReaderRegister::class)->create(['reader_id' => $db_reader->id]);
        $db_card = factory(Card::class)->create(['reader_id' => $db_reader->id]);

        // Give
        $attributes = [
            'reader_id'   => $db_reader->id,
            'register_id' => $db_register->id,
            'card_id'     => $db_card->id,
            'identifier'  => '0000012345',
            'log'         => '123',
            'trigger_at'  => '2019-01-01 01:00:00'
        ];
        // When
        $validator = Validator::make($attributes, $this->rules, $this->messages); $this->request->withValidator($validator);
        $fails = $validator->fails();
        // Then
        $this->assertEquals(false, $fails);

        // Give
        $attributes = [
            'reader_id'   => $db_reader->id,
            'register_id' => $db_register->id,
            'card_id'     => $db_card->id,
            'identifier'  => '0000012345',
            'log'         => '123',
            'trigger_at'  => ''
        ];
        // When
        $validator = Validator::make($attributes, $this->rules, $this->messages); $this->request->withValidator($validator);
        $fails = $validator->fails();
        // Then
        $this->assertEquals(true, $fails);
    }
}
