{*
* 2007-2013 PrestaShop
*
*
*  @author Feedaty <info@feedaty.com>
*  @copyright  2012-2017 Feedaty
*  @version  Release: 2.0.4 $
*}
<div class="tabs">

<div id="navs" class="tab-content">
    {if count($data_review.Feedbacks) neq 0}
        {foreach $data_review.Feedbacks as $review}
            <p>
                <div class="stars">{$review.stars_html nofilter}</div>
                <div class="review">{$review.ProductReview|escape:'htmlall':'UTF-8' nofilter}</div>
            </p>
        {/foreach}
        <p>{$feedaty_link nofilter}</p>
    {else}
        <p>{l s='There are no reviews' mod='feedaty'}</p>
    {/if}

</div>
</div>

