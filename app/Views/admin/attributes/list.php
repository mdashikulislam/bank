<?= $this->extend('admin/layout/default') ?>
<?= $this->section('content') ?>

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
        <div class="col-12">
            <div class="card">
                <div class="card-header with-border d-flex">
                    <h3 class="card-title"><?php echo lang('App.attributes') ?></h3>
                   <div class="ml-auto">
                       <a href="<?php echo url('attributes/add') ?>" class="btn btn-primary btn-sm"><span class="pr-1"><i class="fa fa-plus"></i></span>Add Attribute</a>
                   </div>
                </div>
                <div class="card-body">
                    <table id="example1" class="table table-bordered table-hover table-striped">
                        <thead>
                        <tr>
                            <th><?php echo lang('App.id') ?></th>
                            <th><?php echo lang('App.banks_name') ?></th>
                            <th><?php echo lang('App.attributes') ?></th>
                            <th><?php echo lang('App.status') ?></th>
                            <th><?php echo lang('App.action') ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($attributes as $row): ?>
                            <tr>
                                <td><?= $row->id ?></td>
                                <td><?= $row->bank_name ?></td>
                                <td>
                                    <?php
                                        $name = json_decode($row->name);
                                        foreach ($name as $n){
                                            echo '<span class="badge badge-success mr-2" role="alert">'.$n.'</span>';
                                        }
                                    ?>
                                </td>
                                <td>
                                    <input type="checkbox" name="my-checkbox"  onchange="updateUserStatus('<?php echo $row->id ?>', $(this).is(':checked') )" <?php echo ($row->status) ? 'checked' : '' ?> data-bootstrap-switch  data-off-color="secondary" data-on-color="success"  data-off-text="<?php echo lang('App.user_inactive') ?>" data-on-text="<?php echo lang('App.user_active') ?>">
                                </td>
                                <td>
                                    <?php if (hasPermissions('attributes_edit')): ?>
                                        <a href="<?php echo url('attributes/edit/'.$row->id) ?>" class="btn btn-sm btn-primary" title="<?php echo lang('App.banks_edit') ?>" data-toggle="tooltip"><i class="fas fa-edit"></i></a>
                                    <?php endif;?>
                                    <?php if (hasPermissions('attributes_delete')): ?>
                                        <a href="<?php echo url('attributes/delete/'.$row->id) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Do you really want to delete this user ?')" title="<?php echo lang('App.banks_delete') ?>" data-toggle="tooltip"><i class="fa fa-trash"></i></a>
                                    <?php endif;?>
                                </td>
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

    window.updateUserStatus = (id, status) => {
        $.get( '<?php echo url('attributes/change_status') ?>/'+id, {
            status: status
        }, (data, status) => {
            if (data=='done') {
                // code
            }else{
                alert('<?php echo lang('App.user_unable_change_status') ?>');
            }
        })
    }
</script>

<?= $this->endSection() ?>
