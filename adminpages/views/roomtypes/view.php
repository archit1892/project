<?php

if (!defined('ABSPATH'))
{
    exit('Please do not load this file directly.');
}


$input = PratsRoomtypes::getInput();

$page 			= $input->request('page');
$action 		= $input->request('action', 'default');

v($_REQUEST);

?>

<h1 class="pageheader"><?php _e('Invoices'); ?></h1>
<?php
$a = PratsRoomtypes::getInstance();
$model = $a->getModel('invoices');
$fields = $model->getFields();
$rows = $model->getList();

//v($rows);

?>
<form action="" method="get" name="mainform" id="mainform" width="100%">
    <table border="0" cellpadding="0" cellspacing="0" class="wp-list-table widefat fixed striped posts">
        <tr>
            <td>
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                        <?php
                        foreach( $fields as $field)
                        {
                            ?>
                            <td align="center">
                                <div class="tablehead manage-column column-title column-primary sortable desc"><?php echo $field['name']; ?></div>
                            </td>
                            <?php
                        }
                        ?>
                        <td align="center">
                        </td>
                    </tr>
                    <?php
                    $count = 1;
                    foreach ($rows as $row)
                    {
                        ?>
                        <tr>
                            <?php
                            foreach($fields as $field)
                            {
                                ?>
                                <td><div class="rowitem" style="color: #002073"><?php
                                $function_name = $field['type'].'Callback';
                                echo call_user_func_array( array($model, $function_name),  array($row->$field['id'])); ?>
                                </div>
                                </td>
                                <?php
                            }
                            ?>
                            <td align="center">
                                <input name="editcat" type="submit" value=" Edit " onClick="document.mainform.action.value='category';document.mainform.categories_id.value = '<?php echo $category_id; ?>';">
                                <input name="deletecat" type="submit" value=" Delete " onClick="document.mainform.action.value='deletecategory';document.mainform.categories_id.value = '<?php echo $category_id; ?>'; return true;">
                            </td>
                        </tr>
                        <?php
                        $count++;
                    }
                    ?>
                </table>
            </td>
        </tr>
    </table>
    <input name="categories_id" type="hidden" value="<?php echo $categories_id; ?>">
    <input name="parent_id" type="hidden" value="<?php echo $categories_id; ?>">
    <input name="action" type="hidden" value="">
    <input name="page" type="hidden" value="<?php echo $page; ?>">

    <div style="padding: 10px">
        <input name="category" type="submit" value="Add Category" onClick="document.mainform.action.value = 'addcategory';">
    </div>
</form>
