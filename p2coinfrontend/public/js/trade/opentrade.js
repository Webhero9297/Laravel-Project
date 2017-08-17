$(document).ready(function(){
    
});


function view_trademessage(param){
    var _token = $('meta[name=csrf-token]').attr('content');
    var form = document.createElement("form");
    var element1 = document.createElement("input"); 
    var element2 = document.createElement("input"); 

    form.method = "POST";
    form.action = "trademessage";   

    element1.value=param;
    element1.type = "hidden";
    element1.name="param";
    form.appendChild(element1);  

    element2.value=_token;
    element2.type = "hidden";
    element2.name="_token";
    form.appendChild(element2);

    document.body.appendChild(form);

    form.submit();    
}