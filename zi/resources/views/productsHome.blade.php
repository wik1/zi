@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @if($header != NULL)
                    <h1>{{$header}}</h1>
                @else
                    <h1>Produkty</h1>
                @endif


                <hr/>

                @if(isset($productTree))
                    <div id="products-treeview">
                    </div>
                @else
                    <div id="productCategories">

                    </div>
                @endif
            </div>
        </div>
    </div>
    <script>
        $(function () {
            @if(isset($productTree))
                $("#products-treeview").dxTreeView({
                    items: <?php echo json_encode($productTree);?>,
                    itemTemplate: function(data) {
                        return '<div><a href="/products/' + data.id + '">' + data.text + '</a></div>';
                    }
                });
            @endif

            var productCategoriesContainer = $('#productCategories');

            function fillTableWithData(data) {
                productCategoriesContainer.html('');
                data.forEach(function (productCategory) {
                    productCategoriesContainer.append('<div class="product-category-container"><a href="/products/' + productCategory.id + '">' + productCategory.name + "</a></div>");
                });
            }

            $.get("/api/product/categories/{{$productCategoryId}}", function (data, status) {
                // fill table with data here
                fillTableWithData(data);
            });
        });
    </script>
@endsection
