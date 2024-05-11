<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\City;
use App\Http\Requests\UserFormRequest;
use App\Models\User;

use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cities = City::all();
        return view('user.form', compact('cities'));
    }

    public function store(UserFormRequest $request)
    {
        $user = [];
        $cities = City::all();
        $data = $request->validated();

        try {
            $user = $this->datatableService->create(User::class, $data);
        } catch (QueryException $e) {
            return redirect()->route('user.form', ['user' => $data, 'cities'=> $cities])->with('error', 'User add probleme');
        } 
        return redirect()->route('user.edit', compact('user', 'cities'))->with('success', 'User added successfully!');
    }

    public function edit(User $user)
    {
        $cities = City::all();
        return view('user.form', compact('user', 'cities'));
    }

    public function update(UserFormRequest $request, User $user)
    {
        $data = $request->validated();

        try {
            $user = $this->datatableService->update($user ,$data);
        } catch (QueryException $e) {
            return redirect()->route('user.form', ['user' => $data])->with('error', 'User update probleme');
        } 

        return redirect()->route('user.edit', $user)->with('success', 'User updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        try {
            $this->datatableService->toggleArchive($user); 
            return response()->json([
                    'status' => 'deleted',
                    'message' => 'User successfully deleted',
                ]);
        } catch (QueryException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'User deleting probleme',
                'errors' => $e->getMessage()
            ]);
        } 
    }

    public function export(string $form) 
    {
        if($form == 'excel')
            return Excel::download(new UsersExport, 'users.xlsx', \Maatwebsite\Excel\Excel::XLS);
        elseif($form == 'csv')
            return Excel::download(new UsersExport, 'users.csv', \Maatwebsite\Excel\Excel::CSV);
    }
}
