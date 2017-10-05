<?php

/**
 * Prateeksha_PratsPM - Project Management
 *
 * @category   Projects
 * @package    Prateeksha_PratsPM
 * @author     Sumeet Shroff <sumeet@prateeksha.com>
 * @copyright  2016 Sumeet Shroff Prateeksha Web Design (http://www.prateeksha.com)
 * @license    see license.txt
 * @link       http://www.prateeksha.com/
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
   exit();
}

/**
 * Prateeksha_PratsPM - Project Management
 *
 * @category   Projects
 * @package    Prateeksha_PratsPM
 * @author     Sumeet Shroff <sumeet@prateeksha.com>
 * @copyright  2016 Sumeet Shroff Prateeksha Web Design (http://www.prateeksha.com)
 * @license    see license.txt
 * @link       http://www.prateeksha.com/
 */

class PratsRoomtypes_Helpers_Calendar
{
   /**
    * Current Timestamp
    * @var unknown
    */
   var $current_time;

   /**
    * Date
    * @var unknown
    */
   var $current_date;

   var $current_month;

   var $current_year;

   var $items = array ();

   var $number_of_days = 0;

   var $holiday = array ();

   var $holiday_colour = 'ffe3e3';

   var $user_colors = array ();

   var $func;

   /**
    * Format
    * @var unknown
    */
   var $format_period = NULL;

   /**
    * Format Period options - e.g. Month, Week, Day
    * @var unknown
    */
   var $format_period_options = array (
      'month' => array (
         'title' => 'Month'
      ),
      'week' => array (
         'title' => 'Week'
      ),
      'day' => array (
         'title' => 'Day'
      )
   );

   /**
    * Method to render the calendar
    *
    * @param Array $holidays Holidays for that month
    *
    * @return HTML
    */
   function render()
   {
      $request = PratsRoomtypes::getInput();

      $action = $request->get('action');
      $this->current_time = $request->post('current_time');

      // Unixtime
      if (empty($this->current_time)) {
         $this->current_time = time();
      }

      if ($action == 'shift')
      {

         $shiftmonth = $request->post('shiftmonth');
         $shiftyear = $request->post('shiftyear');
         v($shiftmonth);
         v($shiftyear);

         $this->current_time = mktime(0, 0, 0,
            date('m', $this->current_time) + $shiftmonth,
            date('d', $this->current_time),
            date('Y', $this->current_time) + $shiftyear);
      }

      v($this->current_time);
      v(date('d-m-Y', $this->current_time));
      v($_POST);
      v($time);
      v($action);

      $this->current_day = date('d', $this->current_time);
      $this->current_month = date('m', $this->current_time);
      $this->current_year = date('Y', $this->current_time);

      // $holiday = (array)
      // PratsPM::helper('db')->getModel('postitems')->setPost_Id(
      // $admin_post_id)->setPost_Type('holiday')->getValue();

      // If there are NO holidays for this month and year, reset holiday
      // to empty
      if (isset($holiday [$this->current_year] [(int) $this->current_month])) {
         $this->holiday = $holiday [$this->current_year] [(int) $this->current_month];
      } else {
         $this->holiday = array ();
      }

      return self::renderMonth();
   }

   /**
    * Method to render the calendar
    *
    * @param Array $holidays Holidays for that month
    *
    * @return HTML
    */
   function renderMonth()
   {
      // Get the current first day of week
      $first_day_of_week = self::getFirstDayOfWeek($this->current_month,
         $this->current_year);

      // Get the Number of Days
      $number_of_days = self::getNumberOfDays($this->current_month,
         $this->current_year);
      ?>
<h1><?php echo __('Calendar Task'); ?></h1>
<form method="POST" name="calendar-task" id="calendar-task"
	action="admin.php?page=pratspm_taskcalendar">
	<table style="width: 100%;">
		<tr>
			<td width="8%" style="vertical-align: middle;"><button
					onClick="calendarShift(0,-1);"><</button>
				<button onClick="calendarShift(0,1);">></button>
				<button
					onClick="calendarResetTime('<?php echo time(); ?>');">Today</button>
				</td>
			<td valign="middle" style="vertical-align: middle;"><h2
					style="text-align: center; background-color: red;">
					<button
					onClick="calendarShift(-1,);"><</button>
					<?php echo date('M Y', $this->current_time); ?>
					<button onClick="calendarShift(1,0);">></button>
					</h2>
					</td>
			<td width="15%" style="vertical-align: middle;"><a href="">Day</a> <a
				href="">Week</a> <a href="">Month</a></td>
		</tr>
	</table>
	<table class="table table-striped clearfix" id="articleList"
		style="width: 100%; padding: 20px 20px 0px 0px;">
		<tr>
			<th class="cal-title"><?php echo __('Sunday'); ?></th>
			<th class="cal-title"><?php echo __('Monday'); ?></th>
			<th class="cal-title"><?php echo __('Tuesday'); ?></th>
			<th class="cal-title"><?php echo __('Wednesday'); ?></th>
			<th class="cal-title"><?php echo __('Thursday'); ?></th>
			<th class="cal-title"><?php echo __('Friday'); ?></th>
			<th class="cal-title"><?php echo __('Saturday'); ?></th>
		</tr><?php
      $start = 1;
      ?>
        <tr><?php

      for ($i = 1; $i <= 42; $i++) {

         $color = '#ffffff';
         if ($i < $first_day_of_week || $start > $number_of_days) {
            $color = '#eaeaea';
         }

         if ($i >= $first_day_of_week && in_array((int) $start, $this->holiday)) {
            $color = '#' . $this->holiday_colour;
         } else

            ?>
          <td class="calendar-cell" style="background-color: <?php echo $color; ?>; height: 150px;" width="14%"><?php
         if ($i < $first_day_of_week || $start > $number_of_days) {
            echo '';
         } else {
            ?>
                <div class="calendar-number" style="text-align: right;">
                    <?php echo $start; ?>
                </div>
				<div>
                    <?php echo $this->renderItem($start); ?>
                </div>
				<div style="background-color: #cccccc; margin-top: 20px;">
                    <?php echo $this->renderItemBottom($start); ?>
                </div>
                <?php
            $start++;
         }
         ?>
          </td>                 <?php
         if (($i % 7) == 0) {
            ?>
                </tr>
		<tr><?php
         }
      }
      ?></tr>
	</table>
	<input type="hidden" name="action" id="action" value="" /> <input
		type="hidden" name="shiftyear" id="shiftyear" value="" /> <input
		type="hidden" name="shiftmonth" id="shiftmonth" value="" /> <input
		type="hidden" name="current_time" id="current_time" value="<?php echo $this->current_time; ?>" />

</form><?php
   }

   function setItems($items)
   {
      foreach ($items as $item) {
         $this->addItemForMonth($item->day, $item);
      }

      return true;
   }

   /**
    * Add item to the cell
    *
    * @param Integer $day
    * @param Array $item
    *
    * @return void
    */
   function addItemForMonth($day, $item)
   {
      // Create Items array is not there
      $day = intval($day);
      if (!isset($this->items [$day])) {
         $this->items [$day] = array ();
      }

      if (!isset($this->user_colors [$item->user_id]) && $item->user_id) {
         $this->user_colors [$item->user_id] = PratsPM::helper('db')->getModel(
            'Users')->setUser_Id($item->user_id)->getColumn('color');
      }

      // Add to items array
      $this->items [$day] [] = (array) $item;
   }

   /**
    * Render item
    *
    * @param Integer $day
    *
    * @return NULL|string
    */
   function renderItem($day)
   {
      if (!isset($this->items [$day])) {
         return NULL;
      }

      $array = array ();

      foreach ($this->items [$day] as $item) {

         $time_in_min = PratsPM::helper('db')->getModel('postmeta')->setPost_Id(
            $item ['post_id'])->setMetaKey('time_in_min')->getValue();

         $html = sprintf(
            '<div class="calendar-item" style="width: 100%%; border-left: 4px solid #%s"><strong>[%s]</strong> %s [%s]</div>',
            $this->user_colors [$item ['user_id']], $item ['user_name'],
            $item ['title'], JHTML::_('time.hours', $time_in_min));

         $html = JHTML::tooltip($item ['content'] . ' ' . $time_in_min . ' min',
            $item ['user_name'], 'tooltip.png', $html);

         $array [] = $html;
      }

      return implode('', $array);
   }

   /**
    * Render item
    *
    * @param Integer $day
    *
    * @return NULL|string
    */
   function renderItemBottom($day)
   {
      if (!isset($this->items [$day])) {
         return NULL;
      }

      $time_in_min_arr = array ();
      $total = 0;

      foreach ($this->items [$day] as $item) {

         if (!isset($time_in_min_arr [$item ['user_id']])) {
            $time_in_min_arr [$item ['user_id']] = 0;
         }

         $time = PratsPM::helper('db')->getModel('postmeta')->setPost_Id(
            $item ['post_id'])->setMetaKey('time_in_min')->getValue();
         $time_in_min_arr [$item ['user_id']] += $time;
         $total += $time;
      }

      $array = array ();
      if ($time_in_min_arr) {
         foreach ($time_in_min_arr as $key => $row) {
            $row_str = JHTML::_('time.hours', $row);
            $user_name = PratsPM::helper('db')->getModel('Users')->setUser_Id($key)->getColumn(
               'name');

            $html = sprintf(
               '<div class="calendar-item" style="width: 100%%; border-left: 4px solid #%s">%s [%s]</div>',
               $this->user_colors [$key], $user_name, $row_str);

            $html = JHTML::tooltip($user_name . ' ' . $row_str, $user_name,
               'tooltip.png', $html);

            $array [] = $html;
         }

      }

      $array [] = sprintf(
         '<div class="calendar-item" style="width: 100%%; border-left: 4px solid red">Total [%s]</div>',
         JHTML::_('time.hours', $total));

      return implode('', $array);
   }

   /**
    *
    * @param unknown $month
    * @param unknown $year
    * @return number
    */
   function getNumberOfDays($month, $year)
   {
      $month = intval($month);
      $year = intval($year);

      return cal_days_in_month(CAL_GREGORIAN, $month, $year);
   }

   /**
    *
    */
   function getFirstDayOfWeek($month, $year)
   {
      $month = intval($month);
      $year = intval($year);
      return intval(date('w', mktime(0, 0, 0, $month, 1, $year))) + 1;
   }

}
