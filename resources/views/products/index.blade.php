@extends('layouts.app')

@section('content')

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Products</h1>
    </div>


    <div class="card">
      <form action="{{ route('product.search') }}" method="get" class="card-header">
            <div class="form-row justify-content-between">
                <div class="col-md-2">
                    <input type="text" name="title" placeholder="Product Title" class="form-control">
                </div>
                <div class="col-md-2">
                    
                    <select name="variant" id="" class="form-control">
                          
                        @foreach ($products as $item)
                            @foreach ($item->product_variants as $varinat)
                                <option value="{{$varinat->variant}}">{{$varinat->variant}}</option>
                            @endforeach
                       @endforeach
                    </select>

                   
                </div>

                <div class="col-md-3">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Price Range</span>
                        </div>
                        <input type="text" name="price_from" aria-label="First name" placeholder="From" class="form-control">
                        <input type="text" name="price_to" aria-label="Last name" placeholder="To" class="form-control">
                    </div>
                </div>
                <div class="col-md-2">
                    <input type="date" name="date" placeholder="Date" class="form-control">
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary float-right"><i class="fa fa-search"></i></button>
                </div>
            </div>
        </form>

        <div class="card-body">
            <div class="table-response">
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Variant</th>
                        <th width="150px">Action</th>
                    </tr>
                    </thead>

                    <tbody>
                        {{-- Show all products --}}
                        @foreach ($products as $item)
                        <tr>
                            <td>{{ $products->firstItem() + $loop->index }}</td>
                            {{-- <td>{{$item->title}} <br> Created at : {{ $item->created_at->diffForHumans() }}</td> --}}
                            <td>{{$item->title}} <br> Created at : {{ Carbon\Carbon::parse($item->created_at)->diffForHumans() }}</td>
                            <td style="width:40%">{{$item->description}}</td>
                            <td>

                                {{-- Show product variants --}}
                                          
                                <dl class="row mb-0" style="height: 80px; overflow: hidden" id="variant">
                                  
                                
                                    <dt class="col-sm-3 pb-0">
                                        @foreach ($item->product_variants as $varinat)
                                        {{$varinat->variant }}
                                        @endforeach
                                    </dt>
                                  
                                    @foreach ($item['product_variant_prices'] as $it)  
                                    <dd class="col-sm-9">
                                        <dl class="row mb-0">
                                            <dt class="col-sm-4 pb-0">Price : {{$it->price }}</dt>
                                            <dd class="col-sm-8 pb-0">InStock : {{$it->stock }}</dd>
                                        </dl>
                                    </dd>
                                    @endforeach
                                </dl>
                              

                                <button onclick="$('#variant').toggleClass('h-auto')" class="btn btn-sm btn-link">Show more</button>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('product.edit', $item->id) }}" class="btn btn-success">Edit</a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                  

                    </tbody>

                </table>
             
            </div>
            
        </div>

        <div class="card-footer">
            <div class="row justify-content-between">
                <div class="col-md-6">
                    {{-- <p>Showing 1 to 10 out of 100</p> --}}
                    <p>Showing {{($products->currentpage()-1)*$products->perpage()+1}} to {{ $products->currentpage()*(($products->perpage() < $products->total()) ? $products->perpage(): $products->total())}} out of {{ $products->total()}} entries</p>
                </div>
                <div class="col-md-2" style="float: right">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>

@endsection
