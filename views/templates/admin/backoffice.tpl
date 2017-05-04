{*
* 2007-2013 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
*  @author Feedaty <info@feedaty.com>
*  @copyright  2012-2017 Feedaty
*  @version  Release: 2.0.6 $
*}
<div class="container">
<div class="row">

<div class="ps panel col-xs-12 col-sm-12 col-md-11 col-lg-11">
<div class="panel-heading">
    <i class="icon-AdminParentPreferences">  configure feedaty  </i>
</div>
    <form action="{$smarty.server.REQUEST_URI}" method="post">

        <div class="ps_top col-xs-12 col-sm-12 col-md-12 col-lg-12">

            <!-- merchant code and merchant secret-->
            <div class="lf_clm col-xs-12 col-sm-12 col-md-5 col-lg-5">

                <h1>{l s='Merchant Code:' mod='feedaty'}</h1>
                <input id="Text1" type="text" name="code" value="{$data.code|escape:'htmlall':'UTF-8'}" />
                <div class="clearfix"></div>

                <h1>{l s='Merchant Secret:' mod='feedaty'}</h1>
                <input class="col-md-6" id="Text2" type="text" name="secret" value="{$data.secret|escape:'htmlall':'UTF-8'}" />
                <div class="clearfix"></div>
                <p>{l s='Code info' mod='feedaty'}</p>
                <br>

                <h1>{l s='Debug Mode:' mod='feedaty'}</h1>
                <input id="debug_enabled" type="checkbox" name="debug_enabled" value="1"{if 
                        $data.debug_enabled eq 1} checked="checked"{/if} />
                <div class="clearfix"></div>
                <br>

            </div>
            <!-- infos and links -->
            <div class="rg_clm col-xs-12 col-sm-12 col-md-6 col-lg-6 col-md-offset-1 col-lg-offset-1">
                <div class="col-md-6 col-lg-6" >
                    <h1>{l s='Feedaty Support:' mod='feedaty'}</h1>
                    <div>Scrivi a: <a href="mailto:support{* email *}@feedaty.com">support{* email *}@feedaty.com</a></div>
                    <div>{l s='Visit:' mod='feedaty'} <a href="http://guida.zoorate.com">http://guida.zoorate.com</a></div>
                </div>
                <div class="rg col-md-6 col-lg-6"><img class="img-responsive" alt="" src="../modules/feedaty/img/logo_small.png" /></div>
                <div class="clearfix"></div>
            </div>
            <div class="clearfix"></div>

        </div>
        <div class="clearfix"></div>
        <!-- save div -->
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 save_settings">
            <div class="lf col-xs-12 col-sm-12 col-md-8">{l s='WARNING: every time you made changes remember to save settings.' mod='feedaty'}</div>
            <div class="rg col-xs-12 col-sm-12 col-md-2 col-md-offset-1">
                <input class="col-xs-12 col-sm-12 simple_btn btn btn-success" id="button" type="submit" value="{l s='Save Settings' mod='feedaty'}" name="submitModule" />
            </div>
            <div class="clearfix"></div>
        </div>

        <div class="ps_three_clm col-sm-12 col-md-4">
            <div class="box col-md-12 fd_labels">

                <h1>{l s='Merchant Widget List' mod='feedaty'}<strong><br /></h1>

                <div class="widget">
                    <ul class='accordion'>
                        {foreach from=$data.merchant key=k item=template}
                            <li><input id="radio-{$k|escape:'htmlall':'UTF-8'}" type="radio" name="template_store" value="{$k|escape:'htmlall':'UTF-8'}"{if $data.merchant_template eq $k} checked="checked"{/if} />

                            <span class="desc">
                            <h4>{$template.name_shown|upper|escape:'htmlall':'UTF-8'}</h4>
                                {l s='size:' mod='feedaty'}<strong><br />
                                    {$template.size_shown|escape:'htmlall':'UTF-8'}<br />  </strong></span>

                                <label for='cp-{$k|escape:'htmlall':'UTF-8'}'>[ {l s='example' mod='feedaty'} {$template.name_shown|lower|escape:'htmlall':'UTF-8'} ]</label>
                                <input type='radio' name='a' id='cp-{$k|escape:'htmlall':'UTF-8'}'>
                                <div class='content'>
                                    <img class="img-responsive" alt="" src="{$template.thumb|escape:'htmlall':'UTF-8'}" />
                                </div>
                                <div class="clearfix"></div>
                            </li>
                        {/foreach}
                    </ul>
                </div>

                <div class=" label">{l s='Widget Position:' mod='feedaty'}</div>
                <select id="store_position" name="store_position">
                    {foreach from=$data.merchant_position key=k item=position}
                        <option value="{$k|escape:'htmlall':'UTF-8'}"{if $k eq $data.merchant_default_position} selected="selected"{/if}>{$position|escape:'htmlall':'UTF-8'}</option>
                    {/foreach}
                </select>
                <div class="label">{l s='Show widget:' mod='feedaty'} <input id="widget_store_enabled" type="checkbox" name="widget_store_enabled" value="1"{if $data.widget_store_enabled eq 1} checked="checked"{/if} /></div>

            </div>
        </div>
        <div class="ps_three_clm col-sm-12 col-md-4">
            <div class="box fd_labels">
                <h1>{l s='Products Widget List' mod='feedaty'}</h1>

                <div class="widget">

                    <ul class='accordion'>
                        {foreach from=$data.product key=k item=template}
                            <li><input id="radio-{$k|escape:'htmlall':'UTF-8'}" type="radio" name="product_template" value="{$k|escape:'htmlall':'UTF-8'}"{if $data.product_template eq $k} checked="checked"{/if} />

                            <span class="desc">
                            <h4>{$template.name_shown|upper|escape:'htmlall':'UTF-8'}</h4>
                                {l s='size:' mod='feedaty'}<strong><br />
                                    {$template.size_shown|escape:'htmlall':'UTF-8'}<br />  </strong></span>

                                <label for='cp-{$k|escape:'htmlall':'UTF-8'}'>[ {l s='example' mod='feedaty'} {$template.name_shown|lower|escape:'htmlall':'UTF-8'} ]</label>
                                <input type='radio' name='a' id='cp-{$k|escape:'htmlall':'UTF-8'}'>
                                <div class='content'>
                                    <img alt="" src="{$template.thumb|escape:'htmlall':'UTF-8'}" />
                                </div>
                                <div class="clearfix"></div>
                            </li>
                        {/foreach}

                    </ul>


                </div>

                <div class="label">{l s='Widget Position:' mod='feedaty'}</div>
                <select id="product_position" name="product_position">
                    {foreach from=$data.product_position key=k item=position}
                        <option value="{$k|escape:'htmlall':'UTF-8'}"{if $k eq $data.product_default_position} selected="selected"{/if}>{$position|escape:'htmlall':'UTF-8'}</option>
                    {/foreach}
                </select>
                <div class="label">{l s='Show widget:' mod='feedaty'} <input id="widget_product_enabled" type="checkbox" name="widget_product_enabled" value="1"{if $data.widget_product_enabled eq 1} checked="checked"{/if} /></div>
                <div class="label">{l s='Show widget in preview:' mod='feedaty'} <input id="widget_product_preview_enabled" type="checkbox" name="widget_product_preview_enabled" value="1"{if $data.widget_product_preview_enabled eq 1} checked="checked"{/if} /></div>


            </div>

            <div class="box other col-md-12 fd_labels">

                <h1>{l s='Other settings' mod='feedaty'}</h1>
                <div class="label">{l s='Status to collect order:' mod='feedaty'}</div>
                <select id="status_request" name="status_request">
                    {foreach from=$data.status_list item=status}
                        <option value="{$status.id_order_state|escape:'htmlall':'UTF-8'}"{if $status.id_order_state eq $data.status} selected="selected"{/if}>{$status.name|escape:'htmlall':'UTF-8'}</option>
                    {/foreach}
                </select>

                <div class="label">{l s='Show reviews in product page:' mod='feedaty'}</div>
                <div class="sub col-md-12">
                    <div class="col-md-12 label">{l s='Enable:' mod='feedaty'} 
                        <input id="product_review_enabled" type="checkbox" name="product_review_enabled" value="1" {if $data.product_review_enabled eq 1} checked="checked"{/if} />
                    </div>
                    <div class="label col-md-12">{l s='Number of reviews:' mod='feedaty'} 
                        <input id="count_review" name="count_review" type="text" value="{$data.count_review|escape:'htmlall':'UTF-8'}" />
                    </div>
                </div>


            </div>

        </div>
        <div class="ps_three_clm col-sm-12 col-md-4 col-lg-4">
            <div class="instructions col-sm-12 col-md-12 col-lg-12 ">
                <h1>{l s='INSTRUCTIONS' mod='feedaty'}</h1>
                <ol>
                    <li><div class="num">1</div><div>{l s='Insert merchant code' mod='feedaty'}</div></li>
                    <li><div class="num">2</div><div>{l s='Select the widget that you want to use.' mod='feedaty'}</div> </li>
                    <li><div class="num">3</div><div>{l s='Set widget position.' mod='feedaty'}</div></li>
                    <li><div class="num">4</div><div>{l s='Make sure your profile is active on partners area.' mod='feedaty'}</div></li>
                    <li><div class="num">5</div><div>{l s='Remember to send by email' mod='feedaty'} <a href="mailto:partners{* email *}@feedaty.com">partners{* email *}@feedaty.com</a>,
                            {l s='just first time, previous orders list.' mod='feedaty'}</div> </li>
                    <div class="clearfix"></div>
                </ol>
                <div class="clearfix"></div>
                <h2>{l s='Want to learn more?' mod='feedaty'}</h2> <p>{l s='Visit our website' mod='feedaty'} <a href="http://www.feedaty.com">www.feedaty.com</a><br />{l s='or contact us by' mod='feedaty'} <a href="mailto:prova{* email *}@feedaty.com">prova{* email *}@feedaty.com</a></p>
            </div>

            <div class="col-md-12 box brown">
                <div>{l s='To extract the list of previous orders click here' mod='feedaty'}:</div>
                <input class="simple_btn btn btn-success" id="button2" type="button" value="{l s='Export previous orders' mod='feedaty'}" onclick="$('#exportcsv').submit()" />
            </div>
        </div>
        <div class="clearfix"></div>

        <!-- Microdata Settings for store-->
        <div class="ps_three_clm col-sm-12 col-md-4">
            <div class="box col-md-12 fd_labels">

                <h1>{l s='Merchant microdata settings' mod='feedaty'}<strong><br /></h1>

                <div class="label">{l s='Microdata position:' mod='feedaty'}</div>
                <select id="snip_merchant_position" name="snip_merchant_position">
                    {foreach from=$data.snip_merchant_position key=k item=position}
                        <option value="{$k|escape:'htmlall':'UTF-8'}"{if $k eq $data.snip_merchant_default_position} selected="selected"{/if}>{$position|escape:'htmlall':'UTF-8'}</option>
                    {/foreach}
                </select>
                <!-- Enable microdata-->
                <div class="label">{l s='Show microdata:' mod='feedaty'} <input id="snip_merchant_enabled" type="checkbox" name="snip_merchant_enabled" value="1"{if $data.snip_merchant_enabled eq 1} checked="checked"{/if} /></div>

            </div>
        </div>

        <!-- Microdata Settings for product-->
        <div class="ps_three_clm col-sm-12 col-md-4">
            <div class="box col-md-12 fd_labels">

                <h1>{l s='Product microdata settings' mod='feedaty'}<strong><br /></h1>

                <div class="label">{l s='Microdata position:' mod='feedaty'}</div>
                <select id="snip_product_position" name="snip_product_position">
                    {foreach from=$data.snip_product_position key=k item=position}
                        <option value="{$k|escape:'htmlall':'UTF-8'}"{if $k eq $data.snip_product_default_position} selected="selected"{/if}>{$position|escape:'htmlall':'UTF-8'}</option>
                    {/foreach}
                </select>
                <!-- Enable microdata-->
                <div class="label">{l s='Show microdata:' mod='feedaty'} <input id="snip_product_enabled" type="checkbox" name="snip_product_enabled" value="1"{if $data.snip_product_enabled eq 1} checked="checked"{/if} /></div>

            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 save_settings">

            <div class="lf col-md-8">{l s='WARNING: every time you made changes remember to save settings.' mod='feedaty'}</div>
            <div class="rg col-md-2 col-md-offset-1">
                <input class="simple_btn btn btn-success" id="button1" type="submit" value="{l s='Save settings' mod='feedaty'}" name="submitModule" />
            </div>
            <div class="clearfix"></div>

        </div>
    </form>
    <form id="exportcsv" action="{$smarty.server.REQUEST_URI}" method="post"{if $data.old_version eq 1} target="_blank"{/if}>
        <input type="hidden" name="export" value="csv">
    </form>
</div>
</div>
</div>



