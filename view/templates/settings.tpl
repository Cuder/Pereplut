<form action="?process={$sessionId}" method="post">

    <div class="generalSettings">

        {if isset($customBuilds)}
            <div class="caption">{$captions.0}</div>
        {/if}
        <table class="settings">
            {if isset($trailingCounter)}
                <tr>
                    <td>{$settingsTrailing}</td>
                    <td><input type="checkbox" name="trailing" title="" checked></td>
                </tr>
            {/if}
            <tr>
                <td>{$settings.1}</td>
                <td><input type="checkbox" name="capitalize" title="Not implemented yet" disabled></td>
            </tr>
            <tr>
                <td>{$settings.2}</td>
                <td><input type="checkbox" name="log" title="Not implemented yet" disabled></td>
            </tr>
            <tr>
                <td>{$settings.10}</td>
                <td>
                    <select name="removeBuildFromTocMethod" title="">
                        <option value="none">{$options.2}</option>
                        <option value="all">{$options.3}</option>
                    </select>
                </td>
            </tr>
        </table>
    </div>

    {if isset($customBuilds)}
        <input type="submit" value="{$button}" name="process" class="button">
        <div class="foldout">
            <div class="caption">+ {$captions.1}</div>
            <table class="settings">
                <tr>
                    <td>{$settings.3}</td>
                    <td><input type="text" name="append-name" title="Not implemented yet" disabled></td>
                </tr>
                <tr>
                    <td>{$settings.4}</td>
                    <td>
                        <select name="append-to" title="Not implemented yet" disabled>
                            {foreach from=$customBuilds item=build}
                                <option value="{$build}">{$build}</option>
                            {/foreach}
                        </select>
                    </td>
                </tr>
                {if isset($tocs)}
                    <tr>
                        <td>{$settings.5}</td>
                        <td>
                            <select name="append-toc[]" multiple size="{$tocs|@count}" title="Not implemented yet" disabled>
                                {foreach from=$tocs item=toc}
                                    <option value="{if $toc.title==""}generic{else}{$toc.title}{/if}">
                                        {if $toc.title==""}{$options.0}{else}{$options.1} "{$toc.title}"{/if}
                                    </option>
                                {/foreach}
                            </select>
                        </td>
                    </tr>
                {else}
                    <tr>
                        <td>{$settings.6}</td>
                        <td><input type="checkbox" name="append-toc" title="Not implemented yet" disabled></td>
                    </tr>
                {/if}
                <tr>
                    <td>{$settings.7}</td>
                    <td><input type="checkbox" name="append-topics" title="Not implemented yet" disabled></td>
                </tr>
            </table>
        </div>
        {foreach from=$customBuilds item=build}
            <div class="foldout">
                <div class="caption">{$build}</div>
                <table class="settings">
                    <tr>
                        <td>{$settings.8}</td>
                        <td><input type="text" name="{$build}-rename" title="Not implemented yet" disabled></td>
                    </tr>
                    <tr>
                        <td>{$settings.9}{if !isset($tocs)} {$options.1}{/if}</td>
                        <td>
                            {if isset($tocs)}
                                <select name="removeBuildFromToc[]" multiple size="{$tocs|@count}" title="">
                                    {foreach from=$tocs item=toc}
                                        <option value="{$build}/{$toc.file}">
                                            {if $toc.title==""}{$options.0}{else}{$options.1} "{$toc.title}"{/if}
                                        </option>
                                    {/foreach}
                                </select>
                            {else}
                                <input type="checkbox" name="removeBuildFromToc[{$build}]" title="">
                            {/if}
                        </td>
                    </tr>
                    <tr>
                        <td>{$settings.11}</td>
                        <td>
                            <input type="checkbox" name="{$build}-remove-topics" title="Not implemented yet" disabled>
                        </td>
                    </tr>
                    <tr>
                        <td>{$settings.12}</td>
                        <td>
                            <input type="checkbox" name="{$build}-remove-text" title="Not implemented yet" disabled>
                        </td>
                    </tr>
                </table>
            </div>
        {/foreach}
    {/if}

    <input type="submit" value="{$button}" name="process" class="button">

</form>