<?php $render("partial/header", $data); ?>

    <h1 class="mb-4">Products</h1>

<?php $beginForm("Products", "Index"); ?>
    <div class="row mb-3">
        <div class="col-auto">
            <input autocomplete="off" class="form-control" name="f" value="<?php $htmlOut($data["filter"]); ?>">
        </div>
        <div class="col-auto">
            <button class="btn btn-outline-success">Search</button>
        </div>
        <?php if($data['user'] !== null) { ?>
            <div class="col-auto ms-auto">
                <?php $link("Create new product", 'Products', 'Create', cssClass: 'btn btn-outline-secondary'); ?>
            </div>
        <?php } ?>
    </div>
<?php $endForm(); ?>

<?php $render("partial/productTable", $data); ?>

<?php $render("partial/footer", $data); ?>