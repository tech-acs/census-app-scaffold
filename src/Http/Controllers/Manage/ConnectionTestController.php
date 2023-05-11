<?php

namespace Uneca\Scaffold\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use Uneca\Scaffold\Models\Source;
use Illuminate\Http\RedirectResponse;

class ConnectionTestController extends Controller
{
    public function __invoke(Source $source): RedirectResponse
    {
        $results = $source->test();
        $passesTest = $results->reduce(function ($carry, $item) {
            return $carry && $item['passes'];
        }, true);
        if ($passesTest) {
            return redirect()->route('developer.source.index')
                ->withMessage('Connection test successful');
        } else {
            return redirect()->route('developer.source.index')
                ->withErrors($results->pluck('message')->filter()->all());
        }
    }
}
