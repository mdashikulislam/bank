<?= $this->extend('admin/layout/default') ?>
<?= $this->section('content') ?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1><?php echo lang('App.locations') ?></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="<?php echo url('/') ?>"><?php echo lang('App.home') ?></a>
                    </li>
                    <li class="breadcrumb-item active"><?php echo lang('App.banks') ?></li>
                </ol>
            </div>
        </div>
    </div>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-lg-8 col-12">
            <?php if (hasPermissions('banks_edit')): ?>
            <div class="card">
                <div class="card-header with-border">
                    <h3 class="card-title"><?php echo lang('App.banks_edit') ?></h3>
                </div>
                <?php echo form_open('banks/update/'.$bank->id, ['class' =>
                    'form-validate', 'autocomplete' => 'off', 'method' => 'post']); ?>
                <div class="card-body">
                    <div class="form-group">
                        <label for="location_id"><?php echo lang('App.locations') ?></label>
                        <select name="location_id" id="location_id" class="form-control select2" required>
                            <?php echo getLocationDropdown(set_value('location_id',$bank->location_id))?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="formBank-Bank-Name"><?php echo lang('App.banks_name') ?></label>
                        <input value="<?= set_value('name',$bank->name) ?>" type="text" class="form-control" id="formBank-Bank-Name" name="name"  required placeholder="<?php echo lang('App.banks_name') ?>" />
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col"><a href="<?php echo url('/banks/add') ?>" onclick="return confirm('Are you sure you want to leave?')" class="btn btn-flat btn-danger"><?php echo lang('App.cancel') ?></a></div>
                        <button type="submit" class="btn btn-flat btn-primary"><?php echo lang('App.submit') ?></button>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>
<!-- /.content -->
<?= $this->endSection() ?>
<?= $this->section('js') ?>
<script>
    $(document).ready(function () {
        $(".form-validate").validate();
        //Initialize Select2 Elements
        $(".select2").select2();
    });
</script>
<?= $this->endSection() ?>
