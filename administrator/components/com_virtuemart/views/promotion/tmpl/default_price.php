<?php if($this->product->tour_methor=="tour_group"){ ?>
<div class="row-fluid" id="tour_group">
    <div class="span12">

        <h3>NET PRICE</h3>
        <table class="table-bordered  table table-striped base-price">
            <thead>
            <tr>
                <td>Passenger</td>
                <td style="text-align: center">Senior</td>
                <td style="text-align: center">Adult</td>
                <td style="text-align: center">Teen</td>
                <td style="text-align: center">Child 1</td>
                <td style="text-align: center">Child 2</td>
                <td style="text-align: center">Infant</td>
                <td style="text-align: center">Pr. Room</td>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($this->list_group_size_by_tour_id AS $group_size) { ?>
                <?php
                $tour_price_by_tour_price_id = $this->list_tour_promotion_price_by_tour_promotion_price_id[$group_size->virtuemart_group_size_id];
                $price_senior = $tour_price_by_tour_price_id->price_senior;
                $price_adult = $tour_price_by_tour_price_id->price_adult;
                $price_teen = $tour_price_by_tour_price_id->price_teen;
                $price_children1 = $tour_price_by_tour_price_id->price_children1;
                $price_children2 = $tour_price_by_tour_price_id->price_children2;
                $price_infant = $tour_price_by_tour_price_id->price_infant;
                $price_private_room = $tour_price_by_tour_price_id->price_private_room;

                ?>
                <tr role="row"
                    data-group_size_id="<?php echo $group_size->virtuemart_group_size_id ?>">
                    <td style="text-align: center"><?php echo $group_size->group_name ?></td>
                    <td><input required="true"
                               group-id="<?php echo $group_size->virtuemart_group_size_id ?>"
                               column-type="senior" type="text" size="7"
                               value="<?php echo $price_senior ?>"
                               name="tour_promotion_price_by_tour_promotion_price_id[<?php echo $group_size->virtuemart_group_size_id ?>][price_senior]"
                               required="true" class="inputbox number price_senior"></td>
                    <td><input group-id="<?php echo $group_size->virtuemart_group_size_id ?>"
                               column-type="adult" type="text" size="7"
                               value="<?php echo $price_adult ?>"
                               name="tour_promotion_price_by_tour_promotion_price_id[<?php echo $group_size->virtuemart_group_size_id ?>][price_adult]"
                               required="true" class="inputbox number price_adult"></td>
                    <td><input group-id="<?php echo $group_size->virtuemart_group_size_id ?>"
                               column-type="teen" type="text" size="7"
                               value="<?php echo $price_teen ?>"
                               name="tour_promotion_price_by_tour_promotion_price_id[<?php echo $group_size->virtuemart_group_size_id ?>][price_teen]"
                               required="true" class="inputbox number price_teen"></td>
                    <td><input group-id="<?php echo $group_size->virtuemart_group_size_id ?>"
                               column-type="children1" type="text" size="7"
                               value="<?php echo $price_children1 ?>"
                               name="tour_promotion_price_by_tour_promotion_price_id[<?php echo $group_size->virtuemart_group_size_id ?>][price_children1]"
                               required="true" class="inputbox number price_children1"></td>
                    <td><input group-id="<?php echo $group_size->virtuemart_group_size_id ?>"
                               column-type="children2" type="text" size="7"
                               value="<?php echo $price_children2 ?>"
                               name="tour_promotion_price_by_tour_promotion_price_id[<?php echo $group_size->virtuemart_group_size_id ?>][price_children2]"
                               required="true" class="inputbox number price_children2"></td>
                    <td><input group-id="<?php echo $group_size->virtuemart_group_size_id ?>"
                               column-type="infant" type="text" size="7"
                               value="<?php echo $price_infant ?>"
                               name="tour_promotion_price_by_tour_promotion_price_id[<?php echo $group_size->virtuemart_group_size_id ?>][price_infant]"
                               required="true" class="inputbox number price_infant"></td>
                    <td><input group-id="<?php echo $group_size->virtuemart_group_size_id ?>"
                               column-type="private_room" type="text" size="7"
                               value="<?php echo $price_private_room ?>"
                               name="tour_promotion_price_by_tour_promotion_price_id[<?php echo $group_size->virtuemart_group_size_id ?>][price_private_room]"
                               required="true" class="inputbox number price_private_room"></td>

                </tr>
            <?php } ?>
            </tbody>
        </table>
        <h3>Promotion </h3>
        <table class="table-bordered  table table-striped promotion-price">
            <tr>
                <td>MARK UP VALUE</td>
                <td style="text-align: center">Senior</td>
                <td style="text-align: center">Adult</td>
                <td style="text-align: center">Teen</td>
                <td style="text-align: center">Child 1</td>
                <td style="text-align: center">Child 2</td>
                <td style="text-align: center">Infant</td>
                <td style="text-align: center">Pr. Room</td>
            </tr>
            <tr class="amount">
                <td>Amout</td>
                <td style="text-align: center"><input name="amount[net_markup_promotion_price][senior]"
                                                      value="<?php echo $this->list_promotion['amount']->senior ?>"
                                                      class="inputbox number" column-type="senior"

                                                      type="text"></td>
                <td style="text-align: center"><input name="amount[net_markup_promotion_price][adult]"
                                                      value="<?php echo $this->list_promotion['amount']->adult ?>"
                                                      class="inputbox number" column-type="adult"
                                                      type="text"></td>
                <td style="text-align: center"><input name="amount[net_markup_promotion_price][teen]"
                                                      value="<?php echo $this->list_promotion['amount']->teen ?>"
                                                      class="inputbox number" column-type="teen"
                                                      type="text"></td>
                <td style="text-align: center"><input name="amount[net_markup_promotion_price][children1]"
                                                      value="<?php echo $this->list_promotion['amount']->children1 ?>"
                                                      class="inputbox number"
                                                      column-type="children1" type="text"></td>
                <td style="text-align: center"><input name="amount[net_markup_promotion_price][children2]"
                                                      value="<?php echo $this->list_promotion['amount']->children2 ?>"
                                                      class="inputbox number"
                                                      column-type="children2" type="text"></td>
                <td style="text-align: center"><input name="amount[net_markup_promotion_price][infant]"
                                                      value="<?php echo $this->list_promotion['amount']->infant ?>"
                                                      class="inputbox number" column-type="infant"
                                                      type="text"></td>
                <td style="text-align: center"><input name="amount[net_markup_promotion_price][private_room]"
                                                      value="<?php echo $this->list_promotion['amount']->private_room ?>"
                                                      class="inputbox number"
                                                      column-type="private_room" type="text"></td>
            </tr>
            <tr class="percent">
                <td>percent</td>
                <td style="text-align: center"><input name="percent[net_markup_promotion_price][senior]"
                                                      value="<?php echo $this->list_promotion['percent']->senior ?>"
                                                      class="inputbox number" column-type="senior"
                                                      type="text"></td>
                <td style="text-align: center"><input name="percent[net_markup_promotion_price][adult]"
                                                      value="<?php echo $this->list_promotion['percent']->adult ?>"
                                                      class="inputbox number" column-type="adult"
                                                      type="text"></td>
                <td style="text-align: center"><input name="percent[net_markup_promotion_price][teen]"
                                                      value="<?php echo $this->list_promotion['percent']->teen ?>"
                                                      class="inputbox number" column-type="teen"
                                                      type="text"></td>
                <td style="text-align: center"><input name="percent[net_markup_promotion_price][children1]"
                                                      value="<?php echo $this->list_promotion['percent']->children1 ?>"
                                                      class="inputbox number"
                                                      column-type="children1" type="text"></td>
                <td style="text-align: center"><input name="percent[net_markup_promotion_price][children2]"
                                                      value="<?php echo $this->list_promotion['percent']->children2 ?>"
                                                      class="inputbox number"
                                                      column-type="children2" type="text"></td>
                <td style="text-align: center"><input name="percent[net_markup_promotion_price][infant]"
                                                      value="<?php echo $this->list_promotion['percent']->infant ?>"
                                                      class="inputbox number" column-type="infant"
                                                      type="text"></td>
                <td style="text-align: center"><input name="percent[net_markup_promotion_price][private_room]"
                                                      value="<?php echo $this->list_promotion['percent']->private_room ?>"
                                                      class="inputbox number"
                                                      column-type="private_room" type="text"></td>
            </tr>
        </table>
        <h3>mark up</h3>
        <table class="table-bordered  table table-striped mark-up-price">
            <tr>
                <td>MARK UP VALUE</td>
                <td style="text-align: center">Senior</td>
                <td style="text-align: center">Adult</td>
                <td style="text-align: center">Teen</td>
                <td style="text-align: center">Child 1</td>
                <td style="text-align: center">Child 2</td>
                <td style="text-align: center">Infant</td>
                <td style="text-align: center">Pr. Room</td>
            </tr>
            <tr class="amount">
                <td>Amout</td>
                <td style="text-align: center"><input name="amount[markup_promotion_price][senior]"
                                                      value="<?php echo $this->list_promotion_mark_up['amount']->senior ?>"
                                                      class="inputbox number" column-type="senior"
                                                      type="text"></td>
                <td style="text-align: center"><input name="amount[markup_promotion_price][adult]"
                                                      value="<?php echo $this->list_promotion_mark_up['amount']->adult ?>"
                                                      class="inputbox number" column-type="adult"
                                                      type="text"></td>
                <td style="text-align: center"><input name="amount[markup_promotion_price][teen]"
                                                      value="<?php echo $this->list_promotion_mark_up['amount']->teen ?>"
                                                      class="inputbox number" column-type="teen"
                                                      type="text"></td>
                <td style="text-align: center"><input name="amount[markup_promotion_price][children1]"
                                                      value="<?php echo $this->list_promotion_mark_up['amount']->children1 ?>"
                                                      class="inputbox number"
                                                      column-type="children1" type="text"></td>
                <td style="text-align: center"><input name="amount[markup_promotion_price][children2]"
                                                      value="<?php echo $this->list_promotion_mark_up['amount']->children2 ?>"
                                                      class="inputbox number"
                                                      column-type="children2" type="text"></td>
                <td style="text-align: center"><input name="amount[markup_promotion_price][infant]"
                                                      value="<?php echo $this->list_promotion_mark_up['amount']->infant ?>"
                                                      class="inputbox number" column-type="infant"
                                                      type="text"></td>
                <td style="text-align: center"><input name="amount[markup_promotion_price][private_room]"
                                                      value="<?php echo $this->list_promotion_mark_up['amount']->private_room ?>"
                                                      class="inputbox number"
                                                      column-type="private_room" type="text"></td>
            </tr>
            <tr class="percent">
                <td>percent</td>
                <td style="text-align: center"><input name="percent[markup_promotion_price][senior]"
                                                      value="<?php echo $this->list_mark_up['percent']->senior ?>"
                                                      class="inputbox number" column-type="senior"
                                                      type="text"></td>
                <td style="text-align: center"><input name="percent[markup_promotion_price][adult]"
                                                      value="<?php echo $this->list_mark_up['percent']->adult ?>"
                                                      class="inputbox number" column-type="adult"
                                                      type="text"></td>
                <td style="text-align: center"><input name="percent[markup_promotion_price][teen]"
                                                      value="<?php echo $this->list_mark_up['percent']->teen ?>"
                                                      class="inputbox number" column-type="teen"
                                                      type="text"></td>
                <td style="text-align: center"><input name="percent[markup_promotion_price][children1]"
                                                      value="<?php echo $this->list_mark_up['percent']->children1 ?>"
                                                      class="inputbox number"
                                                      column-type="children1" type="text"></td>
                <td style="text-align: center"><input name="percent[markup_promotion_price][children2]"
                                                      value="<?php echo $this->list_mark_up['percent']->children2 ?>"
                                                      class="inputbox number"
                                                      column-type="children2" type="text"></td>
                <td style="text-align: center"><input name="percent[markup_promotion_price][infant]"
                                                      value="<?php echo $this->list_mark_up['percent']->infant ?>"
                                                      class="inputbox number" column-type="infant"
                                                      type="text"></td>
                <td style="text-align: center"><input name="percent[markup_promotion_price][private_room]"
                                                      value="<?php echo $this->list_mark_up['percent']->private_room ?>"
                                                      class="inputbox number"
                                                      column-type="private_room" type="text"></td>
            </tr>
        </table>
        <h3>PROFIT</h3>
        <table class="table-bordered  table table-striped profit-price">
            <tr>
                <td>Passenger</td>
                <td style="text-align: center">Senior</td>
                <td style="text-align: center">Adult</td>
                <td style="text-align: center">Teen</td>
                <td style="text-align: center">Child 1</td>
                <td style="text-align: center">Child 2</td>
                <td style="text-align: center">Infant</td>
                <td style="text-align: center">Pr. Room</td>
            </tr>
            <?php foreach ($this->list_group_size_by_tour_id AS $group_size) { ?>

                <tr>
                    <td style="text-align: center"><?php echo $group_size->group_name ?></td>
                    <td><span group-id="<?php echo $group_size->virtuemart_group_size_id ?>"
                              column-type="senior"></span></td>
                    <td><span group-id="<?php echo $group_size->virtuemart_group_size_id ?>"
                              column-type="adult"></span></td>
                    <td><span group-id="<?php echo $group_size->virtuemart_group_size_id ?>"
                              column-type="teen"></span></td>
                    <td><span group-id="<?php echo $group_size->virtuemart_group_size_id ?>"
                              column-type="children1"></span></td>
                    <td><span group-id="<?php echo $group_size->virtuemart_group_size_id ?>"
                              column-type="children2"></span></td>
                    <td><span group-id="<?php echo $group_size->virtuemart_group_size_id ?>"
                              column-type="infant"></span></td>
                    <td><span group-id="<?php echo $group_size->virtuemart_group_size_id ?>"
                              column-type="private_room"></span></td>


                </tr>
            <?php } ?>
        </table>
        <h3>Tax</h3>
        <table class="table-bordered  table table-striped tax-price">
            <tr>
                <td>Value</td>
                <td style="text-align: center"><input type="text" class="inputbox number" name="tax"
                                                      value="<?php echo $this->promotion_price->tax ?>"
                                                      style="width: 80%"></td>
            </tr>
        </table>
        <h3>Sale price</h3>
        <table class="table-bordered  table table-striped sale-price">
            <tr>
                <td>Passenger</td>
                <td style="text-align: center">Senior</td>
                <td style="text-align: center">Adult</td>
                <td style="text-align: center">Teen</td>
                <td style="text-align: center">Child 1</td>
                <td style="text-align: center">Child 2</td>
                <td style="text-align: center">Infant</td>
                <td style="text-align: center">Pr. Room</td>
            </tr>
            <?php foreach ($this->list_group_size_by_tour_id AS $group_size) { ?>

                <tr>
                    <td style="text-align: center"><?php echo $group_size->group_name ?></td>
                    <td><span group-id="<?php echo $group_size->virtuemart_group_size_id ?>"
                              column-type="senior"></span></td>
                    <td><span group-id="<?php echo $group_size->virtuemart_group_size_id ?>"
                              column-type="adult"></span></td>
                    <td><span group-id="<?php echo $group_size->virtuemart_group_size_id ?>"
                              column-type="teen"></span></td>
                    <td><span group-id="<?php echo $group_size->virtuemart_group_size_id ?>"
                              column-type="children1"></span></td>
                    <td><span group-id="<?php echo $group_size->virtuemart_group_size_id ?>"
                              column-type="children2"></span></td>
                    <td><span group-id="<?php echo $group_size->virtuemart_group_size_id ?>"
                              column-type="infant"></span></td>
                    <td><span group-id="<?php echo $group_size->virtuemart_group_size_id ?>"
                              column-type="private_room"></span></td>

                </tr>
            <?php } ?>
        </table>

    </div>
    <div class="span6">

    </div>
</div>
<?php }else{ ?>
<div id="tour_basic">
    <h3>NET PRICE</h3>
    <table class="table-bordered  table table-striped base-price">
        <thead>
        <tr>
            <td>Passenger</td>
            <td style="text-align: center">Senior</td>
            <td style="text-align: center">Adult</td>
            <td style="text-align: center">Teen</td>
            <td style="text-align: center">Child 1</td>
            <td style="text-align: center">Child 2</td>
            <td style="text-align: center">Infant</td>
            <td style="text-align: center">Pr. Room</td>
        </tr>
        </thead>
        <tbody>
        <tr role="row">
            <td style="text-align: center">Price</td>
            <td><input required="true"
                       column-type="senior" type="text" size="7" value="<?php echo $price_senior ?>"
                       name="tour_promotion_price_by_tour_promotion_price_id[price_senior]"
                       required="true" class="inputbox number price_senior"></td>
            <td><input
                    column-type="adult" type="text" size="7" value="<?php echo $price_adult ?>"
                    name="tour_promotion_price_by_tour_promotion_price_id[price_adult]"
                    required="true" class="inputbox number price_adult"></td>
            <td><input column-type="teen"
                       type="text" size="7" value="<?php echo $price_teen ?>"
                       name="tour_promotion_price_by_tour_promotion_price_id[price_teen]"
                       required="true" class="inputbox number price_teen"></td>
            <td><input
                    column-type="children1" type="text" size="7"
                    name="tour_promotion_price_by_tour_promotion_price_id[price_children1]"
                    required="true" class="inputbox number price_children1"></td>
            <td><input
                    column-type="children2" type="text" size="7"
                    name="tour_promotion_price_by_tour_promotion_price_id[price_children2]"
                    required="true" class="inputbox number price_children2"></td>
            <td><input
                    column-type="infant" type="text" size="7" value="<?php echo $price_infant ?>"
                    name="tour_promotion_price_by_tour_promotion_price_id[price_infant]"
                    required="true" class="inputbox number price_infant"></td>
            <td><input
                    column-type="private_room" type="text" size="7"
                    name="tour_promotion_price_by_tour_promotion_price_id[price_private_room]"
                    required="true" class="inputbox number price_private_room"></td>

        </tr>
        </tbody>
    </table>
    <h3>Promotion</h3>
    <table class="table-bordered  table table-striped mark-up-price">
        <tr>
            <td>Passenger</td>
            <td style="text-align: center">Senior</td>
            <td style="text-align: center">Adult</td>
            <td style="text-align: center">Teen</td>
            <td style="text-align: center">Child 1</td>
            <td style="text-align: center">Child 2</td>
            <td style="text-align: center">Infant</td>
            <td style="text-align: center">Pr. Room</td>
        </tr>
        <tr class="amount">
            <td>Amout</td>
            <td style="text-align: center"><input name="amount[promotion][senior]"
                                                  value="<?php echo $this->list_promotion_price_markup['amount']->senior ?>"
                                                  class="inputbox number" column-type="senior"
                                                  type="text"></td>
            <td style="text-align: center"><input name="amount[promotion][adult]"
                                                  value="<?php echo $this->list_promotion_price_markup['amount']->adult ?>"
                                                  class="inputbox number" column-type="adult"
                                                  type="text"></td>
            <td style="text-align: center"><input name="amount[promotion][teen]"
                                                  value="<?php echo $this->list_promotion_price_markup['amount']->teen ?>"
                                                  class="inputbox number" column-type="teen"
                                                  type="text"></td>
            <td style="text-align: center"><input name="amount[promotion][children1]"
                                                  value="<?php echo $this->list_promotion_price_markup['amount']->children1 ?>"
                                                  class="inputbox number" column-type="children1"
                                                  type="text"></td>
            <td style="text-align: center"><input name="amount[promotion][children2]"
                                                  value="<?php echo $this->list_promotion_price_markup['amount']->children2 ?>"
                                                  class="inputbox number" column-type="children2"
                                                  type="text"></td>
            <td style="text-align: center"><input name="amount[promotion][infant]"
                                                  value="<?php echo $this->list_promotion_price_markup['amount']->infant ?>"
                                                  class="inputbox number" column-type="infant"
                                                  type="text"></td>
            <td style="text-align: center"><input name="amount[promotion][private_room]"
                                                  value="<?php echo $this->list_promotion_price_markup['amount']->private_room ?>"
                                                  class="inputbox number" column-type="private_room"
                                                  type="text"></td>
        </tr>
        <tr class="percent">
            <td>percent</td>
            <td style="text-align: center"><input name="percent[promotion][senior]"
                                                  value="<?php echo $this->list_promotion_price_markup['percent']->senior ?>"
                                                  class="inputbox number" column-type="senior"
                                                  type="text"></td>
            <td style="text-align: center"><input name="percent[promotion][adult]"
                                                  value="<?php echo $this->list_promotion_price_markup['percent']->adult ?>"
                                                  class="inputbox number" column-type="adult"
                                                  type="text"></td>
            <td style="text-align: center"><input name="percent[promotion][teen]"
                                                  value="<?php echo $this->list_promotion_price_markup['percent']->teen ?>"
                                                  class="inputbox number" column-type="teen"
                                                  type="text"></td>
            <td style="text-align: center"><input name="percent[promotion][children1]"
                                                  value="<?php echo $this->list_promotion_price_markup['percent']->children1 ?>"
                                                  class="inputbox number" column-type="children1"
                                                  type="text"></td>
            <td style="text-align: center"><input name="percent[promotion][children2]"
                                                  value="<?php echo $this->list_promotion_price_markup['percent']->children2 ?>"
                                                  class="inputbox number" column-type="children2"
                                                  type="text"></td>
            <td style="text-align: center"><input name="percent[promotion][infant]"
                                                  value="<?php echo $this->list_promotion_price_markup['percent']->infant ?>"
                                                  class="inputbox number" column-type="infant"
                                                  type="text"></td>
            <td style="text-align: center"><input name="percent[promotion][private_room]"
                                                  value="<?php echo $this->list_promotion_price_markup['percent']->private_room ?>"
                                                  class="inputbox number" column-type="private_room"
                                                  type="text"></td>
        </tr>
    </table>
    <h3>mark up</h3>
    <table class="table-bordered  table table-striped mark-up-price">
        <tr>
            <td>MARK UP VALUE</td>
            <td style="text-align: center">Senior</td>
            <td style="text-align: center">Adult</td>
            <td style="text-align: center">Teen</td>
            <td style="text-align: center">Child 1</td>
            <td style="text-align: center">Child 2</td>
            <td style="text-align: center">Infant</td>
            <td style="text-align: center">Pr. Room</td>
        </tr>
        <tr class="amount">
            <td>Amout</td>
            <td style="text-align: center"><input name="amount[senior]"
                                                  value="<?php echo $this->list_mark_up['amount']->senior ?>"
                                                  class="inputbox number" column-type="senior"
                                                  type="text"></td>
            <td style="text-align: center"><input name="amount[adult]"
                                                  value="<?php echo $this->list_mark_up['amount']->adult ?>"
                                                  class="inputbox number" column-type="adult"
                                                  type="text"></td>
            <td style="text-align: center"><input name="amount[teen]"
                                                  value="<?php echo $this->list_mark_up['amount']->teen ?>"
                                                  class="inputbox number" column-type="teen"
                                                  type="text"></td>
            <td style="text-align: center"><input name="amount[children1]"
                                                  value="<?php echo $this->list_mark_up['amount']->children1 ?>"
                                                  class="inputbox number" column-type="children1"
                                                  type="text"></td>
            <td style="text-align: center"><input name="amount[children2]"
                                                  value="<?php echo $this->list_mark_up['amount']->children2 ?>"
                                                  class="inputbox number" column-type="children2"
                                                  type="text"></td>
            <td style="text-align: center"><input name="amount[infant]"
                                                  value="<?php echo $this->list_mark_up['amount']->infant ?>"
                                                  class="inputbox number" column-type="infant"
                                                  type="text"></td>
            <td style="text-align: center"><input name="amount[private_room]"
                                                  value="<?php echo $this->list_mark_up['amount']->private_room ?>"
                                                  class="inputbox number" column-type="private_room"
                                                  type="text"></td>
        </tr>
        <tr class="percent">
            <td>percent</td>
            <td style="text-align: center"><input name="percent[senior]"
                                                  value="<?php echo $this->list_mark_up['percent']->senior ?>"
                                                  class="inputbox number" column-type="senior"
                                                  type="text"></td>
            <td style="text-align: center"><input name="percent[adult]"
                                                  value="<?php echo $this->list_mark_up['percent']->adult ?>"
                                                  class="inputbox number" column-type="adult"
                                                  type="text"></td>
            <td style="text-align: center"><input name="percent[teen]"
                                                  value="<?php echo $this->list_mark_up['percent']->teen ?>"
                                                  class="inputbox number" column-type="teen"
                                                  type="text"></td>
            <td style="text-align: center"><input name="percent[children1]"
                                                  value="<?php echo $this->list_mark_up['percent']->children1 ?>"
                                                  class="inputbox number" column-type="children1"
                                                  type="text"></td>
            <td style="text-align: center"><input name="percent[children2]"
                                                  value="<?php echo $this->list_mark_up['percent']->children2 ?>"
                                                  class="inputbox number" column-type="children2"
                                                  type="text"></td>
            <td style="text-align: center"><input name="percent[infant]"
                                                  value="<?php echo $this->list_mark_up['percent']->infant ?>"
                                                  class="inputbox number" column-type="infant"
                                                  type="text"></td>
            <td style="text-align: center"><input name="percent[private_room]"
                                                  value="<?php echo $this->list_mark_up['percent']->private_room ?>"
                                                  class="inputbox number" column-type="private_room"
                                                  type="text"></td>
        </tr>
    </table>
    <h3>PROFIT</h3>
    <table class="table-bordered  table table-striped profit-price">
        <tr>
            <td>Passenger</td>
            <td style="text-align: center">Senior</td>
            <td style="text-align: center">Adult</td>
            <td style="text-align: center">Teen</td>
            <td style="text-align: center">Child 1</td>
            <td style="text-align: center">Child 2</td>
            <td style="text-align: center">Infant</td>
            <td style="text-align: center">Pr. Room</td>
        </tr>
        <tbody>
        <tr role="row">
            <td style="text-align: center">Value</td>
            <td><span column-type="senior"></span></td>
            <td><span column-type="adult"></span></td>
            <td><span column-type="teen"></span></td>
            <td><span column-type="children1"></span></td>
            <td><span column-type="children2"></span></td>
            <td><span column-type="infant"></span></td>
            <td><span column-type="private_room"></span></td>


        </tr>
        </tbody>

    </table>
    <h3>Tax</h3>
    <table class="table-bordered  table table-striped tax-price">
        <tr>
            <td>Value</td>
            <td style="text-align: center"><input type="text" class="inputbox number" name="tax"
                                                  value="<?php echo $this->promotion_price->tax ?>"
                                                  style="width: 80%"></td>
        </tr>
    </table>
    <h3>Sale price</h3>
    <table class="table-bordered  table table-striped sale-price">
        <tr>
            <td>Passenger</td>
            <td style="text-align: center">Senior</td>
            <td style="text-align: center">Adult</td>
            <td style="text-align: center">Teen</td>
            <td style="text-align: center">Child 1</td>
            <td style="text-align: center">Child 2</td>
            <td style="text-align: center">Infant</td>
            <td style="text-align: center">Pr. Room</td>
        </tr>
        <tbody>
        <tr role="row">
            <td style="text-align: center"></td>
            <td><span column-type="senior"></span></td>
            <td><span column-type="adult"></span></td>
            <td><span column-type="teen"></span></td>
            <td><span column-type="children1"></span></td>
            <td><span column-type="children2"></span></td>
            <td><span column-type="infant"></span></td>
            <td><span column-type="private_room"></span></td>

        </tr>
        </tbody>

    </table>
</div>
<?php } ?>