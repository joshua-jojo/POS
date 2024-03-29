<?php

namespace App\Http\Controllers\master;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $cari = $request->cari ?: null;
        $page = $request->showItem ?: 5;
        $supplier = Supplier::where('kode', 'like', "%" . $cari . "%")
            ->orWhere('nama', 'like', "%" . $cari . "%")
            ->orWhere('alamat', 'like', "%" . $cari . "%")
            ->orWhere('kontak', 'like', "%" . $cari . "%")
            ->orderBy('id', 'desc')
            ->paginate($page)
            ->withQueryString();
        return inertia()->render('master/supplier', [
            'supplier' => $supplier,
            'search' => $cari,
            'showItem' => $page,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'kontak' => 'required',
            'alamat' => 'required',
        ]);
        $supplier = Supplier::create($request->all());
        $supplier->kode = "P-" . sprintf("%05s", $supplier->id);
        $supplier->update();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function show(Supplier $supplier)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function edit(Supplier $supplier)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Supplier $supplier)
    {
        $request->validate([
            'nama' => 'required',
            'kontak' => 'required',
            'alamat' => 'required',
        ]);

        if ($request->nama != $supplier->nama) {
            $request->validate([
                'nama' => 'unique:suppliers'
            ]);
        }
        $supplier->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
    }
    public function print_all()
    {
        $supplier = Supplier::all();
        return inertia()->render('print/supplierPrint',compact('supplier'));
    }
}
