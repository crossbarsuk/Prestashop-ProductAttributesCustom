
<input type="hidden" name="submitted_tabs[]" value="usagecustom" />

	<fieldset style="border:none;">
	{foreach from=$cats item=cat key=key}
		<h4>{$cat.name}</h4>
		<div class="separation"></div>

		  <table cellpadding="5" style="width:100%">
		  <tbody>
				<tr>
				<td valign="top" style="text-align:left;vertical-align:top;">
					<table cellspacing="0" cellpadding="0" style="width:100%;" class="table">
							<colgroup>
								<col width="50">
								<col>
							</colgroup>
						<thead>
							<tr>
								<th></th>
								<th>Nom</th>
							</tr>
						</thead>
						<tbody>
						   {foreach from=$cat.usages item=usage key=key2}
								<tr>
									<td id="cat_{$usage.id_usage}" class="available_quantity">
										<input type="checkbox" name="id_usage[]" value="{$usage.id_usage}" {foreach from=$selection item=sel}{if $sel.id_usage == $usage.id_usage}checked="checked"{/if}{/foreach} />
									</td>
									<td>{$usage.name}</td>
								</tr>
						   {/foreach}
						</tbody>
						</table>
					</td>
				</tr>
			</tbody>
			</table>

		<div class="separation"></div>
	{/foreach}
    </fieldset>

<div class="clear">&nbsp;</div>

