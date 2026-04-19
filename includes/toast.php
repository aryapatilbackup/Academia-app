
<script>
window.onload=function(){

const toast=document.getElementById("toast");

if(toast){

toast.classList.add("show");

setTimeout(()=>{
toast.classList.remove("show");
},4000);

if(window.history.replaceState){
const cleanURL = window.location.pathname;
window.history.replaceState({}, document.title, cleanURL);
}

}

}
</script>