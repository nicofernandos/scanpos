@extends('layouts.admintemplate')
@section('title','Data Pelanggan')
@section('content')

<div class="card">
    <h5 class="card-header">Data Pelanggan</h5>
    <div class="card-body">
        <div class="table-responsive text-nowrap">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Pelanggan</th>
                        <th>No. HP</th>
                        <th>Alamat</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pelanggans as $pelanggan)
                        <tr>
                            <td>{{ $pelanggan->idpelanggan }}</td>
                            <td>{{ $pelanggan->namapelanggan }}</td>
                            <td>{{ $pelanggan->nohp }}</td>
                            <td>{{ $pelanggan->alamat }}</td>
                            <td>
                                <a href="{{ url('pelangganedit', $pelanggan->idpelanggan) }}" class="btn btn-sm btn-primary">
                                    <i class="bx bx-edit-alt"></i> Edit
                                </a>

                                <form action="{{ url('pelangganhapus/'.$pelanggan->idpelanggan) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus pelanggan ini?')">
                                        <i class="bx bx-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Belum ada data pelanggan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>


@endsection
@section('script')
@endsection