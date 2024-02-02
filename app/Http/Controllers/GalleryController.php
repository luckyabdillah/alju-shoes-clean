<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dashboard.master-data.gallery.index', [
            'galleries' => Gallery::latest()->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.master-data.gallery.create');
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
            $validatedData['img'] = $request->file('img')->store('gallery');
        }

        Gallery::create($validatedData);

        return redirect('/dashboard/master-data/gallery')->with('success', 'New gallery has been successfully created');
    }

    /**
     * Display the specified resource.
     */
    public function show(Gallery $gallery)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Gallery $gallery)
    {
        return view('dashboard.master-data.gallery.edit', compact('gallery'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Gallery $gallery)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:100',
            'description' => 'required|max:255',
            'img' => 'file|max:1536|mimes:jpg,png,jpeg,svg'
        ]);
        
        $validatedData['alt'] = $validatedData['description'];

        if ($request->file('img')) {
            $validatedData['img'] = $request->file('img')->store('gallery');
            Storage::delete($gallery->img);
        }

        Gallery::where('id', $gallery->id)->update($validatedData);

        return redirect('/dashboard/master-data/gallery')->with('success', "Gallery: $gallery->name has been sucessfully updated");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Gallery $gallery)
    {
        Gallery::destroy($gallery->id);

        Storage::delete($gallery->img);

        return redirect('/dashboard/master-data/gallery')->with('success', "Gallery: $gallery->name has been sucessfully deleted");
    }

    public function statusUpdate(Gallery $gallery) {
        if ($gallery->status == 1) {
            Gallery::where('id', $gallery->id)->update(['status' => 0]);
        } else {
            Gallery::where('id', $gallery->id)->update(['status' => 1]);
        }

        return redirect('/dashboard/master-data/gallery')->with('success', "Status: $gallery->name has been sucessfully updated");
    }
}
