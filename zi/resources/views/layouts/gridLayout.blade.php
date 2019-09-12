@extends('layouts.app')

@section('content')
<script>
	function itemHideShow(elementId){
		let x = document.getElementById(elementId);
		
		if (x.style.display === "none")	{ x.style.display = "block"; } 
		else                           	{ x.style.display = "none";  };  
	};

	var dataGrid = null;
	var dataTB = null;
	var dataPM = null;
		
   /* variables to setup in next blade*/
	var dataSource = null;
	var dataColumns = null;
	/* custom define coluns in next blades
		dataColumns = [{ dataField: "NR", caption: "Nr" },
					  { dataField: "KTRH_N", caption:"Kontrahent"} ];*/
	var rowClick = null;
	var rowDblClick = null;
	
	var rowClickCmn = function(e) {
		var component = e.component,
						prevClickTime = component.lastClickTime;
		component.lastClickTime = new Date();
		
		if (prevClickTime && (component.lastClickTime - prevClickTime < 300)) {
			// Double click code DevExpress.ui.notify( 'double click');                  
			rowDblClick(e);
		}
		else {
			// Single click code DevExpress.ui.notify( 'single click');
			rowClick(e);
		}
         
	};
	
	var rowInserted = null;
	var editingStart = null;
	var gridInitialized = null;
	var searchPanelVisible = false;

	
	function gridStorageKey() {
		
		return	"storage"		 
    };
	
	var dataPMItems =	[ 	{ id: 1, text: 'Szczegóły'}, 
							{ id: -1, text: '---------------'},
//							{ id: 2, text: 'Dodaj'}, 
//							{ id: 3, text: 'Edytuj' }, 
							{ id: 4, text: 'Kasuj' }, 
							{ id: -1, text: '---------------'},    
							{ id: 5, text: 'Konfiguruj' }
						];
	
	function delPMClick() {	
		DevExpress.ui.notify("gridLayout button has been clicked!");
    };
	
	var dataPMItemClick= function(e){
		
		if (!e.itemData.items) {
				//var  r = dataGrid.getSelectedRowsData();
				switch(e.itemData.id){
				case 1: 
					delPMDetails();					
					break;
				case 2:
					break;
				case 3: 			
					delPMClick();
					break;
				case 4: 			
					delPMClick();
					break;
				case 5:
					if (dataGrid.option('allowColumnResizing')==true) {
						dataGrid.option('allowColumnResizing', false);	
					} else {
						dataGrid.option('allowColumnResizing', true);	
					};  								

					if (dataGrid.option('allowColumnReordering')==true) {
						dataGrid.option('allowColumnReordering', false);	
					} else {
						dataGrid.option('allowColumnReordering', true);	
					};  			
					if (isMobile()) {
						x= dataTBMobile.option('items[6].visible');	
						dataTBMobile.option('items[6].visible', !x);
					} else {
						dataGrid.showColumnChooser();
					};

//					itemHideShow("toolbarContainer");
//					dataGrid.showColumnChooser();                        
					break;		
			};	
		}
	};
						
						
	var dataTBItems = null;
			
	var tbClickRefresh = function () {
		window.location = window.location.href;
	};
	
	var gridKeyExpr = null;	
						
    
</script>
@yield('gridSetup')

@yield('gridHTML')	
<script>	   	
	$(document).ready(function() {
		gridSetup();					
	});				
	
	dataGrid=$("#gridContainer").dxDataGrid({
		dataSource: dataSource,
		hoverStateEnabled: true,
		wordWrapEnabled: true,
		allowColumnResizing: false,
		allowColumnReordering: false,
		keyExpr: gridKeyExpr,
//			columnAutoWidth: true, 
		showBorders: true,
		rowAlternationEnabled: true,      
		columnResizingMode: "nextColumn",
//			height: 0,
		columnChooser: { 
			enabled: false,
			mode: "select",
			title: "Ustawienia"                    
		},
		remoteOperations: {
			groupPaging: true
		},
		selection: {
			mode: "single"
		},
//        paging: {
//            pageSize: 10
//        },
        pager: {
            showPageSizeSelector: true,
            allowedPageSizes: [10, 25, 50, 100],
            showNavigationButtons: true
        },			
		noDataText: "{{__('messages.teksty.listaProduktow.brakDanych')}}",
		editing: {
			mode: "row",
			allowUpdating: false,
			allowDeleting: false,
			texts: {
				confirmDeleteMessage: ''
			},			
		},
		loadPanel: {
			enabled: true
		},
		sorting: {
			mode: "single"
		},
        searchPanel: {
            visible: searchPanelVisible,
            width: 300,
            placeholder: "Szukaj..."
		},
		scrolling: {
			mode: "standard" // or "standard" "virtual" | "infinite"
		},
		stateStoring: {
			ignoreColumnOptionNames: [], 
			enabled: true,
			type: "localStorage",
			storageKey: gridStorageKey()
		//"storage"
		},
		filterRow: { visible: true, operationDescriptions: {
			between: "Pomiędzy",
			contains: "Zawiera",
			endsWith: "Kończy się na",
			equal: "Równe",
			greaterThan: "Większe niż",
			greaterThanOrEqual: "Większe lub równe",
			lessThan: "Mniejsze niż",
			lessThanOrEqual: "Mniejsze lub równe",
			notContains: "Nie zawiera",
			notEqual: "Nie jest równe",
			startsWith: "Zaczyna się na"
		} },
		columns: dataColumns,
		onRowClick: rowClickCmn,
		onRowInserted: rowInserted,
		onInitialized: gridInitialized,
		onContentReady: function (e) { // Selects the first visible row
							$(".dx-datagrid-headers td").css({ 'text-align' : ''});
							e.component.selectRowsByIndexes([0]);
							var columnChooserView = e.component.getView("columnChooserView");
							if (!columnChooserView._popupContainer) {
								columnChooserView._initializePopupContainer();
								columnChooserView.render();
								columnChooserView._popupContainer.option("position", { of: e.element, my: "right top", at: "right top", offset: "0 50"});
							};													
						},
		onEditingStart: editingStart,
		onKeyDown: function(e) {
			var selKey = e.component.getSelectedRowKeys();
			if (selKey.length) {
				var currentKey = selKey[0];
				var index = e.component.getRowIndexByKey(currentKey);
				if (e.jQueryEvent.keyCode == 38) {
					index--;
					if (index >= 0) {
						e.component.selectRowsByIndexes([index]);
						e.jQueryEvent.stopPropagation();
					}
				}                
				else if (e.jQueryEvent.keyCode == 40) {
					index++;                
					e.component.selectRowsByIndexes([index]);
					e.jQueryEvent.stopPropagation();
				}                   
			}
		}
	}).dxDataGrid("instance");
	
	dataPM=$("#menuContainer").dxContextMenu({
		dataSource: dataPMItems,
		//width: 200,
		target: "#gridContainer",
		onItemClick: dataPMItemClick 
	}).dxContextMenu("instance");
	
	var dataTBItemsDefault = [{
			location: 'before',
			widget: 'dxButton',
			locateInMenu: 'auto',
			options: {
				visible: false,
				icon: "refresh",
				onClick: tbClickRefresh
			}
		}, {
			location: 'after',
			widget: 'dxButton',
			locateInMenu: 'auto',
			options: {
				visible: false,
				icon: "filter",
				hint: "filtr cen",
				onClick: function() {
					x= common.sessionGet('priceFilterOff');
										
					if(x!=0 && x!=1) { //default filter On
						x=0; 		
						
					} else {		
						if(x==1) 	{x=0}
						else		{x=1};
					};
					
					common.sessionPut('priceFilterOff', x);
					
					window.location = window.location.href;
				}
			}
		}, {
			location: 'after',
			widget: 'dxButton',
			locateInMenu: 'auto',
			options: {
				down: true,
				icon: "preferences",
				hint: "konfiguracja kolumn",
				onClick: function() {
					dataGrid.showColumnChooser();                        
				}
			}
		}
	];
	
	if (!dataTBItems){		
		dataTBItems= dataTBItemsDefault
	};
	
	dataTB=$("#toolbarContainer").dxToolbar({
		items: dataTBItems
	}).dxToolbar("instance");

	
</script>
	@yield('editContent')	
@endsection
