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
            <?php if (hasPermissions('locations_add')) ?>
            <div class="card">
                <div class="card-header with-border">
                    <h3 class="card-title"><?php echo lang('App.locations_add') ?></h3>
                </div>

                <?php echo form_open('locations/save', ['class' =>
                    'form-validate', 'autocomplete' => 'off', 'method' => 'post']); ?>
                <div class="card-body">
                    <div class="form-group">
                        <label for="formLocation-Location-Name"><?php echo lang('App.locations_name') ?></label>
                        <input value="<?= set_value('name') ?>" type="text" class="form-control" name="name" id="formLocation-Location-Name" required placeholder="<?php echo lang('App.locations_name') ?>" />
                    </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                    <button type="submit" class="btn btn-flat btn-primary"><?php echo lang('App.submit') ?></button>
                </div>
                <?php echo form_close(); ?>
            </div>
            <?php if (hasPermissions('locations_list')): ?>
            <div class="card">
                <div class="card-header with-border">
                    <h3 class="card-title"><?php echo lang('App.locations') ?></h3>
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
                        <?php foreach ($locations as $row): ?>
                            <tr>
                                <td><?= $row->id ?></td>
                                <td><?= $row->name ?></td>
                                <td>
                                    <?php if (hasPermissions('locations_edit')): ?>
                                        <a href="<?php echo url('locations/edit/'.$row->id) ?>" class="btn btn-sm btn-primary" title="<?php echo lang('App.locations_delete') ?>" data-toggle="tooltip"><i class="fas fa-edit"></i></a>
                                    <?php endif;?>
                                    <?php if (hasPermissions('locations_delete')): ?>
                                    <a href="<?php echo url('locations/delete/'.$row->id) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Do you really want to delete this user ?')" title="<?php echo lang('App.locations_delete') ?>" data-toggle="tooltip"><i class="fa fa-trash"></i></a>
                                    <?php endif;?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>
<!-- /.content -->
<?= $this->endSection() ?>
<?= $this->section('js') ?>
<?= $this->endSection() ?>
