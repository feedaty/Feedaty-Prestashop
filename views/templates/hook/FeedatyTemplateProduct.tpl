<!-- Feedaty Product Block -->
<div id="feedatyBlock_{$feedaty_widget_pos}">
{if !empty($feedaty_widget_data)}
	<div id="FdWidget_{$feedaty_widget_pos}" class="col-lg-4 col-xs-12">
		{$feedaty_widget_data nofilter}
	</div>
{/if}
{if !empty($feedaty_microdata)}
	<div id="FdMicrodata_{$feedaty_widget_pos}" class="col-xs-10 col-xs-offset-1">
		{$feedaty_microdata nofilter}
	</div>
{/if}
</div>
<!-- End Feedaty Product Block -->