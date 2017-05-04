
<!-- Block Feedaty -->
{if !empty($feedaty_widget_data)}
	<div id="feedatyBlock_{$feedaty_widget_pos}" class="block col-lg-4 col-xs-12">
		{$feedaty_widget_data nofilter}
	</div>
{/if}
{if !empty($feedaty_microdata)}
	<div id="feedatyMicrodata_{$feedaty_widget_pos}" class="block col-xs-10 col-xs-offset-1">
		{$feedaty_microdata nofilter}
	</div>
{/if}