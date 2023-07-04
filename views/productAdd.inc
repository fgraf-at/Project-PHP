<?php $render('partial/header', $data); ?>

    <h1 class="mb-3">add new product</h1>

<?php $beginForm('ProductsController', 'Create', method: 'post'); ?>
    <div class="mt-5">
        <label for="manufacturer"
               class="form-label">
            Manufacturer
        </label>
        <input class="form-control"
               id="manufacturer"
               name="manufacturer"
               type="text"
        >
    <div class="mt-5 mb-4">
        <label for="name" class="form-label">Name of Product</label>
        <input class="form-control"
               id="name"
               name="name"
               type="text"
        >
    </div>
    <div class="mt-5 mb-4">
        <label for="name" class="form-label">price</label>
        <input class="form-control"
               id="price"
               name="price"
                type="number"
        >
    </div>
    </div>
    <button class="btn btn-secondary p-2 m-2">Create</button>
<?php $endForm(); ?>

<?php $render('partial/footer', $data); ?>