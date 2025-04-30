<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use Illuminate\Support\Facades\DB;

class ImportGeoData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:geo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import countries, states, and cities from JSON file';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Load the JSON file
        $json = Storage::get('countries+states+cities.json');
        $data = json_decode($json, true);

        // Begin a transaction for bulk insert
        DB::beginTransaction();

        try {
            // Create country records in bulk
            $countries = [];
            foreach ($data as $countryData) {
                $countries[] = [
                    'country_name' => $countryData['country_name'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            // Insert countries using insertOrIgnore to prevent duplicates
            Country::insertOrIgnore($countries);

            // After countries are inserted, get their ids
            $countryMap = Country::whereIn('country_name', array_column($countries, 'country_name'))
                ->pluck('id', 'country_name')
                ->toArray();

            // Prepare state and city records in bulk
            $states = [];
            $cities = [];

            foreach ($data as $countryData) {
                $countryId = $countryMap[$countryData['country_name']];

                foreach ($countryData['states'] as $stateData) {
                    // Prepare state data
                    $states[] = [
                        'country_id' => $countryId,
                        'state_name' => $stateData['state_name'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }

            // Insert states using insertOrIgnore
            State::insertOrIgnore($states);

            // After states are inserted, get their ids
            $stateMap = State::whereIn('state_name', array_column($states, 'state_name'))
                ->pluck('id', 'state_name')
                ->toArray();

            // Prepare city records and update their state_id
            foreach ($data as $countryData) {
                foreach ($countryData['states'] as $stateData) {
                    $stateId = $stateMap[$stateData['state_name']];

                    // Add cities with the correct state_id
                    foreach ($stateData['cities'] as $cityName) {
                        $cities[] = [
                            'state_id' => $stateId,
                            'city_name' => $cityName,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }
            }

            // Insert cities using insertOrIgnore to prevent duplicates
            City::insertOrIgnore($cities);

            // Commit the transaction
            DB::commit();

            $this->info('Geo data imported successfully.');

            return Command::SUCCESS;

        } catch (\Exception $e) {
            // Rollback if any error occurs
            DB::rollBack();
            $this->error('Error importing geo data: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
