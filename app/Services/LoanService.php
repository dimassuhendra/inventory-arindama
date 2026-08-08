<?php

namespace App\Services;

use App\Models\ActivityLogs;
use App\Models\Production;
use App\Models\Products;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoanService
{
    public function getLoanPageData(Request $request): array
    {
        $search = $request->input('search');
        $statusFilter = $request->input('status');
        $perPage = $request->input('per_page', 10);

        // 1. Query Utama dengan Eager Loading Product
        $query = Production::with('product');

        // Filter Pencarian
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('borrower_name', 'like', "%{$search}%")
                    ->orWhere('borrower_contact', 'like', "%{$search}%")
                    ->orWhere('loan_code', 'like', "%{$search}%")
                    ->orWhereHas('product', function ($pQ) use ($search) {
                        $pQ->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Filter Status
        if ($statusFilter) {
            if ($statusFilter === 'overdue') {
                $query->where('status', 'borrowed')
                    ->whereDate('return_date', '<', Carbon::today());
            } else {
                $query->where('status', $statusFilter);
            }
        }

        $limit = $perPage === 'all' ? 10000 : (int) $perPage;
        $loans = $query->latest()->paginate($limit)->appends($request->all());

        // Mini Analytics Data
        $allLoans = Production::get();
        $totalBorrowedQty = $allLoans->where('status', 'borrowed')->sum('quantity');
        $activeLoansCount = $allLoans->where('status', 'borrowed')->count();
        $overdueLoansCount = $allLoans->where('status', 'borrowed')
            ->filter(fn($l) => Carbon::parse($l->return_date)->isPast() && !Carbon::parse($l->return_date)->isToday())
            ->count();
        $completedThisMonth = $allLoans->where('status', 'returned')
            ->filter(fn($l) => $l->actual_return_date && Carbon::parse($l->actual_return_date)->isCurrentMonth())
            ->count();

        // Product Options dengan stok > 0
        $products = Products::where('quantity', '>', 0)->orderBy('name', 'asc')->get();

        return [
            'loans' => $loans,
            'products' => $products,
            'total_borrowed_qty' => $totalBorrowedQty,
            'active_loans_count' => $activeLoansCount,
            'overdue_loans_count' => $overdueLoansCount,
            'completed_this_month' => $completedThisMonth,
        ];
    }

    public function createLoan(array $data): Production
    {
        return DB::transaction(function () use ($data) {
            $product = Products::findOrFail($data['product_id']);

            $loan = Production::create([
                'loan_code' => 'LNK-' . time(),
                'product_id' => $data['product_id'],
                'borrower_name' => $data['borrower_name'],
                'borrower_contact' => $data['borrower_contact'],
                'quantity' => $data['quantity'],
                'loan_date' => $data['loan_date'],
                'return_date' => $data['return_date'],
                'notes' => $data['notes'] ?? null,
                'status' => 'borrowed',
            ]);

            // Potong stok produk
            $product->decrement('quantity', $data['quantity']);

            // Activity Log Audit Trail
            ActivityLogs::create([
                'user_id' => auth()->id(),
                'activity' => "Mencatat peminjaman barang '{$product->name}' ({$data['quantity']} unit) kepada {$data['borrower_name']}",
                'model_type' => Production::class,
                'model_id' => $loan->id,
                'properties' => $loan->toArray(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            return $loan;
        });
    }

    public function returnLoanItem(int $id, ?string $returnNotes = null): Production
    {
        return DB::transaction(function () use ($id, $returnNotes) {
            $loan = Production::with('product')->findOrFail($id);

            if ($loan->status === 'returned') {
                throw new \Exception('Barang pada transaksi ini sudah dikembalikan sebelumnya.');
            }

            $loan->update([
                'status' => 'returned',
                'actual_return_date' => now(),
                'notes' => $returnNotes ? ($loan->notes ? $loan->notes . " | Catatan Kembali: " . $returnNotes : "Catatan Kembali: " . $returnNotes) : $loan->notes,
            ]);

            // Pulihkan stok produk
            $loan->product->increment('quantity', $loan->quantity);

            // Activity Log Audit Trail
            ActivityLogs::create([
                'user_id' => auth()->id(),
                'activity' => "Memproses pengembalian barang '{$loan->product->name}' ({$loan->quantity} unit) dari {$loan->borrower_name}",
                'model_type' => Production::class,
                'model_id' => $loan->id,
                'properties' => $loan->toArray(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            return $loan;
        });
    }
}
