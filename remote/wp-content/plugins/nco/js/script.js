( function( $ ) {
  var nco_validate_insurance_activation_form_jquery = function() {
    let $form = $('.page-id-3671 .insurance-form-section .nco-form');
    let $inputs = $form.find('input:not([type="submit"]), select');

    $form.validate({
      rules: {
        insurance_first_name: "required",
        insurance_last_name: "required",
        insurance_email: {
          required: true,
          email: true,
        },
        insurance_shop: "required",
        insurance_invoice_number: "required",
        insurance_device: "required",
        insurance_insurance_serial: "required",
        insurance_activation_code: "required",
      },
      messages: {
        insurance_first_name: "Este campo es requerido.",
        insurance_last_name: "Este campo es requerido.",
        insurance_email: {
          required: "Este campo es requerido.",
          email: "Ingrese un correo electrónico valido",
        },
        insurance_shop: "Este campo es requerido.",
        insurance_invoice_number: "Este campo es requerido.",
        insurance_device: "Este campo es requerido.",
        insurance_insurance_serial: "Este campo es requerido.",
        insurance_activation_code: "Este campo es requerido.",
      },
      submitHandler: function(form) {
        form.submit();
      },
    });

  }
  
  var nco_handle_query_codes = function(event) {
    let $form = $(event.target).parents('form');
    let $queryInput = $form.find('.input-container #query_str');
    let query = $queryInput.val();
    let type = $form.find('.input-container #query_field').val();

    if(query) {
      $queryInput.removeClass('error');
      $.ajax({
        url : ajax_params.ajax_url,
        type : 'post',
        data : {
          action : 'query_codes',
          query : query,
          type : type,
        },
        success : function( response ) {
          $('.page .insurance-results-section').empty();
          $('.page .insurance-results-section').append( response );
          
          $('.insurance-results-section .actions button.add-claim-button').each(function() {
            $(this).on('click', function(event){
              event.preventDefault();
              nco_claim_insurance_verification(event);
              event.stopPropagation();
            });
          });

          webStateWaiting(false);
        },
        error : function ( response ) {
          $('#pfb-signup-result').html('<p>Sorry but we were unable to retreive the list of codes.</p>');
          console.log(response);
        },
        beforeSend: function() {
          webStateWaiting(true);
          return true;
        },
      });
    }
    else {
      $queryInput.addClass('error');
    }
  }

  var nco_claim_insurance_verification = function(event) {
    let $modal = $('.page-id-9776 .wrapper-container > .modal-root');
    if($modal.length === 0) {
      // copy modal to the end of .wrapper-container
      $('#main-container .wpb_wrapper .modal-root')
        .clone()
        .appendTo('.page-id-9776 .wrapper-container');
  
      $modal = $('.page-id-9776 .wrapper-container > .modal-root');

      // Attach hide event
      $modal.find('.modal-overlay').on('click', nco_hide_claim_insurance_verification);
      $modal.find('.modal-dialog .close-button').on('click', nco_hide_claim_insurance_verification);

      // Attach submit event
      $modal.find('.modal-dialog .form-actions input[type="submit"]').on('click', function(event) {
        event.preventDefault();
        nco_validate_claim_password(event);
        event.stopPropagation();
      });
    }

    // set id of the insurance
    let $button = $(event.target);
    let postId = $button.attr('data-id');
    $modal.find('.verification-form input[name="post-id"]').attr('value', postId);

    // Show modal dialog
    $modal.removeClass('hidden');
    $modal.fadeTo(500, 1, function(){
      $(this).removeClass('transparent');
      $(this).css('display', '');
      //$(this).find('.modal-dialog').addClass('scale');
    });
  }

  var nco_hide_claim_insurance_verification = function() {
    $('.page-id-9776 .wrapper-container > .modal-root').fadeTo(500, 0, function(){
      $(this).addClass('transparent');
      $(this).addClass('hidden');
      $(this).css('display', '');
      // $('.page-id-9776 .wrapper-container > .modal-root').remove();
    });
  }

  var nco_validate_claim_password = function() {
    let $form = $(event.target).parents('form');
    let $claimPassword = $form.find('.input-container #claim_password');
    let $modalBody = $('.wrapper-container > .modal-root .modal-body');
    let claimPassword = $claimPassword.val();
    let postId = $form.find('.form-actions input[name="post-id"]').attr('value');
    let $cellActions = $('.page .insurance-results-section tr.post-' + postId + ' .column-actions');
    let $countContainer = $('.page .insurance-results-section tr.post-' + postId + ' .column-claim-counter span.claim-count');
    debugger;

    if(claimPassword) {
      $claimPassword.removeClass('error');
      $.ajax({
        url : ajax_params.ajax_url,
        type : 'post',
        data : {
          action : 'validate_claim_password',
          claimPassword : claimPassword,
          postId : postId,
        },
        success : function( response ) {
          let data = JSON.parse(response);

          if(data.result == -1) {
            $cellActions.append( data.html );

            let count = parseInt($countContainer.html(), 10) + 1;
            $countContainer.html(count);
            nco_hide_claim_insurance_verification();
          }
          else {
            $modalBody.append( data.html );
            $claimPassword.addClass('error');
          }

          webStateWaiting(false);
        },
        error : function ( response ) {
          $('#pfb-signup-result').html('<p>Sorry but we were unable to retreive the list of codes.</p>');
          console.log(response);
        },
        beforeSend: function() {
          webStateWaiting(true);
          return true;
        },
      });
    }
    else {
      $claimPassword.addClass('error');
    }
  }

  /* NOT used */
var nco_add_claim_to_insurance = function(event) {
  let $button = $(event.target);
  let postId = $button.attr('data-id');
  let $cell = $button.parents('.actions');
  let $countContainer = $cell.siblings('.column-claim-counter').find('span.claim-count');

  $.ajax({
    url : ajax_params.ajax_url,
    type : 'post',
    data : {
      action : 'add_claim',
      postId : postId,
    },
    success : function( response ) {
      $cell.append( response );
      let count = parseInt($countContainer.html(), 10) + 1;
      $countContainer.html(count);

      webStateWaiting(false);
    },
    error : function ( response ) {
      $('#pfb-signup-result').html('<p>Sorry but we were unable to add a claim count to the insurance at this time.</p>');
      console.log(response);
    },
    beforeSend: function() {
      webStateWaiting(true);
      return true;
    },
  });
}


  /**
  * Disables all links and changes cursor for the website, used in ajax calls.
  */
  var webStateWaiting = function(waiting){
    if(waiting) {
      $('body').css('cursor', 'progress');
    }
    else {
      $('body').css('cursor', 'default');
    }
    
    $('a').each(function() {
      if(!$(this).hasClass('disabled') && waiting && !$(this).hasClass('language-option') && !$(this).hasClass('menu-end-post-denominacion-a')) {
        $(this).addClass('disabled');	
      }
      else if ($(this).hasClass('disabled') && !waiting && !$(this).hasClass('language-option') && !$(this).hasClass('menu-end-post-denominacion-a')) {
        $(this).removeClass('disabled');
      }
    });
  }

  $(document).ready(function () {
    if($('.page-id-3671').length > 0) {
      nco_validate_insurance_activation_form_jquery();
    }
    if($('.page-id-9776').length > 0) {
      $('.page-id-9776 .nco-form.query-form input[type="submit"]').each(function() {
        $(this).on('click', function(event){
          event.preventDefault();
          nco_handle_query_codes(event);
          event.stopPropagation();
        });
      });
    }
  });
} (jQuery) );