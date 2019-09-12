@extends('layouts.app')

@section('content')
    <div class="container">		
		<div style="display: block; background-color:red">
			<a id="erorr" class="h4" style="color:white">					
			</a>
		</div>

		
		<div class="row">
			<div class="col-sm-6 col-sm-offset-3">
				<div class="panel panel-default">
					<div class="panel-heading">Parametry logowania 
					</div>	
					@if($guestEnabled)
						<div class="panel-body">								
							<form id="form" method="POST" action="/user/register">
								{{ csrf_field() }}
								<div id="formWidget"></div>	
								<div>
									<table>
										<tr>
											<th>
												<div id="button"></div>
											</th>
											<th width="100%">							
											</th>
											<th>
												<div id="back-button"></div>
											</th>
										</tr>
									</table>
								</div>												
							</form>	
						</div> 		
					@endif
				</div>		
			</div>				
		</div>					
	</div>	
<script>	
	function itemHideShow(elementId, visible){
		let x = document.getElementById(elementId);
		if (visible)	{ x.style.display = "block"; } 
		else            { x.style.display = "none";  };  
	};

	$(document).ready(function() {
		// xText= common.sessionGet('custom_ktrh_name');
		showErorrs = <?php echo json_encode($showErorrs );?>;
		itemHideShow('erorr', showErorrs);
		if (showErorrs) {
			xText= 'Błąd:  '+common.sessionGet('error_msg');		
			$("#erorr").html(xText);
		}			
	});	 	
	
	var formData = {"email": "","password": "","Naz": "","Im": "","IsCompany": "1","Name": "","Nip": "",
	                "Address": '', "ZipCode": "","PostOffice": "", "urlPrevious": document.referrer};
		
    var formWidget = $("#formWidget").dxForm({
        formData: formData,
        readOnly: false,		
        showColonAfterLabel: true,
        showValidationSummary: true,
        validationGroup: "userData",
        items: [{
					itemType: "group",
					caption: "Autoryzacja",
					items: [{	dataField: "urlPrevious",
								visible: false
							}, {
								dataField: "email",
								label: { text: "Adres e-mail"},
								validationRules: [{
										type: "required",
										message: "Podaj adres e-mail"
									}, {
										type: "email",
										message: "Błędny adres e-mail"
									}
								]
							}, {
								dataField: "password",
								editorOptions: {
									mode: "password"
								},
								validationRules: [{
									type: "required",
									message: "Podaj hasło"
								}]
							}, {
								label: {
									text: "Potwierdź hasło"
								},
								editorType: "dxTextBox",
								editorOptions: {
									mode: "password"
								},
								validationRules: [{
									type: "required",
									message: "Potwierdź hasło"
								}, {
									type: "compare",
									message: "'Hasło' and 'Potwierdź hasło' nie są identyczne",
									comparisonTarget: function() {
										return formWidget.option("formData").password;
									}								
								}]
							}]
				},{ 
					itemType: "group",
					caption: "Twoje dane",
					items: [{
								dataField: "Naz",
								label: { text: "Nazwisko"},								
								validationRules: [
									{ 	type: "required", 
										message: "Podaj nazwisko"}, 
									{	type: "pattern",
										pattern: "^[^0-9]+$",
										message: "Nazwisko nie może zawierać cyfr"
									}]
							}, {
								dataField: "Im",
								label: { text: "Imię"},								
								validationRules: [
									{ 	type: "required", 
										message: "Podaj imię"}, 
									{	type: "pattern",
										pattern: "^[^0-9]+$",
										message: "Imię nie może zawierać cyfr"
									}]
							},
							{
								dataField: "IsCompany",
								editorType: 'dxRadioGroup',
								label: { text: 'Kupuję jako', location: 'left' },
								editorOptions: {
									dataSource: [{ text: "osoba fizyczna", value: "0" }, { text: "firma/przedsiębiorstwo", value: "1" }],
									displayExpr: "text",
									valueExpr: "value",									
									layout: "horizontal",		
									onValueChanged: function(e){																				
										if (e.value=='1')	{ 
											e.value=true; 
											xLabel="Nazwa firmy";		
											msg='Podaj nazwę firmy';
											msg2='Nazwa nie może zawierać cyfr';
										}  else { 
											e.value=false;
											xLabel="Nazwisko i imię";	
											msg='Podaj nazwisko i imię';
											msg2='Nazwisko i imię nie może zawierać cyfr';
										};
										
										
										formWidget.beginUpdate();					
											//Name
											r= [{ type: "required", message: msg}, 			
												{type: "pattern", pattern: "^[^0-9]+$", message: msg2}];
											if(!e.value) {												
												r=null;												
											};
											formWidget.itemOption('Twoje dane.Name', 'validationRules', r);	
												
											formWidget.itemOption("Twoje dane.Name", "label", {text: xLabel});
											formWidget.itemOption("Twoje dane.Name", "visible", e.value);
											//Nip
											if(e.value) {
												r=[	{ type: "required", message: "Podaj NIP"}];
											}else{
												r=null;												
											}
											formWidget.itemOption('Twoje dane.Nip', 'validationRules', r);	
											formWidget.itemOption("Twoje dane.Nip", "visible", e.value);
										formWidget.endUpdate();								
										formWidget.validate();
									}
								}	
							},{
								dataField: "Name",
								label: { text: "Nazwa firmy"},								
								validationRules: [
									{ 	type: "required", 
										message: "Podaj nazwę firmy"}, 
									{	type: "pattern",
										pattern: "^[^0-9]+$",
										message: "Nazwa firmy nie może zawierać cyfr"
									}]
							},{
								dataField: "Nip",
								editorType: 'dxTextBox',
								editorOptions: {
									mask: "000-000-00-00",
									maskRules: { "X": /[02-9]/},
									maskInvalidMessage: "Błędny Nip",
									useMaskedValue: true									
								},
								validationRules: [{
									type: "required",
									message: "Podaj NIP"
								}]
							},{
								dataField: "Address",								
								label: { text: "Adres"},
								validationRules: [
									{ 	type: "required", 
										message: "Podaj adres"}]
							},{
								dataField: "ZipCode",							
								editorType: 'dxTextBox',
								editorOptions: {
									mask: "00-000",
									maskRules: { "X": /[02-9]/},
									maskInvalidMessage: "Błędny kod pocztowy",
									useMaskedValue: true									
								},
								label: { text: "Kod pocztowy"},
								validationRules: [
									{ 	type: "required", 
										message: "Podaj kod pocztowy"}]
							},{
								dataField: "PostOffice",
								label: { text: "Poczta"},
								validationRules: [
									{ 	type: "required", 
										message: "Podaj pocztę"}, 
									{	type: "pattern",
										pattern: "^[^0-9]+$",
										message: "Imię i nazwisko nie może zawierać cyfr"
									}]

							}]
        }]
    }).dxForm("instance");

	
	submitButton = $("#button").dxButton({
        text: "Zapisz",
        type: "success",
		validationGroup: "userData",
        useSubmitBehavior: true
    }).dxButton("instance");
	
	backButton = $("#back-button").dxButton({
        text: "Powrót do zamówień",
        type: "default",
		onClick: function(e) { 	
			window.location = '/discounts';
			//DevExpress.ui.notify( 'url - ');
		}

    }).dxButton("instance");
   
	
</script>
@endsection



