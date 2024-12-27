<?= $this->extend('admin/layout/default') ?>
<?= $this->section('content') ?>

<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1><?php echo lang('App.banks') ?></h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="<?php echo url('/') ?>"><?php echo lang('App.home') ?></a></li>
          <li class="breadcrumb-item active"><?php echo lang('App.banks') ?></li>
        </ol>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-sm-9">
      <div class="card">
        <div class="card-header with-border">
          <h3 class="card-title">Add <?php echo lang('App.banks') ?></h3>
        </div>
        <?php echo form_open_multipart('banks/save', ['class' => 'form-validate', 'autocomplete' => 'off', 'method' => 'post']); ?>
        <div class="card-body">
          <div class="form-group">
            <label for="formBank-Bank-Name"><?php echo lang('App.banks_name') ?></label>
            <input type="text" class="form-control" name="name" id="formBank-Bank-Name" required placeholder="<?php echo lang('App.banks_name') ?>" autofocus />
          </div>
        </div>
        <!-- /.card-body -->

        <div class="card-footer">
          <button type="submit" class="btn btn-flat btn-primary"><?php echo lang('App.submit') ?></button>
        </div>
        <?php echo form_close(); ?>
      </div>
      <div class="card">
          <div class="card-header with-border">
              <h3 class="card-title"><?php echo lang('App.banks') ?></h3>
          </div>
          <div class="card-body">
              <table id="example1" class="table table-bordered table-hover table-striped">
                  <thead>
                      <tr>
                          <th><?php echo lang('App.id') ?></th>
                          <th><?php echo lang('App.banks_name') ?></th>
                          <th><?php echo lang('App.action') ?></th>
                      </tr>
                  </thead>
                  <tbody>
                  <?php foreach ($banks as $row): ?>
                    <tr>
                        <td><?= $row->id ?></td>
                        <td><?= $row->name ?></td>
                        <td></td>
                    </tr>
                  <?php endforeach; ?>
                  </tbody>
              </table>
          </div>
      </div>
    </div>
  </div>

</section>
<!-- /.content -->
<?= $this->endSection() ?>
<?= $this->section('js') ?>

<script>
  $(document).ready(function() {
    $('.form-validate').validate();

    //Initialize Select2 Elements
    $('.select2').select2()

  })

  function previewImage(input, previewDom) {

    if (input.files && input.files[0]) {

      $(previewDom).show();

      var reader = new FileReader();

      reader.onload = function(e) {
        $(previewDom).find('img').attr('src', e.target.result);
      }

      reader.readAsDataURL(input.files[0]);
    } else {
      $(previewDom).hide();
    }

  }
</script>

<?= $this->endSection() ?>