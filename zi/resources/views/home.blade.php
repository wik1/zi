@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Zamówienia</h1>
        </div>
    </div>
</div>

<script>
    $(function () {
        $("#buttonContainer").dxButton({
            text: "OK",
            onClick: function (e) {
                DevExpress.ui.notify("The OK button was clicked");
            }
        });
    });
</script>
@endsection
