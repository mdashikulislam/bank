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
                <h1><?php echo lang('App.attributes') ?></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="<?php echo url('/') ?>"><?php echo lang('App.home') ?></a>
                    </li>
                    <li class="breadcrumb-item active"><?php echo lang('App.attributes') ?></li>
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
                        <?php echo lang('App.attributes_edit') ?>
                    </h3>
                </div>
                <?php echo form_open_multipart('attributes/update/'.$attribute->id, ['class' =>
                    'form-validate', 'autocomplete' => 'off', 'method' => 'post']); ?>
                <div class="card">
                    <div class="card-body">
                        <!-- Bank Dropdown -->
                        <div class="form-group">
                            <label for="bank_id"><?php echo lang('App.banks_name'); ?></label>
                            <select name="bank_id" id="bank_id" class="form-control select2" required>
                                <?php echo getBankDropdown(set_value('bank_id',$attribute->bank_id)); ?>
                            </select>
                        </div>
                        <div class="d-flex justify-content-between align-content-center form-group mb-3">
                            <label for="formBank-Bank-Name"><?php echo lang('App.attributes'); ?></label>
                            <a href="#" class="btn btn-primary btn-sm add-attribute" title="Add Attribute">
                                <i class="fa fa-plus"></i>
                            </a>
                        </div>
                        <div id="attributes-container">
                            <?php
                                $names = json_decode($attribute->name);
                                foreach ($names as $name):
                            ?>
                            <div class="form-group d-flex justify-content-between align-items-center gap">
                                <input type="text" name="name[]" class="form-control" value="<?= $name ?>" placeholder="Enter Attribute Name" required>
                                <a href="#" class="btn btn-danger remove-attribute" title="Remove Attribute"><i class="fa fa-trash"></i></a>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="card-footer">
                        <button type="submit" class="btn btn-flat btn-primary"><?php echo lang('App.submit'); ?></button>
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
<script>
    $(document).ready(function () {
        $(".select2").select2();
    });

    document.addEventListener('DOMContentLoaded', function () {
        const attributesContainer = document.getElementById('attributes-container');

        // Add new attribute row
        document.querySelector('.add-attribute').addEventListener('click', function (e) {
            e.preventDefault();

            const newAttribute = document.createElement('div');
            newAttribute.className = 'form-group mb-3';
            newAttribute.innerHTML = `
                <div class="form-group d-flex justify-content-between align-items-center gap">
                    <input type="text" name="name[]" class="form-control" placeholder="Enter Attribute Name" required>
                    <a href="#" class="btn btn-danger remove-attribute" title="Remove Attribute">
                        <i class="fa fa-trash"></i>
                    </a>
                </div>`;
            attributesContainer.appendChild(newAttribute);
        });

        // Remove attribute row
        attributesContainer.addEventListener('click', function (e) {
            if (e.target && e.target.closest('.remove-attribute')) {
                e.preventDefault();
                const row = e.target.closest('.form-group');
                row.remove();
            }
        });
    });
</script>
<?= $this->endSection() ?>
