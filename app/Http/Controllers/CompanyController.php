<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:companies,name',
            'code' => 'nullable|string|max:20|unique:companies,code',
        ], [
            'name.required' => 'Nama perusahaan wajib diisi.',
            'name.unique'   => 'Nama perusahaan sudah terdaftar.',
            'code.unique'   => 'Kode perusahaan sudah digunakan.',
        ]);

        Company::create($request->only('name', 'code'));

        return redirect()->back()->with('success', 'Perusahaan berhasil ditambahkan.');
    }

    public function update(Request $request, Company $company)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:companies,name,' . $company->id,
            'code' => 'nullable|string|max:20|unique:companies,code,' . $company->id,
        ], [
            'name.required' => 'Nama perusahaan wajib diisi.',
            'name.unique'   => 'Nama perusahaan sudah terdaftar.',
            'code.unique'   => 'Kode perusahaan sudah digunakan.',
        ]);

        $company->update($request->only('name', 'code'));

        return redirect()->back()->with('success', 'Perusahaan berhasil diperbarui.');
    }

    public function destroy(Company $company)
    {
        if ($company->departments()->count() > 0) {
            return redirect()->back()->with('error', 'Perusahaan tidak bisa dihapus karena masih memiliki departemen terikat.');
        }

        $company->delete();

        return redirect()->back()->with('success', 'Perusahaan berhasil dihapus.');
    }
}
