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
  var nco_validate_insurance_activation_form = function () {
    let $form = $('.page-id-3671 .insurance-form-section .nco-form');
    let $inputs = $form.find('input:not([type="submit"]), select');
    let $submit = $form.find('input[type="submit"]');

    $inputs.each(function() {
      $(this).on('input', function() {
        if($(this).attr('name') == 'insurance_activation_code' ) {
          let code = $(this).val();
          let codeValid;
          let $input = $(this);
          $.ajax({
            url : ajax_params.ajax_url,
            type : 'post',
            data : {
              action : 'validate_code_activation',
              code : code,
            },
            success : function( response ) {
              codeValid = response;
              if(response == true) {
                codeValid = true;
              }
              else {
                codeValid = false;
              }

              if( !codeValid ) {
                $input[0].setCustomValidity('Code invalid');
                $input.addClass('validation_error');
              }
              else{
                $input[0].setCustomValidity('');
                $input.removeClass('validation_error');
              }

              webStateWaiting(false);
            },
            error : function ( response ) {
              $('#pfb-signup-result').html('<p>Sorry but we were unable validate the code try again latter.</p>');
            },
            beforeSend: function() {
              webStateWaiting(true);
              return true;
            },
          });
        }
        if ($(this)[0].validity.valid) {
          $(this).siblings('.error-tip').addClass('hidden');
          $(this).removeClass('validation_error');
        }
        else {
          $(this).addClass('validation_error');
        }
      });
    });

    $form.on('submit', function(event) {
      let valid = true;
      $inputs.each(function() {
        if(!$(this)[0].validity.valid) {
          valid = false;
          
          $(this).toggleClass('validation_error');
          let $errorTip = $(this).siblings('.error-tip');

          if($errorTip.length === 0 && $(this).attr('type') !== 'checkbox' ) {
            $htmlObject = $('<div></div>')
              .addClass('error-tip');
            
            if($(this)[0].validity.valueMissing) {
              $htmlObject.html('Este campo es requerido.');
            }
            if($(this)[0].validity.typeMismatch) {
              if($(this).attr('type') == 'email'){
                $htmlObject.html('Este campo debe ser un correo electrónico.');
              }
            }
            if($(this)[0].validity.customError) {
              if($(this).attr('name') == 'insurance_activation_code'){
                $htmlObject.html('No hemos podido encontrar el código en la base de datos');
              }
            }

            $(this).parent('.input-container').append($htmlObject);
          }
        }
      });

      if(!valid) {
        let $error = $(this).parent('#tab2_login').find('.error');

        if($error.length === 0) {
          $htmlObject = $('<div></div>')
              .addClass('error')
              .html('Uno o mas de los campos de la forma tienen un error, ' + 
              'por favor revise la información y trate de enviar la forma de nuevo');
          $(this).parent('#tab2_login').append($htmlObject);
        }
        else {
          $error.removeClass('hidden');
        }

        event.preventDefault();
        event.stopPropagation();
      }
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
      //nco_validate_insurance_activation_form();
      nco_validate_insurance_activation_form_jquery();

    }
  });
} (jQuery) );