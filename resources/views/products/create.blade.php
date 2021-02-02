<div class="jumbotron vertical-center">
<div class="container">
    <form action="/products/store" enctype="multipart/form-data" method="post">
        @csrf
        <h1 class="spaced">Add Products</h1>

        <div class="row">
                <input type="file" id="product" name="product" class="form-control-file spaced" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                @if ($errors->has('product'))
                        <strong>{{ $errors->first('product') }}</strong>
                @endif
            <button class="btn btn-primary">Submit</button>
        </div>
    </form>
</div>
</div>
@yield('create')
