
try{
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
}catch(error){
    alert("outdated browser detected");
}
