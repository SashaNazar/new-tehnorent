function confirmDelete() {
   return confirm('Delete this item?') ? true : false;
}

jQuery(function($){
    $("#mobile_phone").mask("+380(99)999-99-99",{placeholder:"+380(  )   -  -  "});
});