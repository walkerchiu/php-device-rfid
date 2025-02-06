<?php

namespace WalkerChiu\DeviceRFID;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use WalkerChiu\DeviceRFID\Models\Entities\Reader;
use WalkerChiu\DeviceRFID\Models\Entities\ReaderRegister;
use WalkerChiu\DeviceRFID\Models\Entities\Card;
use WalkerChiu\DeviceRFID\Models\Entities\Data;

class DataTest extends \Orchestra\Testbench\TestCase
{
    use RefreshDatabase;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__ .'/../migrations');
        $this->withFactories(__DIR__ .'/../../src/database/factories');
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
     * A basic functional test on Data.
     *
     * For WalkerChiu\DeviceRFID\Models\Entities\Data
     * 
     * @return void
     */
    public function testData()
    {
        // Config
        Config::set('wk-core.onoff.core-lang_core', 0);
        Config::set('wk-device-rfid.onoff.core-lang_core', 0);
        Config::set('wk-core.lang_log', 1);
        Config::set('wk-device-rfid.lang_log', 1);
        Config::set('wk-core.soft_delete', 1);
        Config::set('wk-device-rfid.soft_delete', 1);

        // Give
        $db_reader = factory(Reader::class)->create();
        $db_register = factory(ReaderRegister::class)->create(['reader_id' => $db_reader->id]);
        $db_card = factory(Card::class)->create(['reader_id' => $db_reader->id]);
        $db_morph_1 = factory(Data::class)->create(['reader_id' => $db_reader->id, 'register_id' => $db_register->id, 'card_id' => $db_card->id]);
        $db_morph_2 = factory(Data::class)->create(['reader_id' => $db_reader->id, 'register_id' => $db_register->id, 'card_id' => $db_card->id]);
        $db_morph_3 = factory(Data::class)->create(['reader_id' => $db_reader->id, 'register_id' => $db_register->id, 'card_id' => $db_card->id]);

        // Get records after creation
            // When
            $records = Data::all();
            // Then
            $this->assertCount(3, $records);

        // Delete someone
            // When
            $db_morph_2->delete();
            $records = Data::all();
            // Then
            $this->assertCount(2, $records);

        // Resotre someone
            // When
            Data::withTrashed()
                ->find($db_morph_2->id)
                ->restore();
            $record_2 = Data::find($db_morph_2->id);
            $records = Data::all();
            // Then
            $this->assertNotNull($record_2);
            $this->assertCount(3, $records);
    }
}
