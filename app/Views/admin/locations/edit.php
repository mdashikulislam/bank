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
                    <li class="breadcrumb-item active"><?php echo lang('App.locations') ?></li>
                </ol>
            </div>
        </div>
    </div>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-lg-8 col-12">
            <div class="card">
                <div class="card-header with-border">
                    <h3 class="card-title"><?php echo lang('App.locations_edit') ?></h3>
                </div>
                <?php echo form_open('locations/update/'.$location->id, ['class' =>
                    'form-validate', 'autocomplete' => 'off', 'method' => 'post']); ?>
                <div class="card-body">
                    <div class="form-group">
                        <label for="formLocation-Location-Name"><?php echo lang('App.locations_name') ?></label>
                        <input value="<?= set_value('name',$location->name) ?>" type="text" class="form-control" name="name" id="formLocation-Location-Name" required placeholder="<?php echo lang('App.locations_name') ?>" />
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col"><a href="<?php echo url('/locations/add') ?>" onclick="return confirm('Are you sure you want to leave?')" class="btn btn-flat btn-danger"><?php echo lang('App.cancel') ?></a></div>
                        <button type="submit" class="btn btn-flat btn-primary"><?php echo lang('App.submit') ?></button>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</section>
<!-- /.content -->
<?= $this->endSection() ?>
<?= $this->section('js') ?>
<?= $this->endSection() ?>
