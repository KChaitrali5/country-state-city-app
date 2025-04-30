<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Models\Country;
use App\Models\State;
use App\Models\City;

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
        $json = Storage::get('countries+states+cities.json');
        $data = json_decode($json, true);

        foreach ($data as $countryData) {
            $country = Country::firstOrCreate(['country_name' => $countryData['country_name']]);

            $states = [];
            foreach ($countryData['states'] as $stateData) {
                $state = State::firstOrCreate([
                    'country_id' => $country->id,
                    'state_name' => $stateData['state_name']
                ]);

                $cities = [];
                foreach ($stateData['cities'] as $cityName) {
                    $cities[] = [
                        'state_id' => $state->id,
                        'city_name' => $cityName,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }

                City::insertOrIgnore($cities);
            }
        }

        $this->info('Geo data imported successfully.');

        return Command::SUCCESS;
    }
}
