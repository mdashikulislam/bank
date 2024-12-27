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
        <div class="col-sm-9">
            <?php if (hasPermissions('banks_add')): ?>
            <div class="card">
                <div class="card-header with-border">
                    <h3 class="card-title">
                        Add
                        <?php echo lang('App.banks') ?>
                    </h3>
                </div>
                <?php echo form_open_multipart('banks/save', ['class' =>
                    'form-validate', 'autocomplete' => 'off', 'method' => 'post']); ?>
                <div class="card-body">
                    <div class="form-group">
                        <label for="location_id"><?php echo lang('App.locations') ?></label>
                        <select name="location_id" id="location_id" class="form-control select2" required>
                            <?php echo getLocationDropdown(set_value('location_id'))?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="formBank-Bank-Name"><?php echo lang('App.banks_name') ?></label>
                        <input type="text" value="<?= set_value('name') ?>" class="form-control" name="name" id="formBank-Bank-Name" required placeholder="<?php echo lang('App.banks_name') ?>" />
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-flat btn-primary"><?php echo lang('App.submit') ?></button>
                </div>
                <?php echo form_close(); ?>
            </div>
            <?php endif; ?>
            <?php if (hasPermissions('banks_list')): ?>
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
                            <th><?php echo lang('App.status') ?></th>
                            <th><?php echo lang('App.action') ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($banks as $row): ?>
                            <tr>
                                <td><?= $row->id ?></td>
                                <td><?= $row->name ?></td>
                                <td>
                                    <input type="checkbox" name="my-checkbox"  onchange="updateUserStatus('<?php echo $row->id ?>', $(this).is(':checked') )" <?php echo ($row->status) ? 'checked' : '' ?> data-bootstrap-switch  data-off-color="secondary" data-on-color="success"  data-off-text="<?php echo lang('App.user_inactive') ?>" data-on-text="<?php echo lang('App.user_active') ?>">
                                </td>
                                <td></td>
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

<script>
    $(document).ready(function () {
        $(".form-validate").validate();
        //Initialize Select2 Elements
        $(".select2").select2();
    });
    window.updateUserStatus = (id, status) => {
        $.get( '<?php echo url('banks/change_status') ?>/'+id, {
            status: status
        }, (data, status) => {
            if (data=='done') {
                // code
            }else{
                alert('<?php echo lang('App.user_unable_change_status') ?>');
            }
        })
    }
    function previewImage(input, previewDom) {
        if (input.files && input.files[0]) {
            $(previewDom).show();

            var reader = new FileReader();

            reader.onload = function (e) {
                $(previewDom).find("img").attr("src", e.target.result);
            };

            reader.readAsDataURL(input.files[0]);
        } else {
            $(previewDom).hide();
        }
    }
</script>

<?= $this->endSection() ?>
