<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CampaignController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dashboard.master-data.campaign.index', [
            'campaigns' => Campaign::latest()->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.master-data.campaign.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:100',
            'description' => 'required|max:255',
            'img' => 'required|file|max:1536|mimes:jpg,png,jpeg,svg'
        ]);

        $validatedData['uuid'] = Str::uuid()->toString();
        $validatedData['alt'] = $validatedData['description'];

        if ($request->file('img')) {
            $validatedData['img'] = $request->file('img')->store('campaign');
        }

        Campaign::create($validatedData);

        return redirect('/dashboard/master-data/campaign')->with('success', 'New campaign has been successfully created');
    }

    /**
     * Display the specified resource.
     */
    public function show(Campaign $campaign)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Campaign $campaign)
    {
        return view('dashboard.master-data.campaign.edit', compact('campaign'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Campaign $campaign)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:100',
            'description' => 'required|max:255',
            'img' => 'file|max:1536|mimes:jpg,png,jpeg,svg'
        ]);
        
        $validatedData['alt'] = $validatedData['description'];

        if ($request->file('img')) {
            $validatedData['img'] = $request->file('img')->store('campaign');
            Storage::delete($campaign->img);
        }

        Campaign::where('id', $campaign->id)->update($validatedData);

        return redirect('/dashboard/master-data/campaign')->with('success', "Campaign: $campaign->name has been sucessfully updated");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Campaign $campaign)
    {
        Campaign::destroy($campaign->id);

        Storage::delete($campaign->img);

        return redirect('/dashboard/master-data/campaign')->with('success', "Campaign: $campaign->name has been sucessfully deleted");
    }

    public function statusUpdate(Campaign $campaign) {
        if ($campaign->status == 1) {
            Campaign::where('id', $campaign->id)->update(['status' => 0]);
        } else {
            Campaign::where('id', $campaign->id)->update(['status' => 1]);
        }

        return redirect('/dashboard/master-data/campaign')->with('success', "Status: $campaign->name has been sucessfully updated");
    }
}
