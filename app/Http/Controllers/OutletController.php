<?php

namespace App\Http\Controllers;

use App\Models\Outlet;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OutletController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dashboard.master-data.outlet.index', [
            'outlets' => Outlet::latest()->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.master-data.outlet.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'outlet_name' => 'required|max:100',
            'outlet_code' => 'required|max:100'
        ]);

        $validatedData['uuid'] = Str::uuid()->toString();

        Outlet::create($validatedData);

        return redirect('/dashboard/master-data/outlet')->with('success', 'New outlet has been successfully created');
    }

    /**
     * Display the specified resource.
     */
    public function show(Outlet $outlet)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Outlet $outlet)
    {
        return view('dashboard.master-data.outlet.edit', compact('outlet'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Outlet $outlet)
    {
        $validatedData = $request->validate([
            'outlet_name' => 'required|max:100',
            'outlet_code' => 'required|max:100'
        ]);

        Outlet::where('id', $outlet->id)->update($validatedData);

        return redirect('/dashboard/master-data/outlet')->with('success', "Outlet: $outlet->outlet_name has been successfully updated");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Outlet $outlet)
    {
        Outlet::destroy($outlet->id);

        return redirect('/dashboard/master-data/outlet')->with('success', "Outlet: $outlet->outlet_name has been sucessfully deleted");
    }

    public function statusUpdate(Outlet $outlet) {
        if ($outlet->status == 1) {
            Outlet::where('id', $outlet->id)->update(['status' => 0]);
        } else {
            Outlet::where('id', $outlet->id)->update(['status' => 1]);
        }

        return redirect('/dashboard/master-data/outlet')->with('success', "Status: $outlet->outlet_name has been sucessfully updated");
    }
}
