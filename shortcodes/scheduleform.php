<?php
/**
* Shortcode for Tabs
*
* @category   Tasks
* @package    PratsRoomtypes
* @author     Sumeet Shroff <sumeet@prateeksha.com>
* @copyright  2016 Sumeet Shroff (http://www.prateeksha.com)
* @license    see license.txt
* @link       http://www.prateeksha.com/
*/

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit();
}

/**
* Class Shortcode Tabs
*
* @category   Shortcodes
* @package    PratsRoomtypes
* @author     Sumeet Shroff <sumeet@prateeksha.com>
* @copyright  2016 Sumeet Shroff (http://www.prateeksha.com)
* @license    see license.txt
* @link       http://www.prateeksha.com/
*/

class PratsRoomtypes_Shortcodes_Scheduleform extends PratsRoomtypes_Helpers_Shortcode
{
    /**
    * Method to add the shortcode
    *
    * @return void
    */
    public static function register()
    {
        // Add shortcode
        add_shortcode('roomtypes_scheduleform', array (__CLASS__, 'render'));
    }

    public static function render()
    {
        ob_start();
        ?>
        <p><a class="btn-green" href="#">Schedule Appointment</a></p>
        <div class="clearfix scheduleform">
            <div class="col-md-12">
                <div class="form-group">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="bpad2">
                                <label>First Name</label>
                                <input data-validation="required length number" type="text" class="form-control" id="firstname" name="firstname" data-validation-length="min2" required="" />
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="bpad2">
                                <label>Last Name</label>
                                <input data-validation="required length" type="text" class="form-control" id="lastname" name="lastname" data-validation-length="min2" required="" />
                            </div>
                        </div>
                    </div>

                    <div class="bpad2">
                        <label>Email</label>
                        <input data-validation="required length email" type="text" class="form-control" id="email" name="email" required="" />
                    </div>


                    <div class="bpad2">
                        <label>Phone</label>
                        <input data-validation="required length number" type="text" class="form-control" id="phone" name="phone" data-validation-length="min2" required="" />
                    </div>

                    <div class="bpad2">
                        <label>Select your unit type</label>
                        <select class="form-control list" name="unittype" id="unittype">
                            <option value="offgrid">Off Grid (Battery Backup)</option>
                            <option value="ongrid">On Grid</option>
                        </select>
                    </div>

                    <div class="bpad2">
                        <label>Move in Date</label>
                        <input data-validation="required length number" type="text" class="form-control" id="move_in_date" name="move_in_date" required="" />
                    </div>

                    <div class="bpad2">
                        <label>Message</label>
                        <textarea data-validation="required length" type="text" class="form-control" id="message" name="message" required=""></textarea>
                    </div>

                    <div class="bpad2">
                        <input type="button" id="submit" name="submit" class="btn btn-success center-block" value="SEND" onClick="submitScheduleform();" class="btn-block">
                    </div>

                </div>
            </div>
            <div class="col-md-1 no-padding"> </div>
            <div class="col-md-6 no-padding" id="rightform">

            </div>
        </div>

        <?php
        return ob_get_clean();
    }

}
