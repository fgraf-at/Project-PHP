<?php $render("partial/header", $data); ?>

<?php if($data['product'] !== null) { ?>
    <h1>Productinformation: </h1>
    <?php $render("partial/productDetail", $data); ?>
    <h2 class="mb-4">Ratings</h2>
    <?php foreach($data['product']->getRatings() as $rating) { ?>
        <div class="shadow-sm p-5 mb-5 bg-white rounded ">
            <div class="p-4">
                <?php if($data["user"] !== null && $rating->getUserId() ===  $data['user']->getId()) {?>
                    <div class="my-3">
                        <label for="Comment" class="form-label">Comment:</label>
                        <p id="Comment" name="ct" class="p-3">
                            <?php $htmlOut($rating->getComment()) ?>
                        </p>
                    </div>
                    <?php if( $rating->getUserId() ===  $data['user']->getId()) {?>
                        <?php $beginForm(
                            'RatingController', 'Remove',
                            [
                                "pid" =>$data['product']->getProduct()->getId(),
                                "rid" => $data['product']->getProduct()->getId()
                            ],
                            method: 'post'
                        ); ?>
                        <input type="submit" class="btn btn-success"   value="Remove Rating"></input>
                        <?php $endForm(); ?>
                        <?php $beginForm('Ratings', 'Edit', ["pid" => $data['product']->getProduct()->getId(), "rid" => $rating->getId()], method: 'post'); ?>

                        <input type="submit"
                               class="btn btn-dark"
                               value="Update Rating" />
                        <?php $endForm(); ?>
                    <?php }?>

                <?php } else { ?>

                    <div class="m-5">
                        <label for="Comment" class="form-label">Comment</label>
                        <p id="Comment" name="ct" class="p-4" >
                            <?php
                            $htmlOut($rating->getComment());
                            ?>
                        </p>
                    </div>

                <?php } ?>
            </div>
        </div>
    <?php } ?>

<?php } ?>

<?php $render("partial/footer", $data); ?>