$(document).ready(function () {
var autoLogoutTimer,autoLogoutTimer1;
        resetTimer();
        $(document).on('mouseover mousedown touchstart click keydown mousewheel DDMouseScroll wheel scroll',document,function(e){
            // console.log(e.type); // Uncomment this line to check which event is occured
            resetTimer();
            
        });
        // resetTimer is used to reset logout (redirect to logout) time 
        function resetTimer(){ 
            clearTimeout(autoLogoutTimer);
            clearTimeout(autoLogoutTimer1);
            var calctime = (30*60*1000);
            autoLogoutTimer = setTimeout(idleLogout,calctime) // 1000 = 1 second
            autoLogoutTimer1 = setTimeout(idlePopup,calctime-5000) // 1000 = 1 second
        } 
        // idleLogout is used to Actual navigate to logout
function idleLogout(){
   
            window.location.href = baseURL+'logout'; // Here goes to your logout url 
}
function idlePopup(){
//      swal({
//                    title: "Because you have been inactive, your session is about to expire., Please wait..",
//                    imageUrl: loadingImg,
//                    showConfirmButton: false,
//                    allowOutsideClick: false
//                });
}
});