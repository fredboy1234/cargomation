<style>
#registrationForm {
  border: 1px solid #c3c3c3;
  border-radius: 5px;
  padding: 20px 15px;
}
</style>
   <div id="stepperForm" class="bs-stepper">
    <div class="bs-stepper-header" role="tablist">
      <div class="step" data-target="#step-1">
        <button type="button" class="step-trigger" role="tab" id="stepperFormTrigger1" aria-controls="step-1">
          <span class="bs-stepper-circle">
            <span class="fas fa-lock" aria-hidden="true"></span>
          </span>
          <span class="bs-stepper-label">Login</span>
        </button>
      </div>
      <div class="bs-stepper-line"></div>
      <div class="step" data-target="#step-2">
        <button type="button" class="step-trigger" role="tab" id="stepperFormTrigger2" aria-controls="step-2">
          <span class="bs-stepper-circle">
            <span class="fas fa-user" aria-hidden="true"></span>
          </span>
          <span class="bs-stepper-label">Contact</span>
        </button>
      </div>
      <div class="bs-stepper-line"></div>
      <div class="step" data-target="#step-3">
        <button type="button" class="step-trigger" role="tab" id="stepperFormTrigger3" aria-controls="step-3">
          <span class="bs-stepper-circle">
            <span class="fas fa-map-marked" aria-hidden="true"></span>
          </span>
          <span class="bs-stepper-label">Address</span>
        </button>
      </div>
      <div class="bs-stepper-line"></div>
      <div class="step" data-target="#final">
        <button type="button" class="step-trigger" role="tab" id="stepperFormTriggerF" aria-controls="final">
          <span class="bs-stepper-circle">
            <span class="fas fa-save" aria-hidden="true"></span>
          </span>
          <span class="bs-stepper-label">Finish</span>
        </button>
      </div>
    </div>
    <div class="bs-stepper-content">
      <div class="row justify-content-md-center">
        <div class="col-lg-5 col-md-5 col-sm-12">
          <form id="registrationForm" class="needs-validation" action="<?= $this->makeUrl("register/_register"); ?>" method="post" onSubmit="return false" novalidate>
            <div id="step-1" role="tabpanel" class="bs-stepper-pane fade" aria-labelledby="stepperFormTrigger1">
              <div class="form-group">
                <label for="inputEmail">Choose role <span class="text-danger font-weight-bold">*</span></label>
                <select id="inputRole" name="role" class="form-control" required>
                  <option value="2">Client Admin</option>
                  <option value="3">Staff</option>
                  <option value="4">Customer</option>
                </select>
                <span id="errorRole" class="text-danger"></span>
              </div>
              <div class="form-group">
                <label for="inputEmail">Email address <span class="text-danger font-weight-bold">*</span></label>
                <input id="inputEmail" type="email" name="email" data-bind="email" class="form-control" placeholder="Enter email" required>
                <span id="errorEmail" class="text-danger"></span>
              </div>
              <div class="form-group">
                <label for="inputPassword">Password <span class="text-danger font-weight-bold">*</span></label>
                <input id="inputPassword" type="password" name="password" class="form-control" placeholder="Password" required>
                <span id="errorPassword" class="text-danger"></span>
              </div>
              <div class="form-group">
                <label for="inputConfirm">Confirm Password <span class="text-danger font-weight-bold">*</span></label>
                <input id="inputConfirm" type="password" name="password_repeat" class="form-control" placeholder="Confirm Password" required>
                <span id="errorConfirm" class="text-danger"></span>
              </div>
              <button class="btn btn-primary btn-next-form mx-auto d-block">Next</button>
            </div>
            <div id="step-2" role="tabpanel" class="bs-stepper-pane fade" aria-labelledby="stepperFormTrigger2">
              <div class="form-group">
                <label for="inputFirstName">First Name <span class="text-danger font-weight-bold">*</span></label>
                <input id="inputFirstName" type="text" name="first-name" data-bind="first-name" class="form-control" placeholder="First Name" required>
                <span id="errorFirstName" class="text-danger"></span>
              </div>
              <div class="form-group">
                <label for="inputLastName">Last Name <span class="text-danger font-weight-bold">*</span></label>
                <input id="inputLastName" type="text" name="last-name" data-bind="last-name" class="form-control" placeholder="Last Name" required>
                <span id="errorLastName" class="text-danger"></span>
              </div>
              <div class="form-group">
                <label for="inputPhone">Phone <span class="text-danger font-weight-bold">*</span></label>
                <input id="inputPhone" type="text" name="phone" data-bind="phone" class="form-control" placeholder="+61 (02) 9876 5432" required>
                <span id="errorPhone" class="text-danger"></span>
              </div>
              <button class="btn btn-primary btn-next-form mx-auto d-block">Next</button>
            </div>
            <div id="step-3" role="tabpanel" class="bs-stepper-pane fade" aria-labelledby="stepperFormTrigger3">
              <div class="form-group">
                <label for="inputAddress">Address<span class="text-danger font-weight-bold">*</span></label>
                <input id="inputAddress" type="text" name="address" data-bind="address" class="form-control" placeholder="123 Sesame Street" required>
                <div class="invalid-feedback">Please fill the address field</div>
              </div>
              <div class="form-group">
                <label for="inputAddress2">Address 2<span class="font-weight-bold"> (Optional)</span></label>
                <input id="inputAddress2" type="text" name="address2" data-bind="address2" class="form-control" placeholder=" ">
              </div>
              <div class="form-group">
                <label for="inputCity">City <span class="text-danger font-weight-bold">*</span></label>
                <input id="inputCity" type="text" name="city" data-bind="city" class="form-control" placeholder="Sydney" required>
                <div class="invalid-feedback">Please fill the city field</div>
              </div>
              <div class="form-group">
                <label for="inputState">State <span class="text-danger font-weight-bold">*</span></label>
                <input id="inputState" type="text" name="state" data-bind="state" class="form-control" placeholder="State" required>
                <div class="invalid-feedback">Please fill the state field</div>
              </div>
              <div class="form-group">
                <label for="inputZip">ZIP Code <span class="text-danger font-weight-bold">*</span></label>
                <input id="inputZip" type="text" name="zip" data-bind="zip" class="form-control" placeholder="Ex. 12345" required>
                <div class="invalid-feedback">Please fill the ZIP code field</div>
              </div>
              <button class="btn btn-primary btn-next-form mx-auto d-block">Next</button>
            </div>
            <div id="final" role="tabpanel" class="bs-stepper-pane fade" aria-labelledby="stepperFormTriggerF">
              <div class="mx-auto d-block">
                <p>Please review the following information you've given before submitting.</p>
                <label for="email">Email: </label> <span data-update="email"></span> <br>
                <label for="firstname">First Name: </label> <span data-update="first-name"></span> <br>
                <label for="lastname">Last name: </label> <span data-update="last-name"></span> <br>
                <label for="phone">Phone: </label> <span data-update="phone"></span> <br>
                <label for="address">Address: </label> <span data-update="address"></span> <br>
                <label for="address2">Address 2: </label> <span data-update="address2"></span> <br>
                <label for="city">City: </label> <span data-update="city"></span> <br>
                <label for="state">State: </label> <span data-update="state"></span> <br>
                <label for="zip">ZIP Code: </label> <span data-update="zip"></span> <br>
              </div>
              <button id="submit" class="btn btn-primary mx-auto d-block">Submit</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>