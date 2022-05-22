//version: 1.1
jQuery(function($) {
  $(document).ready(function(){

    //pricing page stacktable
    $('.stacktable.flower-price-options').find('table').stacktable();

    // Add simple lightbox
    $('.et_pb_lightbox_image').unbind('click');
    var imageLinks = $("a[href$='.jpg'],a[href$='.jpeg'],a[href$='.png'],a[href$='.gif']").not('.social-share-link').not('.et_pb_gallery_image a, .envira-gallery-wrap a, .ngg-gallery-thumbnail a').attr('rel', 'gallery');
  console.log(imageLinks);
    imageLinks.magnificPopup({
      type: 'image',
      mainClass: 'mfp-fade',
      gallery:{
        enabled: true
      },
      midClick: true
    });
  

  }); // end document ready
});
