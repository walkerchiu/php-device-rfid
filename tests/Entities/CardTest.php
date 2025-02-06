<?php

namespace WalkerChiu\DeviceRFID;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use WalkerChiu\DeviceRFID\Models\Entities\Reader;
use WalkerChiu\DeviceRFID\Models\Entities\Card;
use WalkerChiu\DeviceRFID\Models\Entities\CardLang;

class CardTest extends \Orchestra\Testbench\TestCase
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
     * A basic functional test on Card.
     *
     * For WalkerChiu\DeviceRFID\Models\Entities\Card
     * 
     * @return void
     */
    public function testCard()
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
        $db_morph_1 = factory(Card::class)->create(['reader_id' => $db_reader->id]);
        $db_morph_2 = factory(Card::class)->create(['reader_id' => $db_reader->id]);
        $db_morph_3 = factory(Card::class)->create(['reader_id' => $db_reader->id, 'is_enabled' => 1]);

        // Get records after creation
            // When
            $records = Card::all();
            // Then
            $this->assertCount(3, $records);

        // Delete someone
            // When
            $db_morph_2->delete();
            $records = Card::all();
            // Then
            $this->assertCount(2, $records);

        // Resotre someone
            // When
            Card::withTrashed()
                ->find($db_morph_2->id)
                ->restore();
            $record_2 = Card::find($db_morph_2->id);
            $records = Card::all();
            // Then
            $this->assertNotNull($record_2);
            $this->assertCount(3, $records);

        // Return Lang class
            // When
            $class = $record_2->lang();
            // Then
            $this->assertEquals($class, CardLang::class);

        // Scope query on enabled records
            // When
            $records = Card::ofEnabled()
                           ->get();
            // Then
            $this->assertCount(1, $records);

        // Scope query on disabled records
            // When
            $records = Card::ofDisabled()
                           ->get();
            // Then
            $this->assertCount(2, $records);
    }
}
