<?php

namespace App\Http\Controllers;

use App\Models\Treatment;
use App\Models\TreatmentDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

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

    public function create()
    {
        return view('dashboard.master-data.treatment.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:100',
            'type' => 'required',
            'photo' => 'required|image',
        ]);

        $validatedData['uuid'] = Str::uuid()->toString();
        $validatedData['photo'] = $request->file('photo')->store('treatment_photo');

        Treatment::create($validatedData);
        
        return redirect('/dashboard/master-data/treatment')->with('success', 'New treatment grouping has been successfully created');
    }

    /**
     * Display the specified resource.
     */
    public function show(Treatment $treatment)
    {
        $items = TreatmentDetail::where('treatment_id', $treatment->id)->get();
        return view('dashboard.master-data.treatment.show', compact(['treatment', 'items']));
    }

    /**
     * Edit the specified resource.
     */
    public function edit(Treatment $treatment)
    {
        return view('dashboard.master-data.treatment.edit', compact('treatment'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Treatment $treatment)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:100',
            'type' => 'required',
            'photo' => 'nullable|image',
        ]);
        
        if ($request->photo) {
            $validatedData['photo'] = $request->file('photo')->store('treatment_photo');
            if ($treatment->photo) {
                Storage::delete($treatment->photo);
            }
        }

        Treatment::where('id', $treatment->id)->update($validatedData);

        return redirect('/dashboard/master-data/treatment')->with('success', 'Treatment grouping has been successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Treatment $treatment)
    {
        if ($treatment->photo) {
            Storage::delete($treatment->photo);
        }
        Treatment::destroy($treatment->id);
        TreatmentDetail::where('treatment_id', $treatment->id)->delete();
        
        return redirect('/dashboard/master-data/treatment')->with('success', 'Treatment grouping has been successfully deleted');
    }

    public function storeDetail(Request $request, Treatment $treatment) {
        $validatedData = $request->validate([
            'name' => 'required|max:100',
            'cost' => 'required|numeric',
            'processing_time' => 'required|numeric',
            'description' => 'required|max:255',
            'suitable_for' => 'required|max:150',
        ]);

        $validatedData['treatment_id'] = $treatment->id;

        $validatedData['uuid'] = Str::uuid()->toString();

        TreatmentDetail::create($validatedData);

        return redirect("/dashboard/master-data/treatment/$treatment->uuid")->with('success', 'New treatment has been successfully created');
    }

    public function updateDetail(Request $request, Treatment $treatment, TreatmentDetail $detailTreatment) {
        $validatedData = $request->validate([
            'name' => 'required|max:100',
            'cost' => 'required|numeric',
            'processing_time' => 'required|numeric',
            'description' => 'required|max:255',
            'suitable_for' => 'required|max:150',
        ]);

        TreatmentDetail::where('id', $detailTreatment->id)->update($validatedData);

        return redirect("/dashboard/master-data/treatment/$treatment->uuid")->with('success', 'Treatment has been successfully updated');
    }

    public function destroyDetail(Treatment $treatment, TreatmentDetail $detailTreatment) {
        TreatmentDetail::destroy($detailTreatment->id);
        
        return redirect("/dashboard/master-data/treatment/$treatment->uuid")->with('success', 'Treatment has been successfully deleted');
    }
}
