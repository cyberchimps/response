<?php 
/**
* Comments template used by the CyberChimps Response Core Framework
*
* Authors: Tyler Cunningham, Trent Lapinski
* Copyright: © 2012
* {@link http://cyberchimps.com/ CyberChimps LLC}
*
* Released under the terms of the GNU General Public License.
* You should have received a copy of the GNU General Public License,
* along with this software. In the main directory, see: /licensing/
* If not, see: {@link http://www.gnu.org/licenses/}.
*
* @package Response
* @since 1.0.5
*/

?>

<!--Begin response_before_comments hook-->
	<?php response_before_comments(); ?>
<!--End response_before_comments hook-->

<!--Begin response_comments hook-->
	<?php response_comments(); ?>
<!--End response_comments hook-->

<!--Begin response_after_comments hook-->
	<?php response_after_comments(); ?>
<!--End response_after_comments hook-->