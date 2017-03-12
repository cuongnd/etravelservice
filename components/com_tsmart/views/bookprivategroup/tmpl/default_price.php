<?php
$privategrouptrip=$this->privategrouptrip;
?>
<table class="list-price">
    <?php if ($privategrouptrip->sale_price_senior != 0) { ?>
        <tr>
            <td class=""><?php echo JText::_('Senior') ?>
                :
            </td>
            <td class=""><span
                    class="price <?php echo $privategrouptrip->sale_discount_price_senior != 0 ? ' strikethrough ' : '' ?>"
                    data-a-sign="US$ "><?php echo $privategrouptrip->sale_price_senior ?></span>
                <?php if ($privategrouptrip->sale_discount_price_senior != 0) { ?>
                    <br/>
                    <span class="price discount"
                          data-a-sign="US$ "><?php echo $privategrouptrip->sale_discount_price_senior ?></span>
                <?php } ?>
            </td>
        </tr>
    <?php } ?>
    <?php if ($privategrouptrip->sale_price_adult != 0) { ?>
        <tr>
            <td><?php echo JText::_('Adult') ?>:
            </td>
            <td><span
                    class="price <?php echo $privategrouptrip->sale_discount_price_adult != 0 ? ' strikethrough ' : '' ?>"
                    data-a-sign="US$ "><?php echo $privategrouptrip->sale_price_adult ?></span>
                <?php if ($privategrouptrip->sale_discount_price_adult != 0) { ?>
                    <br/>
                    <span class="price discount"
                          data-a-sign="US$ "><?php echo $privategrouptrip->sale_discount_price_adult ?></span>
                <?php } ?>
            </td>
        </tr>
    <?php } ?>
    <?php if ($privategrouptrip->sale_price_teen != 0) { ?>
        <tr>
            <td><?php echo JText::_('Teener') ?>:
            </td>
            <td><span
                    class="price <?php echo $privategrouptrip->sale_discount_price_teen != 0 ? ' strikethrough ' : '' ?>"
                    data-a-sign="US$ "><?php echo $privategrouptrip->sale_price_teen ?></span>
                <?php if ($privategrouptrip->sale_discount_price_teen != 0) { ?>
                    <br/>
                    <span class="price discount"
                          data-a-sign="US$ "><?php echo $privategrouptrip->sale_discount_price_teen ?></span>
                <?php } ?>
            </td>
        </tr>
    <?php } ?>
    <?php if ($privategrouptrip->sale_price_children1 != 0) { ?>
        <tr>
            <td><?php echo JText::_('Child 6-11') ?>
                :
            </td>
            <td><span
                    class="price <?php echo $privategrouptrip->sale_discount_price_children1 != 0 ? ' strikethrough ' : '' ?>"
                    data-a-sign="US$ "><?php echo $privategrouptrip->sale_price_children1 ?></span>
                <?php if ($privategrouptrip->sale_discount_price_children1 != 0) { ?>
                    <br/>
                    <span class="price discount"
                          data-a-sign="US$ "><?php echo $privategrouptrip->sale_discount_price_children1 ?></span>
                <?php } ?>
            </td>
        </tr>
    <?php } ?>
    <?php if ($privategrouptrip->sale_price_children2 != 0) { ?>
        <tr>
            <td><?php echo JText::_('Child 2-5') ?>:
            </td>
            <td><span
                    class="price <?php echo $privategrouptrip->sale_discount_price_children2 != 0 ? ' strikethrough ' : '' ?>"
                    data-a-sign="US$ "><?php echo $privategrouptrip->sale_price_children2 ?></span>
                <br/>
                <?php if ($privategrouptrip->sale_discount_price_children2 != 0) { ?>
                    <span class="price discount"
                          data-a-sign="US$ "><?php echo $privategrouptrip->sale_discount_price_children2 ?></span>
                <?php } ?>
            </td>
        </tr>
    <?php } ?>
    <?php if ($privategrouptrip->sale_price_infant != 0) { ?>
        <tr>
            <td><?php echo JText::_('Infant') ?>:
            </td>
            <td><span
                    class="price <?php echo $privategrouptrip->sale_discount_price_infant != 0 ? ' strikethrough ' : '' ?>"
                    data-a-sign="US$ "><?php echo $privategrouptrip->sale_price_infant ?></span>
                <?php if ($privategrouptrip->sale_discount_price_infant != 0) { ?>
                    <br/>
                    <span class="price discount"
                          data-a-sign="US$ "><?php echo $privategrouptrip->sale_discount_price_infant ?></span>
                <?php } ?>
            </td>
        </tr>
    <?php } ?>
</table>
