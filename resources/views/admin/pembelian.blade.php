@extends('layouts.admintemplate')
@section('title','Data Pembelian')
@section('content')

<div class="card">
    <h5 class="card-header">Data Pembelian</h5>
    <div class="card-body">
        <div class="table-responsive text-nowrap">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No. Invoice</th>
                        <th>Nama</th>
                        <th>No. HP</th>
                        <th>Tanggal Pembelian</th>
                        <th>Checkout</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>01</td>
                        <td>Inv 012</td>
                        <td>Toyeb</td>
                        <td>08109201902112</td>
                        <td>03-01-2002</td>
                        <td>03-01-2002</td>
                        <td>
                            <a href="{{ url('/') }}" class="btn btn-sm btn-primary">
                                <i class="bx bx-edit-alt"></i> Edit
                            </a>

                            <form action="{{ url('/') }}" me    hod="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus layanan ini?')">
                                <i class="bx bx-trash"></i> Delete
                            </button>
                            </form>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@section('script')
@endsection
