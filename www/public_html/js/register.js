var stepperForm
document.addEventListener('DOMContentLoaded', function () {
  var stepperFormEl = document.querySelector('#stepperForm')
  stepperForm = new Stepper(stepperFormEl, {
    animation: true
  })
  var btnNextList = [].slice.call(document.querySelectorAll('.btn-next-form'))
  var btnPrevList = [].slice.call(document.querySelectorAll('.btn-prev-form'))
  var btnEditList = [].slice.call(document.querySelectorAll('.btn-edit-form'))
  var stepperPanList = [].slice.call(stepperFormEl.querySelectorAll('.bs-stepper-pane'))
  var form = stepperFormEl.querySelector('.bs-stepper-content form')
  btnNextList.forEach(function (btn) {
    btn.addEventListener('click', function () {
      stepperForm.next()
    })
  })
  btnEditList.forEach(function (btn) {
    btn.addEventListener('click', function () {
      stepperForm.to(1)
    })
  })
  btnPrevList.forEach(function (btn) {
    btn.addEventListener('click', function () {
      stepperForm.previous()
    })
  })
  stepperFormEl.addEventListener('show.bs-stepper', function (event) {
    form.classList.remove('was-validated')
    var nextStep = event.detail.indexStep
    var currentStep = nextStep
    if (currentStep > 0) {
      currentStep--
    }
    var stepperPan = stepperPanList[currentStep]
    // if ((stepperPan.getAttribute('id') === 'step-1' && !inputEmail.value.length)
    // || (stepperPan.getAttribute('id') === 'step-2' && !inputPassword.value.length)) {
    //   event.preventDefault()
    //   form.classList.add('was-validated')
    // } 

    // Login Validation 
    var errorEmail = ''
    var errorPassword = ''
    var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/
    if($.trim($('#inputEmail').val()).length == 0 && stepperPan.getAttribute('id') === 'step-1') {
        errorEmail = 'Email is required'
        $('#errorEmail').text(errorEmail)
        event.preventDefault()
        form.classList.add('was-validated')
    } else {
        if (!filter.test($('#inputEmail').val()) && stepperPan.getAttribute('id') === 'step-1') {
            errorEmail = 'Invalid Email'
            $('#errorEmail').text(errorEmail)
            event.preventDefault()
            form.classList.add('was-validated')
        } else {
            errorEmail = ''
            $('#errorEmail').text(errorEmail)
        }
    }
    if($.trim($('#inputPassword').val()).length == 0 && stepperPan.getAttribute('id') === 'step-1') {
        errorPassword = 'Password is required'
        $('#errorPassword').text(errorPassword)
        event.preventDefault()
        form.classList.add('was-validated')
    } else {
        errorPassword = ''
        $('#errorPassword').text(errorPassword)
    }
    if($.trim($('#inputConfirm').val()).length == 0 && stepperPan.getAttribute('id') === 'step-1') {
        errorConfirm = 'Confirm Password is required'
        $('#errorConfirm').text(errorConfirm)
        event.preventDefault()
        form.classList.add('was-validated')
    } else {
        if(($.trim($('#inputPassword').val()) != $.trim($('#inputConfirm').val())) && stepperPan.getAttribute('id') === 'step-1' ) {
            errorConfirm = 'Confirm Password does not match'
            $('#errorConfirm').text(errorConfirm)
            event.preventDefault()
            form.classList.add('was-validated')
        } else {
            errorConfirm = ''
            $('#errorConfirm').text(errorConfirm)
        }
    }
    // Concact Info Validation
    var errorFirstName = '';
    var errorLastName = '';
    var errorPhone = '';
    var phoneValidation = /^(\+?\(61\)|\(\+?61\)|\+?61|\(0[1-9]\)|0[1-9])?( ?-?[0-9]){7,9}$/gm;
    if ($.trim($('#inputFirstName').val()).length == 0 && stepperPan.getAttribute('id') === 'step-2') {
        errorFirstName = 'First Name is required';
        $('#errorFirstName').text(errorFirstName);
        event.preventDefault()
        form.classList.add('was-validated')
    } else {
        errorFirstName = '';
        $('#errorFirstName').text(errorFirstName);
    }
    if ($.trim($('#inputLastName').val()).length == 0 && stepperPan.getAttribute('id') === 'step-2') {
        errorLastName = 'Last Name is required';
        $('#errorLastName').text(errorLastName);
        event.preventDefault()
        form.classList.add('was-validated')
    } else {
        errorLastName = '';
        $('#errorLastName').text(errorLastName);
    }
    if ($.trim($('#inputPhone').val()).length == 0 && stepperPan.getAttribute('id') === 'step-2') {
        errorPhone = 'Mobile Number is required';
        $('#errorPhone').text(errorPhone);
        event.preventDefault()
        form.classList.add('was-validated')
    } else {
        if (!phoneValidation.test($('#inputPhone').val()) && stepperPan.getAttribute('id') === 'step-2') {
            errorPhone = 'Invalid Mobile Number';
            $('#errorPhone').text(errorPhone);
            event.preventDefault()
            form.classList.add('was-validated')
        } else {
            errorPhone = '';
            $('#errorPhone').text(errorPhone);
        }
    }
    // Address Validation
    var errorAddress = '';
    if ($.trim($('#inputAddress').val()).length == 0 && stepperPan.getAttribute('id') === 'step-3') {
        errorAddress = 'Address is required';
        $('#errorAddress').text(errorAddress);
        event.preventDefault()
        form.classList.add('was-validated')
    } else {
        errorAddress = '';
        $('#errorAddress').text(errorAddress);
    }
    
  })
})


document.getElementById('submit').addEventListener('click', function (event) {
    var registrationForm = document.getElementById("registrationForm")
    console.log(registrationForm.action)
    $.ajax({
        type: "POST",
        url: registrationForm.action,
        data: $('#registrationForm').serialize(),
        beforeSend: function() {
            $('#submit').prop('disabled', true);
            $("#final").prepend('<center id="loader"><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span></center>');
        },
        success: function(data) {
            console.log(data)
            $('#loader').empty().html('<h4>Register complete!</h4>')
        }
    })
})

// Declare a global object to store view data.
var viewData = {};

$(document).ready(function () {

    console.log('running...')

    // Update the viewData object with the current field keys and values.
    function updateViewData(key, value) {
        viewData[key] = value;
    }

    // Register all bindable elements
    function detectBindableElements() {
                var bindableEls;

                bindableEls = $('[data-bind]');

                // Add event handlers to update viewData and trigger callback event.
                bindableEls.on('change', function () {
                    var $this;

                    $this = $(this);

                    updateViewData($this.data('bind'), $this.val());

                    $(document).trigger('updateDisplay');
                });

                // Add a reference to each bindable element in viewData.
        bindableEls.each(function () {
            updateViewData($(this), $(this).val());
        });

    }

    // Trigger this event to manually update the list of bindable elements, useful when dynamically loading form fields.
    $(document).on('updateBindableElements', detectBindableElements);

    detectBindableElements();
});

function updateDisplay() {
    var updateEls;
    updateEls = $('[data-update]');

    updateEls.each(function () {
        $(this).text(viewData[$(this).data('update')]);
    });
}

// Run updateDisplay on the callback.
$(document).on('updateDisplay', updateDisplay);