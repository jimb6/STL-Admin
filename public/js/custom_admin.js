$(document).ready(function(){
   $("li.nav-item.dropdown.user-menu a").click(function(){
        $("ul.dropdown-menu").slideToggle();
   });
   $('button#cstm-theme').click(function(){
      $('body').toggleClass('darkTheme');
   });
});
