<?= $this->extend('admin/layout/default') ?>
<?= $this->section('content') ?>

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1><?php echo lang('App.excels') ?></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="<?php echo url('/') ?>"><?php echo lang('App.home') ?></a>
                    </li>
                    <li class="breadcrumb-item active"><?php echo lang('App.excels') ?></li>
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
                    <h3 class="card-title"><?php echo lang('App.excels') ?></h3>
                    <?php if (hasPermissions('excels_add')): ?>
                    <div class="ml-auto">
                        <a href="<?php echo url('excels/add') ?>" class="btn btn-primary btn-sm"><span class="pr-1"><i class="fa fa-plus"></i></span><?php echo lang('App.add_excels')?></a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php if (!empty($excels)): foreach ($excels as $row): ?>
                <div class="card collapsed-card">
                    <div class="card-header border-0 ui-sortable-handle">
                        <h3 class="card-title" data-card-widget="collapse" style="cursor: pointer">
                            <i class="far fa-list-alt"></i>
                            <?php echo $row->bank_name ?> (<?= $row->created_at ?>)
                        </h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-success btn-sm" data-card-widget="collapse">
                                <i class="fas fa-plus"></i>
                            </button>
                            <?php if (hasPermissions('excels_delete')): ?>
                            <a href="<?php echo url('excels/delete/'.$row->id) ?>" onclick="return confirm('Do you really want to delete this user ?')"  class="btn btn-danger btn-sm" >
                                <i class="fas fa-times"></i>
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="card-body table-responsive" style="display: none">
                        <table class="table table-striped table-bordered text-nowrap">
                            <thead>
                            <tr>
                                <?php
                                $header = json_decode($row->header);
                                foreach ($header as $h){
                                    echo '<th>'.ucfirst($h).'</th>';
                                }
                                ?>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $data = json_decode($row->data); foreach ($data as $d): ?>
                                <tr>
                                    <?php foreach ($d as $h): ?>
                                        <td><?= $h ?></td>
                                    <?php endforeach; ?>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endforeach; endif; ?>

        </div>
    </div>
</section>
<!-- /.content -->
<?= $this->endSection() ?>
<?= $this->section('js') ?>
<script !src="">
    $(function () {
        $("table").DataTable({
            "responsive": true,
            "autoWidth": false,
        });
    });
</script>
<?= $this->endSection() ?>
