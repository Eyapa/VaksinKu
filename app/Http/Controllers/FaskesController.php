<?php
namespace App\Http\Controllers;

use Illuminate\View\View;

class FaskesController extends Controller
{
    public function index(): View
    {
        return view('cari.index');
    }
}
