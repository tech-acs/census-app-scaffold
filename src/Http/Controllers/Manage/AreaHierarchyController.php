<?php

namespace Uneca\Scaffold\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Uneca\Scaffold\Http\Requests\AreaHierarchyRequest;
use Uneca\Scaffold\Models\AreaHierarchy;
use Illuminate\Http\Request;

class AreaHierarchyController extends Controller
{
    public function index()
    {
        $records = AreaHierarchy::orderBy('index')->get();
        return view('scaffold::developer.area-hierarchy.index', compact('records'));
    }

    public function create()
    {
        return view('scaffold::developer.area-hierarchy.create');
    }

    public function store(AreaHierarchyRequest $request)
    {
        AreaHierarchy::create([
            'index' => AreaHierarchy::count(),
            'name' => $request->get('name'),
            'zero_pad_length' => $request->get('zero_pad_length'),
            'simplification_tolerance' => $request->get('simplification_tolerance'),
        ]);
        return redirect()->route('developer.area-hierarchy.index')->withMessage('Area hierarchy created');
    }

    public function edit(AreaHierarchy $areaHierarchy)
    {
        $areaHierarchy->zoom_start = min($areaHierarchy->map_zoom_levels ?? [6]);
        $areaHierarchy->zoom_end = max($areaHierarchy->map_zoom_levels ?? [6]);
        return view('scaffold::developer.area-hierarchy.edit', compact('areaHierarchy'));
    }

    public function update(AreaHierarchy $areaHierarchy, AreaHierarchyRequest $request)
    {
        $this->validateZoomRange($request);
        $zoomLevels = range($request->integer('zoom_start'), $request->integer('zoom_end'));
        $areaHierarchy->update($request->merge(['map_zoom_levels' => $zoomLevels])->only(['name', 'zero_pad_length', 'simplification_tolerance', 'map_zoom_levels']));
        return redirect()->route('developer.area-hierarchy.index')->withMessage('Area hierarchy updated');
    }

    public function destroy(AreaHierarchy $areaHierarchy)
    {
        $areaHierarchy->delete();
        return redirect()->route('developer.area-hierarchy.index')->withMessage('Area hierarchy deleted');
    }
}
