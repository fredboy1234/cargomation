<style>
#registrationForm {
  border: 1px solid #c3c3c3;
  border-radius: 5px;
  padding: 20px 15px;
}
</style>
<?php
$user_count = $this->user->user_count[0]->count;
$user_max = $this->user->account_info[0]->max_users;
$contact_id = "";
if(isset($this->contact_info)) {
  $contact_info = $this->contact_info[0];
  $contact_id = $contact_info->id;
}
?>

<?php if($user_count >= $user_max && $this->role->id != 1) : ?>
  <div>
    <center>Note: Maximum number of user reach, please upgrade your subscription plan.</center>
  </div>
<?php else: ?>
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
        <div class="col-lg-5 col-md-8 col-sm-12">
          <form id="registrationForm" class="needs-validation" action="<?= $this->makeUrl("register/_register/" . $contact_id); ?>" method="post" onSubmit="return false" novalidate>
            <div id="step-1" role="tabpanel" class="bs-stepper-pane fade" aria-labelledby="stepperFormTrigger1">
              <div class="form-group">
                <label for="inputEmail">Choose role <span class="text-danger font-weight-bold">*</span></label>
                <select id="inputRole" name="role" class="form-control" required>
                  <?php if($this->role->id == 1) : ?>
                  <option value="1">Super Admin</option>
                  <?php endif; ?>
                  <?php if($this->role->id == 2 || $this->role->id == 1) : ?>
                  <option value="2">Client Admin</option>
                  <?php endif; ?>
                  <option value="3">Staff</option>
                  <option value="4">Customer</option>
                </select>
                <span id="errorRole" class="text-danger"></span>
              </div>
              <div id="code" class="form-group">
                <label for="inputCode">Organization Code <span class="text-danger font-weight-bold">*</span></label>
                <input id="inputCode" type="text" name="organization_code" data-bind="organization_code" class="form-control" placeholder="Enter organization code" 
                <?= (empty($contact_info->organization_code))  ? "" : 'value="' . $contact_info->organization_code . '"'; ?>
                required>
                <span id="errorCode" class="text-danger"></span>
              </div>
              <div id="company" class="form-group">
                <label for="inputCompany">Company Name <span class="text-danger font-weight-bold"></span></label>
                <input id="inputCompany" type="text" name="company_name" data-bind="company_name" class="form-control" placeholder="Enter company name" 
                <?= (empty($contact_info->company_name))  ? "" : 'value="' . $contact_info->company_name . '"'; ?>>
                <span id="errorCompany" class="text-danger"></span>
              </div>
              <div class="form-group">
                <label for="inputEmail">Email address <span class="text-danger font-weight-bold">*</span></label>
                <input id="inputEmail" type="email" name="email" data-bind="email" class="form-control" placeholder="Enter email" 
                <?= (empty($contact_info->email_address))  ? "" : 'value="' . $contact_info->email_address . '"'; ?>
                required>
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
              <div class="form-group row">
                  <div class="mx-auto d-block">
                    <button class="btn btn-primary btn-next-form mx-auto">Next</button>
                  </div>
              </div>
            </div>
            <div id="step-2" role="tabpanel" class="bs-stepper-pane fade" aria-labelledby="stepperFormTrigger2">
              <div class="form-group">
                <label for="inputFirstName">First Name <span class="text-danger font-weight-bold">*</span></label>
                <input id="inputFirstName" type="text" name="first-name" data-bind="first-name" class="form-control" placeholder="First Name" 
                <?= (empty($contact_info->first_name))  ? "" : 'value="' . $contact_info->first_name . '"'; ?>
                required>
                <span id="errorFirstName" class="text-danger"></span>
              </div>
              <div class="form-group">
                <label for="inputLastName">Last Name <span class="text-danger font-weight-bold">*</span></label>
                <input id="inputLastName" type="text" name="last-name" data-bind="last-name" class="form-control" placeholder="Last Name" 
                <?= (empty($contact_info->last_name))  ? "" : 'value="' . $contact_info->last_name . '"'; ?>
                required>
                <span id="errorLastName" class="text-danger"></span>
              </div>
              <div class="form-group">
                <label for="inputPhone">Phone <span class="text-danger font-weight-bold">*</span></label>
                <input id="inputPhone" type="text" name="phone" data-bind="phone" class="form-control" placeholder="+61 (02) 9876 5432" 
                <?= (empty($contact_info->phone))  ? "" : 'value="' . $contact_info->phone . '"'; ?>
                required>
                <span id="errorPhone" class="text-danger"></span>
              </div>
              <div class="form-group row">
                  <div class="mx-auto d-block">
                    <button class="btn btn-primary btn-prev-form mx-auto">Previous</button>
                    <button class="btn btn-primary btn-next-form mx-auto">Next</button>
                  </div>
              </div>
            </div>
            <div id="step-3" role="tabpanel" class="bs-stepper-pane fade" aria-labelledby="stepperFormTrigger3">
              <div class="form-group">
                <label for="inputAddress">Address<span class="text-danger font-weight-bold">*</span></label>
                <input id="inputAddress" type="text" name="address" data-bind="address" class="form-control" placeholder="123 Sesame Street" 
                <?= (empty($contact_info->address))  ? "" : 'value="' . $contact_info->address . '"'; ?>
                required>
                <div class="invalid-feedback">Please fill the address field</div>
              </div>
              <?php if(false): ?>
              <div class="form-group">
                <label for="inputAddress2">Address 2<span class="font-weight-bold"> (Optional)</span></label>
                <input id="inputAddress2" type="text" name="address2" data-bind="address2" class="form-control" placeholder=" ">
              </div>
              <?php endif; ?>
              <div class="form-group">
                <label for="inputCity">City <span class="text-danger font-weight-bold">*</span></label>
                <input id="inputCity" type="text" name="city" data-bind="city" class="form-control" placeholder="Sydney" 
                <?= (empty($contact_info->city))  ? "" : 'value="' . $contact_info->city . '"'; ?>
                required>
                <div class="invalid-feedback">Please fill the city field</div>
              </div>
              <div class="form-group">
                <label for="inputState">State <span class="text-danger font-weight-bold">*</span></label>
                <input id="inputState" type="text" name="state" data-bind="state" class="form-control" placeholder="State" required>
                <div class="invalid-feedback">Please fill the state field</div>
              </div>
              <div class="form-group">
                <label for="inputZip">ZIP Code <span class="text-danger font-weight-bold">*</span></label>
                <input id="inputZip" type="text" name="zip" data-bind="zip" class="form-control" placeholder="Ex. 12345" 
                <?= (empty($contact_info->postcode))  ? "" : 'value="' . $contact_info->postcode . '"'; ?>
                required>
                <div class="invalid-feedback">Please fill the ZIP code field</div>
              </div>
              <div class="form-group row">
                  <div class="mx-auto d-block">
                    <button class="btn btn-primary btn-prev-form mx-auto">Previous</button>
                    <button class="btn btn-primary btn-next-form mx-auto">Next</button>
                  </div>
              </div>
            </div>
            <div id="final" role="tabpanel" class="bs-stepper-pane fade" aria-labelledby="stepperFormTriggerF">
              <form class="form-horizontal">
                <div class="callout callout-warning">
                  <h5>Almost done!</h5>

                  <p>Please review the following information you've given before submitting.</p>
                </div>
                <div class="form-group row">
                    <label for="email" class="col-sm-6 col-form-label">Email</label>
                    <div class="col-sm-6 mt-2">
                        <span data-update="email">
                        <?= (empty($contact_info->email_address))  ? "" : $contact_info->email_address; ?>
                        </span>
                    </div>
                </div>
                <div id="org_code" class="form-group row" style="">
                    <label for="organization_code" class="col-sm-6 col-form-label">Organization Code</label>
                    <div class="col-sm-6 mt-2">
                        <span data-update="organization_code">
                        <?= (empty($contact_info->organization_code))  ? "" : $contact_info->organization_code; ?>
                        </span>
                    </div>
                </div>
                <div id="comp_name" class="form-group row" style="">
                    <label for="company_name" class="col-sm-6 col-form-label">Company Name</label>
                    <div class="col-sm-6 mt-2">
                        <span data-update="company_name">
                        <?= (empty($contact_info->company_name))  ? "" : $contact_info->company_name; ?>
                        </span>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="firstname" class="col-sm-6 col-form-label">First Name:</label>
                    <div class="col-sm-6 mt-2">
                      <span data-update="first-name">
                      <?= (empty($contact_info->first_name))  ? "" : $contact_info->first_name; ?>
                      </span>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="lastname" class="col-sm-6 col-form-label">Last Name:</label>
                    <div class="col-sm-6 mt-2">
                      <span data-update="last-name">
                      <?= (empty($contact_info->last_name))  ? "" : $contact_info->last_name; ?>
                      </span>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="phone" class="col-sm-6 col-form-label">Phone:</label>
                    <div class="col-sm-6 mt-2">
                      <span data-update="phone">
                      <?= (empty($contact_info->phone)) ? "" : $contact_info->phone; ?>
                      </span>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="address" class="col-sm-6 col-form-label">Address:</label>
                    <div class="col-sm-6 mt-2">
                      <span data-update="address">
                      <?= (empty($contact_info->address)) ? "" : $contact_info->address; ?>
                      </span>
                    </div>
                </div>
                <div class="form-group row" style="display: none">
                    <label for="address2" class="col-sm-6 col-form-label">Address 2:</label>
                    <div class="col-sm-6 mt-2">
                      <span data-update="address2"></span>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="city" class="col-sm-6 col-form-label">City:</label>
                    <div class="col-sm-6 mt-2">
                      <span data-update="city">
                      <?= (empty($contact_info->city))  ? "" : $contact_info->city; ?>
                      </span>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="state" class="col-sm-6 col-form-label">State:</label>
                    <div class="col-sm-6 mt-2">
                      <span data-update="state"></span>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="zip" class="col-sm-6 col-form-label">ZIP code:</label>
                    <div class="col-sm-6 mt-2">
                      <span data-update="zip">
                      <?= (empty($contact_info->postcode))  ? "" : $contact_info->postcode; ?>
                      </span>
                    </div>
                </div>
                <div class="form-group row">
                  <div class="mx-auto d-block">
                    <?php 
                      if(!empty($contact_info->id) && false) {
                        echo '<input type="hidden" name="id" value="' . $contact_info->id . '">';
                        echo '<input type="hidden" name="code" value="' . $contact_info->organization_code . '">';
                        echo '<input type="hidden" name="company" value="' . $contact_info->company_name . '">';
                      }
                    ?>
                    <button class="btn btn-primary btn-edit-form mx-auto">Edit</button>
                    <button id="submit" class="btn btn-primary mx-auto">Submit</button>
                  </div>
                </div>
              </form>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
<?php endif; ?>
</div>