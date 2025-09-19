@extends('layouts.admintemplate')
@section('title','Data Stok Tambahan')
@section('content')

<div class="card">
    <h5 class="card-header">Data Stok </h5>
    <div class="card-body">
        <div class="table-responsive text-nowrap">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Stok</th>
                        <th>Harga</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                        <tr>
                            <td>1</td>
                            <td>2</td>
                            <td>Rp. 100.000</td>
                            <td>
                                <a href="{{ url('tambahlayananedit/') }}" class="btn btn-sm btn-primary">
                                    <i class="bx bx-edit-alt"></i> Edit
                                </a>

                                <form action="{{ url('stokhapus/') }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus layanan ini?')">
                                        <i class="bx bx-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4" class="text-center">Belum ada data stok</td>
                        </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>


@endsection
@section('script')
@endsection