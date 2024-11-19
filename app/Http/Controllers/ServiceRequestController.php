<?php

namespace App\Http\Controllers;

use App\Models\ServiceRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ServiceRequestController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(ServiceRequest::class, 'service');
    }

    public function index()
    {
        $this->authorize('viewAny', ServiceRequest::class);

        if (auth()->user()->isAdmin()) {
            $requests = ServiceRequest::with('user')
                ->latest()
                ->paginate(10);
        } else {
            $requests = ServiceRequest::where('user_id', auth()->id())
                ->latest()
                ->paginate(10);
        }

        return view('services.index', compact('requests'));
    }

    public function adminIndex()
    {
        $services = ServiceRequest::with('user')
            ->latest()
            ->paginate(10);

        return view('admin.services.index', compact('services'));
    }

    public function create()
    {
        $this->authorize('create', ServiceRequest::class);
        return view('services.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', ServiceRequest::class);

        $validated = $request->validate([
            'service_type' => 'required|string|in:installation,maintenance,repair,configuration',
            'description' => 'required|string|min:10',
            'preferred_date' => 'required|date|after:now',
            'address' => 'required|string|min:10'
        ]);

        $validated['status'] = 'pending';
        $validated['preferred_date'] = Carbon::parse($validated['preferred_date']);

        $serviceRequest = $request->user()->serviceRequests()->create($validated);

        return redirect()
            ->route('services.index')
            ->with('success', 'Solicitud de servicio creada exitosamente.');
    }

    public function show(ServiceRequest $service)
    {
        return view('admin.services.show', compact('service'));
    }

    public function updateStatus(Request $request, ServiceRequest $service)
    {
        $this->authorize('updateStatus', $service);

        $validated = $request->validate([
            'status' => 'required|in:pending,in_progress,completed',
            'notes' => 'nullable|string'
        ]);

        $service->update($validated);

        return back()->with('success', 'Estado actualizado correctamente.');
    }

    public function destroy(ServiceRequest $service)
    {
        $service->delete();
        return redirect()->route('admin.services.index')
            ->with('success', 'Servicio eliminado correctamente');
    }
}
