@extends('layouts.app')

@section('content')    
	<div class="container">				
		<div class="row">
			<div class="col-sm-6 col-sm-offset-3">
				<div class="panel panel-default">
					<div class="panel-body">								
						<form id="form" method="GET" action="/search">
							{{ csrf_field() }}
							<div>
								<table>
									<tr>
										<th>
											<div id="back-button"></div>
										</th>
										<th width="10%"></th>
										<th width="80%">											
											<div id="button"></div>
										</th>
										<th width="10%"></th>
									</tr>
								</table>
							</div>																			
							<div id="formWidget"></div>	
						</form>	
					</div> 		
				</div>		
			</div>				
		</div>					
	</div>	
	
<script>	
	$(document).ready(function() {
		formWidget.getEditor("q").focus();
	});	 

	var formData = {"q": "", "xyz": "aa"};
	
	var store = new DevExpress.data.ArrayStore({
			data: <?php echo json_encode($searchProductGroups );?>,
			key: "id",
			errorHandler: function (error) {
				alert(error.message);
			}
		});
		var source = new DevExpress.data.DataSource({
			store: store
		});
		dataSource = source;	
	
    var formWidget = $("#formWidget").dxForm({
        formData: formData,
        readOnly: false,		
        showColonAfterLabel: true,        
        items: [{
					itemType: "group",
					caption: "",
					items: [ {
								dataField: "q",															
								label: { visible: false },
								editorOptions: {
									placeholder: "Fraza ...",
								},
								validationRules: [{
										type: "required",
										message: "Podaj frazę do szukania"
									}
								]
							}, {
								dataField: "category",
								label: { visible: false },
								editorType: 'dxSelectBox',								
								editorOptions: {
									dataSource: dataSource,
									valueExpr: "id",
									displayExpr: "name",
									searchEnabled: true,
									showClearButton: true,
									placeholder: "Wybierz grupę ...",
								}
							}]
				}]
    }).dxForm("instance");
	
	submitButton = $("#button").dxButton({
        text: "Szukaj",
        type: "default",
		width: "100%",
        useSubmitBehavior: true
    }).dxButton("instance");
	
	submitButton = $("#back-button").dxButton({
        text: "Powrót do zamówień",
        type: "back",
		onClick: function(e) { 	
			window.location = document.referrer;
//			DevExpress.ui.notify( 'url - '+formData.q+formData.category);
		}
    }).dxButton("instance");
   
	
</script>
@endsection



