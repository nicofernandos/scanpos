@extends('layouts.admintemplate')
@section('content')
    <!-- page content -->
    <div class="pcoded-content">
        <!-- Page-header start -->
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="page-header-title">
                            <h5 class="m-b-10">Detail Riwayat Pembelian {{ $namapelanggan }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="pcoded-inner-content">
            <div class="main-body">
                <div class="page-wrapper">
                    <div class="page-body">
                        <div class="card">
                            <div class="card-header">
                                <h5>Riwayat Pembelian</h5>
                                <div class="card-header-right">
                                    <ul class="list-unstyled card-option">
                                        <li><i class="fa fa fa-wrench open-card-option"></i></li>
                                        <li><i class="fa fa-window-maximize full-card"></i></li>
                                        <li><i class="fa fa-minus minimize-card"></i></li>
                                        <li><i class="fa fa-refresh reload-card"></i></li>
                                        <li><i class="fa fa-trash close-card"></i></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card-block table-border-style">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped" id="table">
                                        <thead> 
                                            <tr>
                                                <th class="text-center">No</th>
                                                <th class="text-center">No. Nota</th>
                                                <th class="text-center">Tanggal Penjualan</th>
                                                <th width="30%" class="text-center">Daftar Produk</th>
                                                <th class="text-center">Total Belanja</th>
                                                <th class="text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $totalpemasukan = 0;
                                            @endphp
                                            @foreach ($penjualan as $key => $value)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ $value->notajual }}</td>
                                                    <td>{{ date('d-m-Y', strtotime($value->tanggalpenjualan)) }}</td>
                                                    <td width="40%">
                                                        <table style="width: 100%;">
                                                            <tr>
                                                                <th>Produk</th>
                                                                <th>Jumlah</th>
                                                                <th>Harga</th>
                                                                <th>Total</th>
                                                            </tr>
                                                            @if (isset($penjualandetail[$value->notajual]))
                                                                @foreach ($penjualandetail[$value->notajual] as $val)
                                                                    <tr>
                                                                        <td width="40%">{{ $val->namabarang }}</td>
                                                                        <td width="10%">{{ $val->jumlah }}</td>
                                                                        <td width="30%">{{ rupiah($val->harga) }}</td>
                                                                        <td width="30%">{{ rupiah($val->total) }}</td>
                                                                    </tr>
                                                                @endforeach
                                                            @endif
                                                        </table>
                                                    </td>
                                                    <td>{{ rupiah($value->grandtotal) }}</td>
                                                    <td>
                                                        <a class="btn btn-success text-white mb-1" data-toggle="modal"
                                                            data-target="#detail{{ $key }}">Detail</a>
                                                        <a class="btn btn-info text-white mb-1"
                                                            href="{{ url('cetakfakturpenjualan/' . $value->notajual) }}"
                                                            target="_blank">Nota</a>
                                                    </td>
                                                </tr>
                                                @php
                                                    $totalpemasukan += $value->grandtotal;
                                                @endphp
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <th colspan="4" class="text-right"><em>Total Pemasukan :</em></th>
                                            <th colspan="3"><?= rupiah($totalpemasukan) ?></th>
                                        </tfoot>
                                    </table>
                                    @foreach ($penjualan as $key => $value)
                                        <div class="modal fade" id="detail{{ $key }}" tabindex="-1"
                                            role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Daftar Belanja: Nota
                                                            {{ $value->notajual }}</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <table class="table table-bordered table-striped">
                                                            <thead>
                                                                <tr>
                                                                    <th>No</th>
                                                                    <th>Nama Barang</th>
                                                                    <th>Harga</th>
                                                                    <th>Jumlah</th>
                                                                    <th>Total Harga</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($penjualandetail[$value->notajual] as $i => $val)
                                                                    <tr>
                                                                        <td>{{ $i + 1 }}</td>
                                                                        <td>{{ $val->namabarang }}</td>
                                                                        <td>{{ rupiah($val->harga) }}</td>
                                                                        <td>{{ $val->jumlah }}</td>
                                                                        <td>{{ rupiah($val->total) }}</td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Tutup</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach


                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endsection
