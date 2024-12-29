<?= $this->extend('admin/layout/default') ?>
<?= $this->section('content') ?>
<style>
    .gap{
        gap: 20px;
    }
</style>
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1><?php echo lang('App.bank_excels') ?></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="<?php echo url('/') ?>"><?php echo lang('App.home') ?></a>
                    </li>
                    <li class="breadcrumb-item active"><?php echo lang('App.bank_excels') ?></li>
                </ol>
            </div>
        </div>
    </div>
</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-sm-9">
            <div class="card">
                <div class="card-header with-border">
                    <h3 class="card-title">
                        Add
                        <?php echo lang('App.bank_excels') ?>
                    </h3>
                </div>
                <?php echo form_open_multipart('excels/save', ['class' =>
                    'form-validate', 'autocomplete' => 'off', 'method' => 'post','encrypt'=>'multipart-form-data']); ?>
                <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="bank_id"><?php echo lang('App.banks_name'); ?></label>
                            <select name="bank_id" id="bank_id" class="form-control select2" required>
                                <?php echo getBankDropdown(set_value('bank_id')); ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="formBank-Bank-Name"><?php echo lang('App.select_excels'); ?></label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input required accept=".xlsx,.csv" type="file" name="file" class="custom-file-input" id="exampleInputFile">
                                    <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col"><a href="<?php echo url('/excels') ?>" onclick="return confirm('Are you sure you want to leave?')" class="btn btn-flat btn-danger"><?php echo lang('App.cancel') ?></a></div>
                            <button type="submit" class="btn btn-flat btn-primary"><?php echo lang('App.submit') ?></button>
                        </div>
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
<script src="<?php echo assets_url('admin') ?>/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
<script>
    $(document).ready(function () {
        $(".form-validate").validate();
        $(".select2").select2();
        $(function () {
            bsCustomFileInput.init();
        });
    });
</script>
<?= $this->endSection() ?>
