@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-center">
                    <h4>{{ __('Products') }}</h4>
                </div>

                <div class="card-body p-5">
                    <p class="text-center">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
                                {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            </div>
                        @elseif(session('danger'))
                            <div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
                                {{ session('danger') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            </div>
                        @endif
                    </p>
                    <table class="table table-condensed table-bordered table-responsive">
                        <thead>
                            <tr class="bg-secondary">
                                <th>S.No</th>
                                <th>Name</th>
                                <th>Price ({{ env('CURRENCY_SYMBOL') }})</th>
                                <th>Description</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $i = 0 @endphp
                            @forelse($products as $product)
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>{{ $product->name }}</td>
                                <td>{{ number_format($product->price, 2) }}</td>
                                <td>{{ $product->description }}</td>
                                <td>
                                    <a href="{{ route('buy', ['id' => $product->id]) }}" class="btn btn-sm btn-primary">Buy Now</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5">There are no products</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="text/javascript">
$(document).ready(function() {

        $(document).on('click', 'button.close', function() {
            $(this).closest('.alert').remove();
        });

    });

</script>
@endpush