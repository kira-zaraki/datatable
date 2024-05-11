<?php
namespace App\Services;

use Illuminate\Support\Facades\DB;

class DataListService {

	public function GetDataList($request, $model, $query, $columns){
        $draw            = $request->get('draw');
        $start           = $request->get("start");
        $rowPerPage      = $request->get("length");

        $orderArray      = $request->get('order');
        $columnNameArray = $request->get('columns');                             
        $searchArray     = $request->get('search');
        $searchValue     =  $searchArray['value'];

        $total = $query->count();
        
        $reflection = new \ReflectionClass($model);
        $model = $reflection->getShortName();
        

        $query->when($searchValue, function($query) use ($searchValue, $columns){
            $query->where(function($query) use ($searchValue, $columns) {
                foreach ($columns as $column) {
                    $query->orWhereRaw('LOWER(' . $column . ') LIKE ?', ['%' . strtolower($searchValue) . '%']);
                }
            });
        });

        if($orderArray){

            $columnIndex     = $orderArray[0]['column'];  
            $columnName      = $columnNameArray[$columnIndex]['data'];  
            $columnSortOrder = $orderArray[0]['dir'];

            $query->orderBy($columnName,$columnSortOrder);
        }

        $filteredRecords = $query->count();

        $data = $query->skip($start)->take($rowPerPage)->select($columns)->get();

        $totalRecords = DB::table($model);

        return [
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data,
        ];
	}
} 