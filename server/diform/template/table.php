<? /* @var array|of|\diform\control $controls */ ?>
<form <?= static::attrs($form) ?>>
    <fieldset><? if (isset($legend)) : ?> 
            <legend><?= $legend ?></legend><? endif; ?> 
        <table><? foreach ($this->controls as $control) : ?><? if ($control instanceof \diform\control) : ?> 
            <tr>
                <th><?= $control->label ?></th>
                <td><? if ($control->tip) : ?><a title="<?= $control->tip ?>">?</a><? endif; ?></td>
                <td><?= $control ?></td>
                <td><?= $control->feedback ?></td>
            </tr><? endif; ?><? endforeach; ?> 
        </table>
    </fieldset>
</form>