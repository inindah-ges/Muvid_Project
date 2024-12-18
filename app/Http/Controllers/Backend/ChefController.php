<?php

namespace App\Http\Controllers\Backend;

use App\Models\Backend\Chef;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Backend\ChefRequest;
use App\Http\Services\Backend\ChefService;

class ChefController extends Controller
{
    public function __construct(private ChefService $chefService) 
    {
        $this->middleware(function ($request, $next) {
            if ($request->user()->isOwner() && !in_array($request->routeIs('panel.chef.index', 'panel.chef.show'), ['panel.chef.index', 'panel.chef.show'])) {
                abort(403, 'Unauthorized action.');
            }

            if (!$request->user()->isOwner() && !$request->user()->isAdmin()) {
                abort(403, 'Unauthorized action.');
            }

            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('backend.chef.index', [
            'chefs' => $this->chefService->select(10),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.chef.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ChefRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('chefs', 'public');
        }

        try {
            Chef::create($data);

            return redirect()->route('panel.chef.index')->with('success', 'Chef successfully saved');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $uuid)
    {
        return view('backend.chef.show', [
            'chef' => $this->chefService->selectFirstBy('uuid', $uuid),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $uuid)
    {
        return view('backend.chef.edit', [
            'chef' => $this->chefService->selectFirstBy('uuid', $uuid),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ChefRequest $request, string $uuid)
    {
        $data = $request->validated();

        try {
            $chef = $this->chefService->selectFirstBy('uuid', $uuid);

            if ($request->hasFile('photo')) {
                if ($chef->photo) {
                    Storage::disk('public')->delete($chef->photo);
                }
                $data['photo'] = $request->file('photo')->store('chefs', 'public');
            }

            $chef->update($data);
            return redirect()->route('panel.chef.index')->with('success', 'Chef successfully updated');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $uuid)
    {
        $chef = $this->chefService->selectFirstBy('uuid', $uuid);

        if ($chef->photo) {
            Storage::disk('public')->delete($chef->photo);
        }

        $chef->delete();

        return response()->json([
            'message' => 'Chef successfully deleted',
        ]);
    }
}
