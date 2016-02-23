<?php
defined('_JEXEC') or die;
$doc=JFactory::getDocument();
JHtml::_('jquery.framework');
$doc->addScript(JUri::root().'/media/system/js/CircularContentCarousel/js/jquery.mousewheel.js');
$doc->addScript(JUri::root().'/media/system/js/CircularContentCarousel/js/jquery.contentcarousel.js');
?>
<h4><span title="" class="icon-palette hasTooltip" data-original-title="Username"></span>GROUP & PRIVATE TOURS</h4>
<div id="ca-container" class="ca-container">
    <div class="ca-wrapper">
        <?php for($i=0;$i<9;$i++){ ?>
        <div class="ca-item span3 ca-item-<?php echo $i ?>">
            <div class="ca-item-main">
                <div class="ca-icon"></div>
                <h3>Stop factory farming</h3>
                <h4>
                    <span class="ca-quote">&ldquo;</span>
                    <span>The greatness of a nation and its moral progress can be judged by the way in which its animals are treated.</span>
                </h4>
                <a href="#" class="ca-more">more...</a>
            </div>
            <div class="ca-content-wrapper">
                <div class="ca-content">
                    <h6>Animals are not commodities</h6>
                    <a href="#" class="ca-close">close</a>
                    <div class="ca-content-text">
                        <p>I am so happy, my dear friend, so absorbed in the exquisite sense of mere tranquil existence, that I neglect my talents. I should be incapable of drawing a single stroke at the present moment; and yet I feel that I never was a greater artist than now.</p>
                        <p>When, while the lovely valley teems with vapour around me, and the meridian sun strikes the upper surface of the impenetrable foliage of my trees, and but a few stray gleams steal into the inner sanctuary, I throw myself down among the tall grass by the trickling stream;</p>
                        <p>She packed her seven versalia, put her initial into the belt and made herself on the way.</p>
                    </div>
                    <ul>
                        <li><a href="#">Read more</a></li>
                        <li><a href="#">Share this</a></li>
                        <li><a href="#">Become a member</a></li>
                        <li><a href="#">Donate</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
</div>

<script type="text/javascript">
    jQuery(document).ready(function($){
        $('#ca-container').contentcarousel();
    });

</script>






