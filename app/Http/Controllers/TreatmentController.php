<?php

namespace App\Http\Controllers;

use App\Models\Treatment;
use App\Models\DetailTreatment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TreatmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dashboard.master-data.treatment.index', [
            'treatments' => Treatment::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:100'
        ]);

        $validatedData['uuid'] = Str::uuid()->toString();

        Treatment::create($validatedData);
        
        return redirect('/dashboard/master-data/treatment')->with('success', 'New treatment grouping has been successfully created');
    }

    /**
     * Display the specified resource.
     */
    public function show(Treatment $treatment)
    {
        $items = DetailTreatment::where('treatment_id', $treatment->id)->get();
        return view('dashboard.master-data.treatment.show', compact(['treatment', 'items']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Treatment $treatment)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:100'
        ]);

        Treatment::where('id', $treatment->id)->update($validatedData);

        return redirect('/dashboard/master-data/treatment')->with('success', 'Treatment grouping has been successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Treatment $treatment)
    {
        Treatment::destroy($treatment->id);
        DetailTreatment::where('treatment_id', $treatment->id)->delete();
        
        return redirect('/dashboard/master-data/treatment')->with('success', 'Treatment grouping has been successfully deleted');
    }

    public function storeDetail(Request $request, Treatment $treatment) {
        $validatedData = $request->validate([
            'name' => 'required|max:100',
            'cost' => 'required|numeric',
            'processing_time' => 'required|numeric'
        ]);

        $validatedData['treatment_id'] = $treatment->id;

        $validatedData['uuid'] = Str::uuid()->toString();

        DetailTreatment::create($validatedData);

        return redirect("/dashboard/master-data/treatment/$treatment->uuid")->with('success', 'New treatment has been successfully created');
    }

    public function updateDetail(Request $request, Treatment $treatment, DetailTreatment $detailTreatment) {
        $validatedData = $request->validate([
            'name' => 'required|max:100',
            'cost' => 'required|numeric',
            'processing_time' => 'required|numeric'
        ]);

        DetailTreatment::where('id', $detailTreatment->id)->update($validatedData);

        return redirect("/dashboard/master-data/treatment/$treatment->uuid")->with('success', 'Treatment has been successfully updated');
    }

    public function destroyDetail(Treatment $treatment, DetailTreatment $detailTreatment) {
        DetailTreatment::destroy($detailTreatment->id);
        
        return redirect("/dashboard/master-data/treatment/$treatment->uuid")->with('success', 'Treatment has been successfully deleted');
    }
}
