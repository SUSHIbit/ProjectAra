<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::latest()->paginate(10);
        
        return view('manager.services.index', compact('services'));
    }
    
    public function create()
    {
        return view('manager.services.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'is_active' => 'nullable|boolean',
        ]);
        
        Service::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'is_active' => $request->is_active ?? true,
        ]);
        
        return redirect()->route('manager.services')
                         ->with('success', 'Service created successfully.');
    }
    
    public function edit(Service $service)
    {
        return view('manager.services.edit', compact('service'));
    }
    
    public function update(Request $request, Service $service)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'is_active' => 'nullable|boolean',
        ]);
        
        $service->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'is_active' => $request->is_active ?? false,
        ]);
        
        return redirect()->route('manager.services')
                         ->with('success', 'Service updated successfully.');
    }
    
    public function destroy(Service $service)
    {
        $service->delete();
        
        return redirect()->route('manager.services')
                         ->with('success', 'Service deleted successfully.');
    }
}