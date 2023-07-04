<div class="shadow-sm p-5 mb-5 bg-white rounded ">
    <?php $product = $data['product'] ?>
    <p ><strong>Created by:</strong> <?php $htmlOut($product->getUser()->getUserName())  ?></p>
    <p><strong>Product name:</strong> <?php $htmlOut($product->getProduct()->getName()) ?></p>
    <p><strong>Manufacturer:</strong> <?php $htmlOut($product->getProduct()->getProducer()) ?></p>
    <p><strong>Price:</strong> <?php $htmlOut($product->getProduct()->getPrice()) ?></p>
    <p>
        <small class="text-secondary"><?php $htmlOut($product->getAverageRating())?> of 5</small><br></br>
        <small class="text-secondary"><?php $htmlOut($product->getTotalAmountOfRatings())?> ratings in total</small>
    </p>
</div>