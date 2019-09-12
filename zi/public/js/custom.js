
$(function () {
    highlightProperMenuItem();
    highlightProperSubMenuItem();

    function highlightProperMenuItem() {
        var menuItems = $('.menu-inner-container a');
        menuItems.each(
            function (index, item) {
                if (currentUrlContainsHrefOf(item) && $(item).text() !== 'Produkty') {
                    $(item).addClass('active');
                }
            }
        );
    }

    function highlightProperSubMenuItem() {
        var subMenuItems = $('.submenu-container li');
        subMenuItems.each(
            function (index, item) {
                if (currentUrlContainsHrefOf($(item).children('a')[0])) {
                    $(item).addClass('active');
                }
            }
        );
    }

    function currentUrlContainsHrefOf(item) {
        return window.location.pathname.lastIndexOf(item.pathname, 0) === 0;
    }

    $("#search-input").keyup(function(event){
        if(event.keyCode == 13){
            $("#search-button").click();
        }
    });

    $(".product-menu-trigger").hover(function (event) {
        $(".productsMenu").show();
    }, function (event) {
        $(".productsMenu").hide();
    });
});


function searchButtonClicked(buttonElement) {
    var searchQuery = $($(buttonElement).parent().children()[0]).val();
    var searchCategory = $($(buttonElement).parent().children()[1]).val();

    common.changeToSmallSpinnerIcon(buttonElement);
    window.location.href = "/search?q=" + searchQuery + "&category=" + searchCategory;
}

var common = {};

common.displayNotification = function (messageText) {
    new Noty({
        type: 'success',
        text: messageText,
        timeout: 100
    }).show();
};

common.updateCartSize = function (cartData) {
    $('.main-banner .cart-size').text(cartData.cartSize);
    $('.main-banner .cart-value-span').text(common.numberWithSpaces(cartData.cartValue));
};

common.changeToSpinnerIcon = function (buttonElement) {
    $(buttonElement).html('<i class="fa fa-refresh fa-spin fa-2x fa-fw" aria-hidden="true"></i>');
};

common.changeToSmallSpinnerIcon = function (buttonElement) {
    $(buttonElement).html('<i class="fa fa-refresh fa-spin fa-fw" aria-hidden="true"></i>');
};

common.changeHtmlTo = function (buttonElement, html) {
    $(buttonElement).html(html);
};

common.updateCartNumberInTable = function(childFormElement) {
    var previousValueString = $(childFormElement).parent().parent().prev().text();
    var previousValueInt = parseFloat(previousValueString);
    var valueToAdd = parseFloat($($(childFormElement).prev().prev().children()[0]).val());
    $(childFormElement).parent().parent().prev().text(previousValueInt + valueToAdd);
};

common.addProductToCart = function (childFormElement, addedMessage, callback) {
    $.ajax({
        type: "PUT",
        url: '/api/cart/addproduct',
        data: $(childFormElement).parent().serialize(), // serializes the form's elements.
        success: function (data) {
            common.displayNotification(addedMessage);
            common.updateCartSize(data);
            common.updateCartNumberInTable(childFormElement);
            if (callback) {
                callback();
            }
        }
    });
};

common.displayErrorNotification = function (messageText) {
    new Noty({
        type: 'error',
        text: messageText,
        timeout: 3000
    }).show();
};

common.showGlobalSpinner = function() {
    $('.zi-global-spinner').show();
};

common.hideGlobalSpinner = function() {
    $('.zi-global-spinner').hide();
};

common.numberWithSpaces = function(x, precision) {
    if (precision === undefined) {
        precision = 2;
    }
    return x.toFixed(precision).toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ").replace(".",",");
}

common.dataGridCustomizeNumber = function (numberString, precision) {
    return common.numberWithSpaces(parseFloat(numberString), precision);
}


common.sessionPut = function (id, value) {
	
	$.ajax(	{	type: "PUT",
				async: false,
				url: '/api/sessionput/'+id+'/'+value,
				success: function(data){
					result= data;
					// alert("Ok: ");
				},
				error: function(data){
					alert("Błąd");
				}
	});

	return result;
};
	
       
common.sessionPutIfEmpty = function (id, value) {
	let x=common.sessionGet(id);
	
	if (x==="null" || x==="NaN" || x==="") {
		common.sessionPut( id, value);
	};

	return value;
};	


common.sessionGet = function (id) {
	
	$.ajax(	{	type: "GET",
				async: false,
				url: '/api/sessionget/'+id,
				success: function(data){
					result= data;
					// alert("Ok: ");
				},
				error: function(data){
					alert("Błąd");
				}
	});

	return result;
};	

common.sessionPull = function (id) {
	
    $.ajax( {	type: "GET",
                async: false,
                url: '/api/sessionpull/'+id,
                success: function(data){
                        result= data;
                        // alert("Ok: ");
                },
                error: function(data){
                        alert("Błąd");
                }
    });

    return result;
};	

common.errorMsg = function (msg, title)  {	
	title = title || 'Błąd';
	
	var closedDialog = DevExpress.ui.dialog.custom({
		  title: title,
		  message: msg,
		  buttons: [{ text: "Ok" } ]
	});			
	
	closedDialog.show();
	
	//alert( title+'\n\n'+msg);
};	

common.getStrPart = function (text, mrkStart, mrkEnd ) {
	let start = text.indexOf(mrkStart)+mrkStart.length;
	let end   = text.indexOf(mrkEnd);
	if (!mrkEnd) end=text.length;

	return text.substr(start, end-start).trim();
};

common.getErrDbKind = function(text) {
	//1-grant; 2-exception; 3- złamamny klucz
	
	if (text.indexOf("SQLSTATE")<0) //no DB error
		return;
						   
	x=-1; k= text.indexOf("General error: -551"); 
	
	if (k>0) x= 1
	else {
		k= text.indexOf("General error: -836");
		if (k>0) x= 2 
		else {
			k= text.indexOf("General error: -530");
			if (k>0) x= 3;
		}
	}
	
	return x;
};

common.getErrDbGrant = function (text) {
	return 'Brak uprawnień do: "'+common.getStrPart(text, "to PROCEDURE", "(SQL:" )+'"';
};  

common.getErrDbException = function (text) {
	x = common.getStrPart(text, "exception", "At procedure" );
	x = common.getStrPart(x, " ")
    x = common.getStrPart(x, " ");
	
	return x;
};

common.getErrDbForeigenKey = function (text) {
	return 'Złamany klucz: '+common.getStrPart(text, "FOREIGN KEY constraint", "on table" );
};

common.getErrDbText = function (text) {
	let k =  common.getErrDbKind(text);
    let x = '';
    
    if (k) 
    	switch(k) {
          case 1: 
          	x = common.getErrDbGrant(text);
            break;
          case 2:
            x = common.getErrDbException(text);
            break;
          case 3:
            x = common.getErrDbForeigenKey(text);
            break;            
          default:
            x = text;
            // 'Błąd nie rozpoznany';
        } 
     else 
     	x = text;
        
    x=x+"<br />";
	
	return x;
};		
