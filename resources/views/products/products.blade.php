<div class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center sm:pt-0">
<div class="table-container">
    <div class="d-flex justify-center">
        <h1>All Products</h1>
    </div>
    <table class="table-container">
        <thead>
        <tr>
            <td> CODE </td>
            <td> NAME </td>
            <td> DESCRIPTION </td>
            <td> STOCK </td>
            <td> COST (£) </td>
            <td> DISCONTINUED AS OF </td>
        </tr>
        </thead>
        <tbody>
        @foreach (\App\Models\Product::all() as $product)
            <tr>
                <td><p>{{ $product->strProductCode }}</p></td>
                <td><p>{{ $product->strProductName }}</p></td>
                <td><p>{{ $product->strProductDesc }}</p></td>
                <td><p>{{ $product->stock }}</p></td>
                <td><p>{{ $product->price }}</p></td>
                <td><p>{{ $product->dtmDiscontinued }}</p></td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
</div>
@yield('products')
