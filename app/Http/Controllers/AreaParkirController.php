<?php

namespace App\Http\Controllers;

use App\Models\AreaParkir;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AreaParkirController extends Controller
{
    public function index()
    {
        $areaParkir = AreaParkir::orderBy('created_at', 'desc')->paginate(10);
        return view('area.index', compact('areaParkir'));
    }

    public function create()
    {
        return view('area.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_area' => 'required|string|max:100',
            'kapasitas' => 'required|integer|min:1',
            'jenis_kendaraan' => 'required|in:semua,Motor,Mobil',
            'status' => 'required|in:tersedia,penuh,hampir_penuh',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $area = AreaParkir::create($request->all());

        LogAktivitas::create([
            'user_id' => auth()->user()->id,
            'aktivitas' => 'Menambahkan area parkir: ' . $request->nama_area . ' (Kapasitas: ' . $request->kapasitas . ', Jenis: ' . $request->jenis_kendaraan . ')',
        ]);

        return redirect()->route('area.index')
            ->with('success', 'Area parkir berhasil ditambahkan!');
    }

    public function edit(AreaParkir $area)
    {
        return view('area.edit', compact('area'));
    }

    public function update(Request $request, AreaParkir $area)
    {
        $validator = Validator::make($request->all(), [
            'nama_area' => 'required|string|max:100',
            'kapasitas' => 'required|integer|min:1',
            'jenis_kendaraan' => 'required|in:semua,Motor,Mobil',
            'status' => 'required|in:tersedia,penuh,hampir_penuh',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $oldName = $area->nama_area;
        $area->update($request->all());

        LogAktivitas::create([
            'user_id' => auth()->user()->id,
            'aktivitas' => 'Mengupdate area parkir: ' . $oldName . ' -> ' . $request->nama_area,
        ]);

        return redirect()->route('area.index')
            ->with('success', 'Area parkir berhasil diperbarui!');
    }

    public function destroy(AreaParkir $area)
    {
        $nama = $area->nama_area;

        LogAktivitas::create([
            'user_id' => auth()->user()->id,
            'aktivitas' => 'Menghapus area parkir: ' . $nama,
        ]);

        $area->delete();

        return redirect()->route('area.index')
            ->with('success', 'Area parkir berhasil dihapus!');
    }
}