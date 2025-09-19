@extends('layouts.admintemplate')
@section('title','Data Kategori')
@section('content')

<div class="card">
    <h5 class="card-header">Data Kategori</h5>
    <div class="card-body">
        <div class="table-responsive text-nowrap">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Kategori</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- @forelse($layanans as $layanan)
                        <tr>
                            <td>{{ $layanan->idlayanantambahan }}</td>
                            <td>{{ $layanan->namalayanantambahan }}</td>
                            <td>Rp {{ number_format($layanan->hargalayanantambahan, 0, ',', '.') }}</td>
                            <td>
                                <a href="{{ url('tambahlayananedit/'.$layanan->idlayanantambahan) }}" class="btn btn-sm btn-primary">
                                    <i class="bx bx-edit-alt"></i> Edit
                                </a>

                                <form action="{{ url('stokhapus/'.$layanan->idlayanantambahan) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus layanan ini?')">
                                        <i class="bx bx-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">Belum ada data layanan tambahan</td>
                        </tr>
                    @endforelse --}}
                    <tr>
                            <td colspan="4" class="text-center">Belum ada data Kategori</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>


@endsection
@section('script')
@endsection