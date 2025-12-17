<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\Pekerjaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PegawaiController extends Controller
{
    public function index(Request $request) {
        $keyword = $request->get('keyword');
        $sort = in_array($request->get('sort'), ['created_at','updated_at']) ? $request->get('sort') : 'updated_at';
        $order = in_array($request->get('order'), ['asc','desc']) ? $request->get('order') : 'desc';

        $data = Pegawai::with('pekerjaan')
            ->when($keyword, function ($query) use ($keyword) {
                $query->where('nama', 'like', "%{$keyword}%")->orWhere('email', 'like', "%{$keyword}%");
            })->orderBy($sort, $order)->paginate(10)->withQueryString();

        return view('pegawai.index', compact('data'));
    }

    public function add() {
        $pekerjaan = Pekerjaan::all();
        return view('pegawai.add', compact('pekerjaan'));
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'pekerjaan_id' => 'required|exists:pekerjaan,id',
            'nama' => 'required|string',
            'email' => 'required|email|unique:pegawai,email',
            'gender' => 'required|in:male,female',
            'captcha' => 'required|captcha',
        ]);

        if ($validator->fails()) return redirect()->back()->withErrors($validator)->withInput();

        $data = new Pegawai();
        $data->pekerjaan_id = $request->pekerjaan_id;
        $data->nama = $request->nama;
        $data->email = $request->email;
        $data->gender = $request->gender;
        $data->is_active = $request->has('is_active') ? 1 : 0;

        if ($data->save()) {
            return redirect()->route('pegawai.index')->with('success', 'Data berhasil ditambahkan');
        }

        return redirect()->route('pegawai.index')->with('success', 'Data tidak tersimpan');
    }

    public function edit(Request $request) {
        $data = Pegawai::findOrFail($request->id);
        $pekerjaan = Pekerjaan::all();
        return view('pegawai.edit', compact('data', 'pekerjaan'));
    }

    public function update(Request $request) {
        $validator = Validator::make($request->all(), [
            'pekerjaan_id' => 'required|exists:pekerjaan,id',
            'nama' => 'required|string',
            'email' => 'required|email|unique:pegawai,email,' . $request->id,
            'gender' => 'required|in:male,female',
        ]);

        if ($validator->fails()) return redirect()->back()->withErrors($validator)->withInput();

        $data = Pegawai::findOrFail($request->id);
        $data->pekerjaan_id = $request->pekerjaan_id;
        $data->nama = $request->nama;
        $data->email = $request->email;
        $data->gender = $request->gender;
        $data->is_active = $request->has('is_active') ? 1 : 0;

        if ($data->save()) {
            return redirect()->route('pegawai.index')->with('success', 'Data tersimpan');
        }

        return redirect()->route('pegawai.index')->with('success', 'Data tidak tersimpan');
    }

    public function destroy(Request $request) {
        Pegawai::findOrFail($request->id)->delete();
        return redirect()->route('pegawai.index')->with('success', 'Data terhapus');
    }
}
