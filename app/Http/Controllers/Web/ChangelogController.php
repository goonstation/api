<?php

namespace App\Http\Controllers\Web;

use App\Helpers\ChangelogHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ChangelogController extends Controller
{
    public function index(Request $request)
    {
        $this->setMeta(title: 'Changelog', description: 'All the recent game changes');

        return Inertia::render('Changelog/Index', [
            'changelog' => ChangelogHelper::get(),
        ]);
    }
}
