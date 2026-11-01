<?php

namespace App\Http\Controllers;

use App\Models\TarifParkir;
use App\Models\AreaParkir;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TarifParkirController extends Controller
{
    public function index()
    {
        $tarifParkir = TarifParkir::with('areaParkir')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('tarif.index', compact('tarifParkir'));
    }

    public function create()
    {
        $areas = AreaParkir::all();
        return view('tarif.create', compact('areas'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'area_id' => 'required|exists:area_parkir,id',
            'jenis_kendaraan' => 'required|in:Motor,Mobil',
            'tarif' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $area = AreaParkir::find($request->area_id);
        TarifParkir::create($request->all());

        // Catat log
        LogAktivitas::create([
            'user_id' => auth()->user()->id,
            'aktivitas' => 'Menambahkan tarif parkir: ' . $area->nama_area . ' - ' . $request->jenis_kendaraan . ' Rp ' . number_format($request->tarif, 0, ',', '.'),
        ]);

        return redirect()->route('tarif.index')
            ->with('success', 'Tarif parkir berhasil ditambahkan!');
    }

    public function edit(TarifParkir $tarif)
    {
        $areas = AreaParkir::all();
        return view('tarif.edit', compact('tarif', 'areas'));
    }

    public function update(Request $request, TarifParkir $tarif)
    {
        $validator = Validator::make($request->all(), [
            'area_id' => 'required|exists:area_parkir,id',
            'jenis_kendaraan' => 'required|in:Motor,Mobil',
            'tarif' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $oldTarif = $tarif->tarif;
        $area = AreaParkir::find($request->area_id);
        $tarif->update($request->all());

        // Catat log
        LogAktivitas::create([
            'user_id' => auth()->user()->id,
            'aktivitas' => 'Mengupdate tarif parkir: ' . $area->nama_area . ' - ' . $request->jenis_kendaraan . ' Rp ' . number_format($oldTarif, 0, ',', '.') . ' -> Rp ' . number_format($request->tarif, 0, ',', '.'),
        ]);

        return redirect()->route('tarif.index')
            ->with('success', 'Tarif parkir berhasil diperbarui!');
    }

    public function destroy(TarifParkir $tarif)
    {
        $area = AreaParkir::find($tarif->area_id);
        $jenis = $tarif->jenis_kendaraan;

        // Catat log
        LogAktivitas::create([
            'user_id' => auth()->user()->id,
            'aktivitas' => 'Menghapus tarif parkir: ' . $area->nama_area . ' - ' . $jenis,
        ]);

        $tarif->delete();

        return redirect()->route('tarif.index')
            ->with('success', 'Tarif parkir berhasil dihapus!');
    }
}