<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class InvoiceController extends Controller
{
    public function displayAllInvoice(){
        $invoices = Invoice::all();

        return View::make('invoices.index')
            ->with('invoices', $invoices);
    }
    public function index()
    {
        $user = Auth::user();
        $invoices = Invoice::where('user_id', $user->id)->get();

        return view('invoices.index', compact('invoices'));
    }

    public function show($id)
    {
        $invoice = Invoice::findOrFail($id);

        return view('invoices.show', compact('invoice'));
    }
}
