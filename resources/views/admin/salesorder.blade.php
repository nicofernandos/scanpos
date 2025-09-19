@extends('layouts.admintemplate')
@section('title','Data Sales Order')

@section('content')
<div class="card">
    <h5 class="card-header">Data Sales Order</h5>
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
            <table id="salesOrder" class="table table-striped table-bordered dt-responsive nowrap w-100">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No. SO</th>
                        <th>Tanggal Sales Order</th>
                        <th>Detail Barang</th>
                        <th>Grand Total</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tsaleorder as $index => $row)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $row->noso }}</td>
                            <td>{{ $row->tgl }}</td>
                            

                            {{-- List Nama Barang + Qty --}}
                            <td>
                                <ul class="mb-0 ps-3">
                                    @foreach($row->salesorderdetails as $detail)
                                        <li>{{ $detail->nam }} ({{ $detail->qty }})</li>
                                    @endforeach
                                </ul>
                            </td>

                            <td>Rp {{ number_format($row->tot, 0, ',', '.') }}</td>

                            {{-- Actions --}}
                            <td>
                                <a href="{{ url('saleorderdetail/'.$row->id) }}" class="btn btn-sm btn-success">
                                    <i class="bx bx-detail"></i> 
                                </a>
                                <a href="{{ url('saleorderedit/'.$row->id) }}" class="btn btn-sm btn-primary">
                                    <i class="bx bx-edit-alt"></i>
                                </a>
                                <form action="{{ url('saleorder/'.$row->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus Sales Order ini?')">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">Belum ada data Sales Order</td>
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
        $('#salesOrder').DataTable({
            responsive: true,
            pageLength: 10,
            language: {
                url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
            }
        });
    });
</script>
@endsection
