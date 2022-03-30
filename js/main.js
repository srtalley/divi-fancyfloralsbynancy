//version: 1.1
jQuery(function($) {
  $(document).ready(function(){

    //pricing page stacktable
    $('.stacktable.flower-price-options').find('table').stacktable();

    // detect if social is open or closed
    var monarch_social = document.querySelector('.et_social_mobile_button');

    var monarch_social_observer = new MutationObserver(function(mutations){
      mutations.forEach(function(mutation){
        var foo = mutation.target.getAttribute('class');
        if(foo.indexOf('et_social_active_button') >= 0){
          $('#footer-info').addClass('monarch-hidden');
        } else {
          $('#footer-info').removeClass('monarch-hidden');
        }
      });
    });
    // configuration of the observer:
    var config = { attributes: true, childList: true, characterData: true, attributeFilter: ['class'] };
    monarch_social_observer.observe(monarch_social,config);

  }); // end document ready
});
