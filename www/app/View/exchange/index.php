<?php //var_dump($this->currency); ?>
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
                    <label>Currency I have</label>
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
                    <label>Currency I want</label>
                    <select class="form-control select2bs4" style="width: 100%;" id="currency_code" name="currency_code" onchange="getExchange(this)">
                      <?php foreach ($this->list_currency as $value): ?>
                      <option value="<?= $value->currency_code; ?>" <?= ($value->currency_code != "USD") ?: "selected" ?>>
                        <?= $value->currency_code . " - " . $value->currency_desc; ?>
                      </option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <!-- /.form-group col-md-3 -->
                  <div class="form-group col-md-6">
                    <label for="result">Exchange</label>
                    <input type="result" class="form-control" id="result" placeholder="0.00" disabled="disabled">
                  </div>
                  <!-- /.form-group col-md-3 -->
                </div>
                <!-- /.form-row -->
              </div>
              <!-- /.col-md-6 -->
            </div>
            <!-- <div id="loader-wrapper" class="justify-content-center">
              <center>
                <div class="spinner-border" role="status">
                  <span class="sr-only">Loading...</span>
                </div>
              </center>
            </div> -->
          </form>
          <!-- /.form -->
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">ForEx Table</h3>

                  <div class="card-tools">
                    <form>
                      <div class="form-row align-items-center">
                        <div class="col-auto">
                          <label class="sr-only" for="inlineFormInputGroup">Daterange</label>
                          <div class="input-group mb-2 input-group-sm">
                            <div class="input-group-prepend">
                              <div class="input-group-text"><i class="fa fa-calendar" aria-hidden="true"></i></div>
                            </div>
                            <input type="text" class="form-control" id="daterange" placeholder="01/01/2001">
                            <input type="hidden" class="form-control" id="start" placeholder="01/01/2001">
                            <input type="hidden" class="form-control" id="end" placeholder="01/01/2001">
                          </div>
                        </div>
                        <div class="col-auto">
                          <label class="sr-only" for="inlineFormInputGroup">Search</label>
                          <div class="input-group mb-2 input-group-sm">
                            <input type="text" class="form-control" id="table_search" placeholder="Search">
                            <div class="input-group-append">
                              <div class="input-group-text"><i class="fa fa-search" aria-hidden="true"></i></div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive p-0">
                  <table id="forex" class="table table-head-fixed text-nowrap">
                    <thead>
                      <tr>
                        <th>Currency</th>
                        <th>Buy</th>
                        <th>Sell</th>
                        <th>Effective Date</th>
                        <th>Effective Time</th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($this->currency as $key => $value) : ?>
                      <tr>
                        <td><img src="https://cargomation.com/img/flag/flag-<?= strtolower($value->currency_code) ?>.png" alt="<?= strtolower($value->currency_code) ?>" style="width: 28px"> 
                        <?= $value->currency_code . " - " . $value->currency_desc; ?></td>
                        <td><?= $value->TTBuy ?></td>
                        <td><?= $value->TTSell ?></td>

                        <td><?= date_format(date_create($value->EffectiveDate), 'j F Y') ?></td>
                        <td><?= date_format(date_create($value->EffectiveTime), 'H:i:s A') ?></td>
                      </tr>
                    <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>
                <!-- /.card-body -->
              </div>
              <!-- /.card -->
            </div>
          </div>
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


