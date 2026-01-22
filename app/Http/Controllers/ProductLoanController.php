<?php
namespace App\Http\Controllers;

use App\Models\{Production, Products};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductLoanController extends Controller
{
    public function index()
    {
        $loans = Production::with('product')->latest()->paginate(10);
        $products = Products::where('quantity', '>', 0)->get();
        return view('loan', compact('loans', 'products'));
    }

    public function store(Request $request)
    {
        $product = Products::findOrFail($request->product_id);

        $request->validate([
            'product_id' => 'required',
            'quantity' => 'required|numeric|min:1|max:' . $product->quantity,
            'borrower_name' => 'required',
            'borrower_contact' => 'required',
            'loan_date' => 'required|date',
            'return_date' => 'required|date|after_or_equal:loan_date',
        ]);

        DB::transaction(function () use ($request, $product) {
            Production::create([
                'loan_code' => 'LNK-' . time(),
                'product_id' => $request->product_id,
                'borrower_name' => $request->borrower_name,
                'borrower_contact' => $request->borrower_contact,
                'quantity' => $request->quantity,
                'loan_date' => $request->loan_date,
                'return_date' => $request->return_date,
                'status' => 'borrowed'
            ]);

            $product->decrement('quantity', $request->quantity);
        });

        return back()->with('success', 'Peminjaman berhasil dicatat.');
    }

    public function returnItem($id)
    {
        $loan = Production::findOrFail($id);

        DB::transaction(function () use ($loan) {
            $loan->update([
                'status' => 'returned',
                'actual_return_date' => now()
            ]);

            $loan->product->increment('quantity', $loan->quantity);
        });

        return back()->with('success', 'Barang telah dikembalikan, stok pulih.');
    }
}