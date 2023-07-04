<?php $render('partial/header', $data); ?>

    <h1>Edit product</h1>
    <?php if ($data['product'] !== null) {?>
        <?php $beginForm('ProductsController', 'Edit',
                    ["id" => $data['product']->getProduct()->getId()],
                    method: 'post'); ?>

            <div class="form-group mb-5">
                <label for="producer"
                       class="form-label">Brand

                </label>
                <input id="producer"
                       class="form-control"
                       name="producer"
                       required="true"
                       value="<?php $htmlOut($data['product']->getProduct()->getProducer()) ?>"
                >
                <small>Please enter a new producer (if you want)</small>
            </div>
            <div class="form-group mb-5">
                <label for="name"
                       class="form-label">
                    Name
                </label>
                <input id="productName"
                       class="form-control"
                       name="productName"
                       required="true"
                       value="<?php $htmlOut($data['product']->getProduct()->getName()) ?>"
                >
                <small>Please enter a new product name (if you want)</small>
            </div>

            <input type="submit"  class="btn btn-primary " value="Update"></input>
             <input type="reset"  class="btn btn-dark" value="Reset"></input>
        <?php $endForm(); ?>
    <?php } ?>

<?php $render('partial/footer', $data); ?>