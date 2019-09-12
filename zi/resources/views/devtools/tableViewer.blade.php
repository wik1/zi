@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <h1>DevTools / TableViewer</h1>
        </div>
        <div class="row">
            <div class="col-md-12">
                <form>
                    <div class="form-group">
                        <label for="tableName">Table name</label>
                        <input type="text" class="form-control" id="tableName" placeholder="Table name">
                    </div>
                    <button id="submitTableViewer" type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
        <div class="row">
            <div id="dataContainer" class="col-md-12">
                <div class="alert alert-danger" role="alert">
                    <strong>Oh snap!</strong> Change a few things up and try submitting again.
                </div>
                <table class="table">
                    <thead>
                    <tr>
                        <th>Column name</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <script>
        $(function () {
            var errorElement = $('#dataContainer .alert-danger');
            errorElement.hide();

            function setError(errorMessage) {
                errorElement.show();
                $('#dataContainer .alert-danger').text(errorMessage)
            }

            var columnsTable = $('#dataContainer .table tbody');
            function fillTableWithData(data) {
                columnsTable.html('');
                data.forEach(function (columnName) {
                    columnsTable.append("<tr> <td>" + columnName + "</td> </tr>");
                });
            }

            $("#submitTableViewer").click(function (e) {
                e.preventDefault();
                errorElement.hide();
                $.get("/api/devtools/tableviewer/" + $("#tableName").val(), function (data, status) {
                    // fill table with data here
                    fillTableWithData(data);
                }).fail(
                    function (data, status) {
                        if (data.responseText.indexOf("Table unknown") > -1) {
                            setError("Table unknown")
                        } else {
                            setError("Other error")
                        }
                    }
                );
            });
        });
    </script>
@endsection
