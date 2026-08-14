<?php

namespace App\Http\Controllers;

use App\Http\Requests\PicRequest;
use App\Models\Pic;
use App\Services\PicService;
use Illuminate\Http\Request;

class PicController extends Controller
{
    protected $picService;

    public function __construct(PicService $picService)
    {
        $this->picService = $picService;
    }

    public function index(Request $request)
    {
        $data = $this->picService->getPicPageData($request);
        return view('pics', $data);
    }

    public function store(PicRequest $request)
    {
        try {
            $this->picService->createPic($request->validated());
            return redirect()->back()->with('success', 'PIC berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function update(PicRequest $request, Pic $pic)
    {
        try {
            $this->picService->updatePic($pic, $request->validated());
            return redirect()->back()->with('success', 'Data PIC berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function destroy(Pic $pic)
    {
        try {
            $this->picService->deletePic($pic);
            return redirect()->back()->with('success', 'Data PIC berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
