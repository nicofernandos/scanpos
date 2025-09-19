@extends('layouts.admintemplate')
@section('title','Data Penjualan')

@section('content')
<div class="card">
    <h5 class="card-header">Data Penjualan</h5>
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card-body">
        <div class="table-responsive text-nowrap">
            <table id="penjualanTable" class="table table-striped table-bordered dt-responsive nowrap w-100">
                <thead class="">
                    <tr>
                        <th>No</th>
                        <th>No. Invoice</th>
                        {{-- <th>No. HP</th> --}}
                        <th>Tanggal Pembelian</th>
                        <th>Total Barang</th>
                        <th>Grand Total</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($penjualan as $index => $row)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $row->nofp }}</td>
                            {{-- <td>{{ $row->nohp }}</td> --}}
                            <td>{{ $row->tglfp }}</td>
                            {{-- <td>                                
                            @foreach ($row->penjualanDetails as $detail)
                            {{$detail->nam}} {{ $detail->sum('qty') }} 
                            @endforeach
                            </td> --}}
                            <td>
                            {{ $row->penjualanDetails->sum('qty') }}
                            </td>

                            <td>Rp. {{ number_format($row->totnet, 0, ',', '.') }}</td>
                            <td>
                            <a href="{{ url('penjualandetail', $row->id) }}" class="btn btn-sm btn-success">
                                <i class="bx bx-detail"></i> Detail
                            </a>
                                <a href="{{ url('penjualanedit', $row->id) }}" class="btn btn-sm btn-primary">
                                    <i class="bx bx-edit-alt"></i> Edit
                                </a>
                                <form action="{{ url('penjualanhapus/'.$row->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus booking ini?')">
                                        <i class="bx bx-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Belum ada data penjualan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('script')
    <script>
        $(document).ready(function () {
            $('#penjualanTable').DataTable({
                responsive: true,
                pageLength: 10,
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
                }
            });
        });
    </script>
@endsection
