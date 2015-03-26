# Exp:resso Store Product Modifiers Range

**Show the actual price range of an Exp:resso Store product based off its modifiers.**

For example, you might have a bunch of colors and a bunch of sizes. One color might cost more, one size might cost less. You will be able to output the highest and lowest prices based off the modifiers.


**Colors**
* Red (+$0)  
* Blue (+$1)  
* Brown (-$1)

**Sizes**
* Small (+$0)  
* Medium (+$0)  
* XL (+$2)  

**Stock Combinations**

* Red Small ($0)
* Red Medium ($0)
* Red XL (+$2)
* Blue Small (+$1)
* Blue Medium (+$1)
* Blue XL (+$3)
* Brown Small (-$1)
* Brown Medium (-$1)
* Brown XL (+$1)

So now the lowest modifier is -$1.00 and the highest is +$3.00.  
If your base product price is $10.00, your real range would be $9.00 to $13.00.


## Installation

Copy the `system/expressionengine/third_party/product_mod_range` folder to your `system/expressionengine/third_party` directory.

## Usage

### Parameters

| Parameter    | Description         |
|--------------|---------------------|
| `entry_id`   | Entry ID. Required. |
| `base_price` | The price of your product. It will be added to your modifier outputs. |

### Variables

| Variable | Description |
|----------|-------------|
| `min`    | The lowest product modifier. |
| `max`    | The highest product modifier. |
| `base_price` | The price passed in through the `base_price` parameter. |

### Conditionals

| Variable | Description |
|----------|-------------|
| `has_mods` | If the product has any modifiers, this returns true. |
| `no_mods` | If the product *doesn't* have any modifiers, this returns true. |


### Example

~~~
<h3>{title}</h3>
{exp:product_mod_range entry_id="{entry_id}" base_price="{price}"}
  {if has_mods}{min} to {max}{/if}
  {if no_mods}{base_price}{/if}
{/exp:product_mod_range}
~~~
Outputs:
~~~
My Product
$9.00 to $13.00
~~~
or if your product has no modifiers:
~~~
My Product
$10.00
~~~


## Support

Please open a Github Issue for support.

https://github.com/pixelfear/store-product-modifiers-range/issues
