<?php $render("partial/header", $data); ?>

    <h1 class="mb-2">Search</h1>
    <?php $beginForm("ProductsController", "Search"); ?>
        <div class="mb-3">
            <div class="col-auto">
                <input  autocomplete="off" class="form-control" name="filter" value="<?php $htmlOut($data["filter"]); ?>">
            </div>
            <div class="mb-2">
                <input type="submit" class="btn btn-primary" value="search"></input>
            </div>
        </div>
    <?php $endForm(); ?>
<?php
   if( $data['user'] !== null  ) {

    ?>

        <?php $beginForm("ProductsController", "Create"); ?>
            <div class="p-3">
                <div class="p-1">
                    <input type="submit"
                           class="btn btn-secondary"
                           value="create new product"></input>
                </div>
            </div>
        <?php $endForm(); ?>
   <?php }?>
<?php if($data['products'] !== null) { ?>

    <?php if(sizeof($data['products']) > 0) { ?>
        <div class="table-responsive">
            <table class="table table-md table-hover">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Created by</th>
                    <th>Manufacturer</th>
                    <th>Rating</th>
                    <th></th>

                </tr>
                </thead>
                <tbody>
                <?php foreach($data['products'] as $product) { ?>
                    <tr>
                        <td>

                            <?php $link($product->getProduct()->getName(), 'ProductsController', 'Detail',
                                ['id' => $product->getProduct()->getId()],
                                cssClass: 'link-primary'); ?>
                        </td>
                        <td><?php $htmlOut($product->getUser()->getUserName()); ?>


                        </td>
                        <td><?php $htmlOut($product->getProduct()->getProducer()); ?></td>
                        <td>
                            <?php $htmlOut($product->getTotalAmountOfRatings()); ?>
                        </td>

                        <td>
                            <?php if(  $data['user'] !== null &&
                                $product->getProduct()->getUserId() === $data['user']->getId())
                            {?>
                                <?php $link("Edit", 'ProductsController',
                                'Edit',
                                ['id' => $product->getProduct()->getId()],
                                cssClass: 'btn btn-secondary '); ?>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    <?php } else { ?>
        <div class="alert alert-danger" role="alert">
            No products found.
        </div>
    <?php } ?>
<?php } ?>



<?php $render("partial/footer", $data); ?>