<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Country;
use App\Models\City;

use App\Services\DataListService;
use App\Services\DatatableService;
use App\Http\Requests\CityFormRequest;

use App\Exports\CitiesExport;
use Maatwebsite\Excel\Facades\Excel;

class CityController extends Controller
{ 

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $countries = Country::all();
        return view('city.form', compact('countries'));
    }

    public function store(CityFormRequest $request)
    {
        $city = [];
        $countries = Country::all();
        $data = $request->validated();

        try {
            $city = $this->datatableService->create(City::class, $data);
        } catch (QueryException $e) {
            return redirect()->route('city.form', ['city' => $data, 'countries'=> $countries])->with('error', 'City add probleme');
        } 
        return redirect()->route('city.edit', compact('city', 'countries'))->with('success', 'City added successfully!');
    }

    public function edit(City $city)
    {
        $countries = Country::all();
        return view('city.form', compact('city', 'countries'));
    }

    public function update(CityFormRequest $request, City $city)
    {
        $data = $request->validated();

        try {
            $city = $this->datatableService->update($city ,$data);
        } catch (QueryException $e) {
            return redirect()->route('city.form', ['city' => $data])->with('error', 'City update probleme');
        } 

        return redirect()->route('city.edit', $city)->with('success', 'City updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(City $city)
    {
        try {
            $this->datatableService->toggleArchive($city); 
            return response()->json([
                    'status' => 'deleted',
                    'message' => 'city successfully deleted',
                ]);
        } catch (QueryException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'city deleting probleme',
                'errors' => $e->getMessage()
            ]);
        } 
    }

    public function usersList(City $city){
        return view('city.users', compact('city'));
    }

    public function usersByCity(City $city, Request $request){

        return response()->json($this->dataListService->GetDataList($request,$city, $city->users() ,['id', 'name', 'email', 'archived']));
    }

    public function export(string $form) 
    {
        if($form == 'excel')
            return Excel::download(new CitiesExport, 'cities.xlsx', \Maatwebsite\Excel\Excel::XLS);
        elseif($form == 'csv')
            return Excel::download(new CitiesExport, 'cities.csv', \Maatwebsite\Excel\Excel::CSV);
    }
}
