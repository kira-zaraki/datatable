<?php
namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\Country;
use App\Models\City;
use App\Models\User;

class DatatableService {

	public function create($model, $data)
    {
        /**
         * PUT more logique
         * */
        $country = DB::transaction(function () use ($data, $model) {
            return $model::create($data);

         });
        return $country;
    }

    public function update($country, $data)
    {
        /**
         * PUT more logique
         * */
        DB::transaction(function () use ($country, $data) {
                
            $country->update($data);

         });
        return $country;
    }

    public function toggleArchive($country)
    {
        /**
         * PUT more logique
         * */
        DB::transaction(function () use ($country) {
                
            $country->update(['archived' => !$country->archived]);

         });
        return $country;
    }
} 