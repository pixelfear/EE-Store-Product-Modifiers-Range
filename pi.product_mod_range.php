<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Store Product Modifiers Range Plugin
 *
 * @package		ExpressionEngine
 * @subpackage	Addons
 * @category	Plugin
 * @author		Jason Varga
 * @link		http://pixelfear.com
 */

$plugin_info = array(
	'pi_name'        => 'Store Product Modifiers Range',
	'pi_version'     => '1.0',
	'pi_author'      => 'Jason Varga',
	'pi_author_url'  => 'http://pixelfear.com',
	'pi_description' => 'Display ranges of price modifiers for Exp:resso Store products',
	'pi_usage'       => Product_mod_range::usage()
);


class Product_mod_range {

	public $return_data = '';
	protected $mod_sums = array();
    
	/**
	 * Constructor
	 */
	public function __construct()
	{
		// Base price?
		$base_price = ee()->TMPL->fetch_param('base_price', 0);
		$base_price = preg_replace('/[\$,]/', '', $base_price);

		// Get entry
		$entry_id = ee()->TMPL->fetch_param('entry_id');

		// Get modifier ids
		$mod_id_query = ee()->db->select('product_mod_id')
		                        ->where('entry_id', $entry_id)
		                        ->get('store_product_modifiers');

		$mod_ids = array();
		foreach ($mod_id_query->result_array() as $mod)
		{
			$mod_ids[] = $mod['product_mod_id'];
		}
		
		// Product has no mods? Bail out.
		if (count($mod_ids) == 0)
		{
			$max = $base_price;
			$min = $base_price;
			$has_mods = false;
		}

		// Has mods? Keep going.
		else
		{
		
			// Get option modifier prices and put them into modifier groups
			$opts_query = ee()->db->where_in('product_mod_id', $mod_ids)
			                      ->get('store_product_options');

			$mod_groups = array();
			foreach ($opts_query->result_array() as $option)
			{
				$mod_price = isset($option['opt_price_mod']) 
				             ? floatval($option['opt_price_mod'])
				             : 0;
				$mod_groups[$option['product_mod_id']][] = $mod_price;
			}

			// Then get all the combinations
			$mod_group_cartesian = call_user_func_array(array($this,'cartesian_product'), $mod_groups);

			// Get the sum of each array
			foreach ($mod_group_cartesian as $group)
			{
				$this->mod_sums[] = floatval(array_sum($group));
			}

			// Vars
			$max = number_format(max($this->mod_sums) + $base_price, 2);
			$min = number_format(min($this->mod_sums) + $base_price, 2);
			$has_mods = true;
		
		}

		$vars = array(array(
			'has_mods'   => $has_mods,
			'no_mods'    => !$has_mods,
			'max'        => $max,
			'min'        => $min,
			'base_price' => $base_price
		));

		$this->return_data = ee()->TMPL->parse_variables(ee()->TMPL->tagdata, $vars);
	}

	private function cartesian_product()
	{
		$_ = func_get_args();
		if(count($_) == 0)
			return array(array());
		$a = array_shift($_);
		$c = call_user_func_array(array($this,__FUNCTION__), $_);
		$r = array();
		foreach($a as $v)
			foreach($c as $p)
				$r[] = array_merge(array($v), $p);
		return $r;
	}
	
	// ----------------------------------------------------------------
	
	/**
	 * Plugin Usage
	 */
	public static function usage()
	{
		ob_start();
?>
Outputs the highest or the lowest product modifier.
<?php
		$buffer = ob_get_contents();
		ob_end_clean();
		return $buffer;
	}
}

/* End of file pi.product_mod_range.php */
/* Location: /system/expressionengine/third_party/product_mod_range/pi.product_mod_range.php */