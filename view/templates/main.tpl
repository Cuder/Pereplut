{* Header *}
{include file="header.tpl"}

{if isset($upload)}
    {include file="loadFile.tpl"}
{/if}

{if isset($settings)}
    {include file="settings.tpl"}
{/if}

{* Footer *}
{include file="footer.tpl"}