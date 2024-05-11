<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Country;

use App\Services\DataListService;
use App\Services\DatatableService;
use App\Http\Requests\CountryFormRequest;

use App\Exports\CountriesExport;
use Maatwebsite\Excel\Facades\Excel;

class CountryController extends Controller
{ 

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('country.form');
    }

    public function store(CountryFormRequest $request)
    {
        $country = [];
        $data = $request->validated();

        try {
            $country = $this->datatableService->create(Country::class, $data);
        } catch (QueryException $e) {
            return redirect()->route('country.form', ['country' => $data])->with('error', 'Country add probleme');
        } 

        return redirect()->route('country.edit', $country)->with('success', 'Country added successfully!');
    }

    public function edit(Country $country)
    {
        return view('country.form', compact('country'));
    }

    public function update(CountryFormRequest $request, Country $country)
    {
        $data = $request->validated();

        try {
            $country = $this->datatableService->update($country ,$data);
        } catch (QueryException $e) {
            return redirect()->route('country.form', ['country' => $data])->with('error', 'Country update probleme');
        } 

        return redirect()->route('country.edit', $country)->with('success', 'Country updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Country $country)
    {
        try {
            $this->datatableService->toggleArchive($country); 
            return response()->json([
                    'status' => 'deleted',
                    'message' => 'country successfully deleted',
                ]);
        } catch (QueryException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'country deleting probleme',
                'errors' => $e->getMessage()
            ]);
        } 
    }

    public function countries(Request $request)
    {
        return response()->json($this->dataListService->GetDataList($request,Country::class, Country::query() ,['id', 'name', 'code', 'archived']));
    }

    public function citiesList(Country $country){
        return view('country.cities', compact('country'));
    }

    public function citiesByCountry(Country $country, Request $request){

        return response()->json($this->dataListService->GetDataList($request,$country, $country->cities() ,['id', 'name', 'latitude', 'longitude', 'postal_code', 'archived']));
    }

    public function export(string $form) 
    {
        if($form == 'excel')
            return Excel::download(new CountriesExport, 'countries.xlsx', \Maatwebsite\Excel\Excel::XLS);
        elseif($form == 'csv')
            return Excel::download(new CountriesExport, 'countries.csv', \Maatwebsite\Excel\Excel::CSV);
    }
    
}
