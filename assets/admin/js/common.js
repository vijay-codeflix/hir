var base_url = $('#baseUrl').val();

/*
Name: ImageExist
Description: This function is created for check image exist or not
*/
function ImageExist(url) 
{
   var img = new Image();
   img.src = url;
   return img.height != 0;
}

/*
Name: IsJsonString
Description: this function check given string is json or not
*/
function IsJsonString(str) {
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
}

/*
Name: IsJsonString
Description: this function check given string is json or not
*/
function IsJsonStringData(str) {
	var jsonStr = '';
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    jsonStr = JSON.parse(str);
    return jsonStr;
}

/*Function:
* Name: loaderOn()
* Parameters: 
* Description: this function is used for Active loader event 
*/
function loaderOn(){ $( document ).ajaxStart(function() {  $('#page-wrapper').addClass('page-loading'); }); }

/*Function:
* Name: loaderOff()
* Parameters: 
* Description: this function is used for Inactive loader event 
*/
function loaderOff(){ $( document ).ajaxStart(function() {  $('#page-wrapper').removeClass('page-loading'); }); }

/*Function:
* Name: pad()
* Parameters: n = pass my value , width= length of required string, z = character add before
* Response: - add zero OR other character before num
* Description:
*/
function pad(n, width, z) {
  z = z || '0';
  n = n + '';
  return n.length >= width ? n : new Array(width - n.length + 1).join(z) + n;
}

/*Function:
* Name: allowOnlyCharactres()
* Description: function created for allow only charcaters
*/
function allowOnlyCharactres(key){
	if( (key.charCode == 32) || (key.charCode == 0) || (key.charCode > 64 && key.charCode < 91) || (key.charCode > 96 && key.charCode < 123) ){
		return true;  
	} else {
		return false; 
	}
}
/*Function:
* Name: allowOnlyNumbers()
* Description: function created for allow only numbers
*/
function allowOnlyNumbers(key){
	if( (key.charCode == 0) || (key.charCode > 47 && key.charCode < 58)){
		return true; 
	}else{ 
		return false; 
	}
}
/*Function:
* Name: appendUppercaseValue()
* Description: function created for appendUppercaseValue
*/
function appendUppercaseValue( key ){
	var val = (key.val()).toUpperCase();
	key.val( val );
}
/*Function:
* Name: allowCharNumbers()
* Description: function created for create code/string which content string-num value.
*/
function allowCharNumbers( key ){
	if( (key.charCode == 0) || (key.charCode > 64 && key.charCode < 91) || (key.charCode > 96 && key.charCode < 123) ){
		return true;  
	}else if(key.charCode >= 48 && key.charCode < 58){
		return true; 
	}else{
		return false;
	}
}

/*Function:
* Name: allowCharNumbersSpecial()
* Description: function created for create code/string which content string-num value.
*/
function allowCharNumbersSpecial( key ){

 if( (key.charCode == 32) || (key.charCode == 0) || (key.charCode > 64 && key.charCode < 91) || (key.charCode > 96 && key.charCode < 123) ){
    return true;  
  }else if(key.charCode >= 48 && key.charCode < 58){
    return true; 
  }else if(key.charCode == 44 || key.charCode == 45 || key.charCode == 46){
    return true;
  }else {
    return false;
  }
}

/*Function:
* Name: capitalise()
* Description: function created for capitalise first character of any string
*/
function capitalise(string) { return string.charAt(0).toUpperCase() + string.slice(1).toLowerCase(); }

/*Function:
* Name: capitalise()
* Description: function created for capitalise first character of any string
*/
function capitalizeFirstLetter(string) {     return string.charAt(0).toUpperCase() + string.slice(1); }

/*Function:
* Name: removeLRcomma()
* Description: function created for remove LR commas
*/
function removeLRcomma(str){ return string = str.replace(/^,|,$/g,''); }

/*Function:
* Name: removeLRMspaces()
* Description: function created for remove LRM spaces
*/
function removeLRMspaces(str){ return string = $.trim( str.replace(/  +/g, ' ') ); }

/*Function:
* Name: createErrorHtml()
* Description: function created for display errors in red color
*/
function createErrorHtml(arrayVal){
    for(var i=0; i < arrayVal.length; i++){
        $('#' + arrayVal[i]).parent().parent().addClass('has-error');
        $('#' + arrayVal[i]).siblings('input').val('');
        $('#' + arrayVal[i]).fadeOut(5000, function(){ $(this).parent().parent().removeClass('has-error');$(this).remove(); });
    }
}

/*Function:
* Name: addOneDay()
* Description: function created for addOneDay in given date
*/
function addOneDay(start_date){
  start_date = start_date.split('-');
    if(start_date.length > 2){
      start_date = start_date[1] +'/'+ start_date[0] +'/'+ start_date[2];
    }else{
      $(this).val('');
      return false;
    }
    var today = new Date( start_date );
    var tomorrow = new Date(today);
        tomorrow.setDate(today.getDate()+2);
        tomorrow.toLocaleDateString();
    return tomorrow;        
}

/*Function:
* Name: compareDates()
* Description: function created for compareDates given date
*/
function compareDates(first, second){
  first = first.split('-');
  first = first[1] +'/'+ first[0] +'/'+ first[2];

  second = second.split('-');
  second = second[1] +'/'+ second[0] +'/'+ second[2];

  if( (new Date(second).getTime() > new Date(first).getTime()) )
  {
    return 'correct';    
  }else{
    return 'incorrect';
  }
}


$( document ).ajaxStart(function() {
	$('#page-wrapper').addClass('page-loading');
});

$( document ).ajaxStop(function() {
	$('#page-wrapper').removeClass('page-loading');
});

$(window).ready(function(){ 
  var sideBar = $('#sidebar-scroll ul.sidebar-nav > li');
  var className = '';
  var validate = false;

  for(var i = 0; i < sideBar.length; i++){
  
    className = sideBar.eq(i).attr('class');
    classText = $('#sidebar-scroll ul.sidebar-nav > li:eq('+i+') >a:eq(0) span' ).html();
    
    classNameAn = $('#sidebar-scroll ul.sidebar-nav > li:eq('+i+') > a').attr('class');
    
    if($.trim(className) == 'active'){

      localStorage.setItem("activeLiIndex", i);
      localStorage.setItem("mainTabName", classText);

      localStorage.setItem("activeLiAnchorIndex", null);
      //localStorage.setItem("activeLiAnchorIndexText", null);

      for(var y = 0; y < $('#sidebar-scroll ul.sidebar-nav > li:eq('+i+') > ul li').length; y++){
          var innerActive = $('#sidebar-scroll ul.sidebar-nav > li:eq('+i+') > ul li:eq('+ y +') > a').attr('class');          
          if($.trim(innerActive) == 'active'){
            localStorage.setItem("innerActive", y);
            localStorage.setItem('innerActiveText', $('#sidebar-scroll ul.sidebar-nav > li:eq('+i+') > ul li:eq('+ y +') > a').html() );
          }
      }

    }else if($.trim(classNameAn) == 'active'){      
      localStorage.setItem("mainTabName", classText);
      localStorage.setItem("activeLiIndex", null);
      localStorage.setItem("innerActive", null);
      localStorage.setItem('innerActiveText', null);
      localStorage.setItem("activeLiAnchorIndex", i);
    }

  }

  var activeLiIndex = localStorage.getItem("activeLiIndex");
  var innerActive = localStorage.getItem("innerActive");
  var activeLiAnchorIndex = localStorage.getItem("activeLiAnchorIndex");

  var mainTabName = localStorage.getItem("mainTabName");
  var innerActiveText = localStorage.getItem("innerActiveText");

  //alert(mainTabName + '__' + innerActiveText);

  if(activeLiAnchorIndex != null){
    $('#sidebar-scroll ul.sidebar-nav > li:eq('+ activeLiAnchorIndex +') a').attr('class',' active');
  }

  if(activeLiIndex != null){
    
    $('#sidebar-scroll ul.sidebar-nav > li:eq('+ activeLiIndex +')').attr('class',' active');

    if(innerActive != null){
      $('#sidebar-scroll ul.sidebar-nav > li:eq('+activeLiIndex+') > ul li:eq('+ innerActive +') > a').attr('class', ' active');
    }

  }


  //alert(activeLiIndex +'__'+ activeLiAnchorIndex);
  
  $('button').click(function(){
    
    var lenVissible = $('.alert-danger:visible').length;
    
    var lenSucsVissible = $('.alert-success:visible').length;
    
    if(lenVissible > 0 || lenSucsVissible > 0){ $('html,body').animate({scrollTop: $('html').offset().top}); } 
    
  });
	
	/***Disable mouse right click event**/	
	/*document.oncontextmenu = function() {return false;};

	$(document).mousedown(function(e){ 
		if( e.button == 2 ) { 
		  return false; 
		} 
		return true; 
	}); */
	/***Disable mouse right click event**/

	/***Disable F12 key event**/
  	/*document.onkeypress = function (event) {
        event = (event || window.event);
        if (event.keyCode == 123) {
            return false;
        }
    }*/
    /***Disable F12 key event**/

    /***Disable cut copy paste event**/
    /*$( document ).on("cut copy paste",function(e) {
	    e.preventDefault();
    });*/
    /***Disable cut copy paste event**/
    
});

$( "form" ).submit(function( event ) {  $('html,body').animate({scrollTop: $('html').offset().top}); });

function submitForm(refs){  $(refs).parent().submit(); }

function getFormattedDate(date) {
  var year = date.getFullYear();
  var month = (1 + date.getMonth()).toString();
  month = month.length > 1 ? month : '0' + month;
  var day = date.getDate().toString();
  day = day.length > 1 ? day : '0' + day;
  return day + '-' + month + '-' + year;
}

function getMoney( num ){
  var number = num.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');  
  return number;
}

function rgbToHex(r, g, b) {
    return "#" + componentToHex(r) + componentToHex(g) + componentToHex(b);
}

function componentToHex(c) {
    var hex = c.toString(16);
    return hex.length == 1 ? "0" + hex : hex;
}

function checkBillStatus(){
    $.ajax({
        url: base_url + 'admin/customerarea/checkBillUnpaid',
        method: 'POST',
        success: function(result){

            if($.trim(result) == 'BILL_UNPAID'){
                
                window.location.href = base_url + 'admin/customerarea/billunpaid';
                
                return false;

            }else{
                return true;
            }
        }
    });
}

var sortSelect = function (select, attr, order) {
    if(attr === 'text'){
        if(order === 'asc'){
            $(select).html($(select).children('option').sort(function (x, y) {              
                if($(x).text().toUpperCase() != 'ALL' && $(y).text().toUpperCase() != "ALL"){
                    return $(x).text().toUpperCase() < $(y).text().toUpperCase() ? -1 : 1;
                }
            }));
            $(select).get(0).selectedIndex = 0;
  //          e.preventDefault();
        }// end asc
        if(order === 'desc'){
            $(select).html($(select).children('option').sort(function (y, x) {
                return $(x).text().toUpperCase() < $(y).text().toUpperCase() ? -1 : 1;
            }));
            $(select).get(0).selectedIndex = 0;
    //        e.preventDefault();
        }// end desc
    }

};

$( document ).ready(function(){
  
  setTimeout(function(){
    $('.alert-danger').fadeOut(15000);
    $('.alert-success').fadeOut(15000);
    $('#notdelete').fadeOut(15000);
  }, 30000);  

  var timeout = 15000; // in miliseconds (3*1000)

  $('.alert-danger').delay(timeout).fadeOut(15000);
  $('.alert-success').delay(timeout).fadeOut(15000);

  /*Set filters*/
  var dv = $('.filterNone');
  var height = 0;
  if(dv.length > 0){
    for(var x = 0; x < dv.length; x++){
      var curHt = dv.eq(x).height();    
      if(curHt > height){
        height = curHt;
      }
    }

    if(height > 36){
     dv.css('height',curHt+ 'px');
   }
    /*Set filters*/  
  }

  var totalSelect = $('select');

  if(totalSelect.length > 0){
  
    for(var x=0; x < totalSelect.length; x++){
      
      var cls = totalSelect.eq(x).attr('class');

      if(cls != 'widFull select-select2'){
        totalSelect.eq(x).removeClass().addClass('widFull');
        totalSelect.eq(x).select2({
            minimumResultsForSearch: -1
        });
      }

    }

  }

});

$( document ).ajaxComplete(function() {
  
  setTimeout(function(){
    $('.alert-danger').fadeOut(1000);
    $('.alert-success').fadeOut(1000);
    $('#notdelete').fadeOut(1000);
  }, 3000);  
  
  var totalSelect = $('select');

  if(totalSelect.length > 0){
  
    for(var x=0; x < totalSelect.length; x++){
      
      var cls = totalSelect.eq(x).attr('class');

      if(cls != 'widFull select-select2'){
        totalSelect.eq(x).removeClass().addClass('widFull');
        totalSelect.eq(x).select2({
            minimumResultsForSearch: -1
        });
      }

    }

  }
  /*Set filters*/
  var dv = $('.filterNone');
  var height = 0;
  if(dv.length > 0){
    for(var x = 0; x < dv.length; x++){
      var curHt = dv.eq(x).height();    
      if(curHt > height){
        height = curHt;
      }
    }

    if(height > 36){
     dv.css('height',curHt+ 'px');
    }
    /*Set filters*/  
  }
});
function checkproductName(){
        
    var newVal = removeLRMspaces( $('#pro_name').val() ) ;
    var prdId = ($('#prod_id').length > 0)? $('#prod_id').val() : '';
    
    $('#pro_name').val( newVal ); 

    $.ajax({
        url: base_url + "admin/product/checkProductName",
        method: 'POST',
        data: { name: newVal, id: prdId },
        success:function(result) {

            if(result == 'true'){
                $('#productDuplicate').show().parent().parent().addClass('has-error');
                $('#pro_name').val(''); 
                return false;
            }else{
                $('#productDuplicate').hide().parent().parent().removeClass('has-error');
                return true;
            }
            
            if($('#productDuplicate').is(":visible") == true) { 
             $('#productDuplicate').fadeOut(3000,function(){ $(this).parent().parent().removeClass('has-error'); });
            }
        }
    });
}