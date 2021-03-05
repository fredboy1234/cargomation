  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <!-- SELECT2 EXAMPLE -->
      <div class="card card-default">
        <div class="card-header">
          <h3 class="card-title"></h3>

          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
              <i class="fas fa-minus"></i>
            </button>
            <button type="button" class="btn btn-tool" data-card-widget="remove" disabled>
              <i class="fas fa-times"></i>
            </button>
          </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
          <form action="">
            <div class="row">
              <div class="col-md-6">
                <div class="form-row">
                  <div class="form-group col-md-6">
                    <label>From</label>
                    <select class="form-control select2bs4" style="width: 100%;" id="currency_code1" name="currency_code1">
                      <option value="AUD" selected="selected">AUD - Australian Dollar</option>
                    </select>
                  </div>
                  <!-- /.form-group col-md-3 -->
                  <div class="form-group col-md-6">
                    <label for="amount">Amount</label>
                    <input type="amount" class="form-control" id="amount" placeholder="Enter amount" value="1" onkeyup="getExchange(this)">
                  </div>
                  <!-- /.form-group col-md-3 -->
                </div>
                <!-- /.form-row -->
              </div>
              <!-- /.col-md-6 -->
              <div class="col-md-6">
                <div class="form-row">
                  <div class="form-group col-md-6">
                    <label>To</label>
                    <select class="form-control select2bs4" style="width: 100%;" id="currency_code" name="currency_code" onchange="getExchange(this)">
                      <option value="USD" selected="selected">USD - United States Dollar</option>
                      <?php foreach ($this->list_currency as $value): ?>
                      <option value="<?= $value->currency_code; ?>"><?= $value->currency_code . " - " . $value->currency_desc; ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <!-- /.form-group col-md-3 -->
                  <div class="form-group col-md-6">
                    <label for="result">Exchange</label>
                    <input type="result" class="form-control" id="result" placeholder="0.00">
                  </div>
                  <!-- /.form-group col-md-3 -->
                </div>
                <!-- /.form-row -->
              </div>
              <!-- /.col-md-6 -->
            </div>
            <div id="loader-wrapper" class="justify-content-center">
              <center>
                <div class="spinner-border" role="status">
                  <span class="sr-only">Loading...</span>
                </div>
              </center>
            </div>
          </form>
          <!-- /.form -->
        </div>
        <!-- /.card-body -->
        <div class="card-footer">
          This page was using Westpac Banking for converting rates. Visit <a href="https://www.westpac.com.au/business-banking/services/foreign-exchange-rates/">FOREIGN EXCHANGE RATES</a> for more information about
          the exchange.
        </div>
      </div>
      <!-- /.card -->
    </div>
  </section>
</div>


