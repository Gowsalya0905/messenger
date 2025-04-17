/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function onLoad(loading, loaded) {
    if(document.readyState === 'complete'){
        return loaded();
    }
    loading();
    if (window.addEventListener) {
        window.addEventListener('load', loaded, false);
    }
    else if (window.attachEvent) {
        window.attachEvent('onload', loaded);
    }
};

onLoad(function(){
     $('#divLoading').addClass('show');
     $('#loadinggif').addClass('show');
  
},
function(){
    setTimeout(function () {
       $('#divLoading').removeClass('show');
       $('#loadinggif').removeClass('show');
    }, 800);
    
});

var loader = function(){ 
  //  alert("fdgfd");
    $('#divLoading').addClass('show');
    $('#loadinggif').addClass('show');
};
var unloader = function(){
    $('#divLoading').removeClass('show');
    $('#loadinggif').removeClass('show');
};
