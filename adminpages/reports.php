<?php


if (!defined('ABSPATH'))
{
    exit('Please do not load this file directly.');
}


class PratsRoomtypes_AdminPages_Reports
{
    /**
 	 * Block comment
 	 *
 	 * @param type
 	 * @return void
	 */
    public static function getView($name)
    {
        $filename = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . $name . DIRECTORY_SEPARATOR . 'view.php';
        if ( file_exists($filename) )
        {
            include_once $filename;
            return;
        }

        echo 'no view found';
    }


    

    /**
    * Block comment
    *
    * @param type
    * @return void
    */
    public static function render()
    {
        $input = PratsRoomtypes::getInput();
        $action  = $input->request('action');

        switch($action)
        {
            default:
            $view = self::getView('invoices');
            break;
        }

    }


}
