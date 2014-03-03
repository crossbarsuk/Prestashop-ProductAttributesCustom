

		{foreach from=$cats item=cat key=key}
			<h2>{$cat.name}</h2>
			<ul>
			{foreach from=$cat.usages item=usage key=key2}
				{foreach from=$selection item=sel}{if $sel.id_usage == $usage.id_usage}<li>{$usage.name}</li>{/if}{/foreach}
			{/foreach}
			</ul>
		{/foreach}


