  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <!-- SELECT2 EXAMPLE -->
      <div class="card card-default">
        <div class="card-header">
          <h3 class="card-title">Convert</h3>

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
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>From</label>
                <select class="form-control select2bs4" style="width: 100%;" id="currency_code1" name="currency_code1">
                  <option value="AUD" selected="selected">AUD - Australian Dollar</option>
                </select>
              </div>
              <!-- /.form-group -->
            </div>
            <!-- /.col -->
            <div class="col-md-6">
              <div class="form-group">
                <label for="amount">Amount</label>
                <input type="amount" class="form-control" id="amount" placeholder="Enter amount" value="1" onchange="getExchange(this)">
              </div>
            </div>
            <!-- /.col -->
            <div class="col-md-6">
              <div class="form-group">
                  <label>To</label>
                  <select class="form-control select2bs4" style="width: 100%;" id="currency_code" name="currency_code" onchange="getExchange(this)">
                    <option value="USD" selected="selected">USD - United States Dollar</option>
                    <?php foreach ($this->list_currency as $value): ?>
                    <option value="<?= $value->currency_code; ?>"><?= $value->currency_code . " - " . $value->currency_desc; ?></option>
                    <?php endforeach; ?>
                  </select>
              </div>
              <!-- /.form-group -->
              </div>
              <!-- /.col -->
            <div class="col-md-6">
              <div class="form-group">
                <label for="result">Exchange</label>
                <input type="result" class="form-control" id="result" placeholder="0.00">
              </div>
            </div>
            <!-- /.col -->
          </div>
          <!-- /.row -->
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


