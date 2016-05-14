<form action="?process={$sessionId}" method="post">

    {if isset($trailingCounter)}
        <input type="checkbox" name="trailing" title="" checked>
        Delete {$trailingCounter} trailing newline{if $trailingCounter>1}s{/if}
        <br>
        <br>
    {/if}

    {if isset($customBuilds)}
        Custom builds:
        <br>
        <ul>
            {foreach from=$customBuilds item=build}
                <li>{$build}</li>
            {/foreach}</ul>
    {/if}

    {if isset($tocs)}
        Table of Contents:
        <br>
        <ul>
            {foreach from=$tocs item=toc}
                <li>
                    {if $toc.title==""}Generic{else}{$toc.title}{/if}
                </li>
            {/foreach}</ul>
    {/if}

    <input type="submit" value="Go" name="process">

</form>