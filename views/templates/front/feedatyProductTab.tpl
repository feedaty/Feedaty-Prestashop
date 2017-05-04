{*
* 2007-2013 PrestaShop
*
*
*  @author Feedaty <info@feedaty.com>
*  @copyright  2017 Feedaty
*  @version  Release: 2.1.0 $
*}
<section class="page-product-box">
    <h3 id="#idTabfeedatyReviews" class="idTabHrefShort page-product-heading">{l s='Feedaty Reviews'} {$tabTitleSuffix}</h3>
    <div id="idTabfeedatyReviews">
        <div id="product_feedaty_reviews_block_tab">
            {if count($data_review.Feedbacks) neq 0}
        		{foreach $data_review.Feedbacks as $review}
            		<p>
                		<span class="stars">{$review.stars_html nofilter}</span>
                		<span class="review">{$review.ProductReview|escape:'htmlall':'UTF-8'}</span>
            		</p>
        		{/foreach}
        		<div class="feedaty_link"><p>{$feedaty_link nofilter}</p></div>
    		{else}
        		<p>{l s='There are no reviews' mod='feedaty'}</p>
    		{/if}
        </div>
        <div class="img-responsive fd_logo"><img src="../modules/feedaty/img/logo_small.png" alt="feedaty_logo"></div>

    </div>
</section>