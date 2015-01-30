<?php
header('Expires: Tue, 03 Jul 2001 06:00:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');

/**
 * Highlight an XML string as HTML with same colors as kwrite.
 * 
 * @param string $s The XML String to highlight
 * @return HTML Markup
 */
function highlight_xml($s) {
	$s = htmlspecialchars($s);
	
	// The whole opening and closing tag without the content: <name foo="bar"> ; </name> ; <name foo="bar" />
	$s = preg_replace('#&lt;([/]*?)(.*)([\s]*?)&gt;#sU', '<span style="color:#00000;font-weight:bold;">&lt;\\1\\2\\3&gt;</span>', $s);
	
	// Peamble
	$s = preg_replace('#&lt;([\?a-z]*?)(.*)([\?])&gt;#sU', '<span style="color:#000000;font-weight:bold;">&lt;\\1</span><span style="color:#000000;font-weight:normal;">\\2</span><span style="color:#000000;font-weight:bold;">\\3&gt;</span>', $s);
	
	// Tag nameswithout attributes
	$s = preg_replace('#&lt;([^\s\?/=])(.*)([\[\s/]|&gt;)#iU', '&lt;<span style="color:#000000;font-weight:bold;">\\1\\2</span>\\3', $s);
	$s = preg_replace('#&lt;([/])([^\s]*?)([\s\]]*?)&gt;#iU', '&lt;\\1<span style="color:#000000;font-weight:bold;">\\2</span>\\3&gt;', $s);
	
	// Attributes
	$s = preg_replace('#([^\s]*?)\=(&quot;|\')(.*)(&quot;|\')#isU', '<span style="color:#40805E;font-weight:normal;">\\1=</span><span style="color:#BF2040;font-weight:normal;">\\2\\3\\4</span>', $s);
	
	// CData content
	$s = preg_replace('#&lt;(\[CDATA\[)(.*)(\]])&gt;#isU', '<span style="color:#B08840;font-weight:bold;">&lt;\\1</span><span style="color:#000000;font-weight:normal;">\\2</span><span style="color:#B08840;font-weight:bold;">\\3&gt;</span>', $s);
	return '<code>' . nl2br($s) . '</code>';
}
?>
<html>
<head>
	<style type="text/css">
		html, body, * {
			font-size: 10pt;
		}
		pre, code {
			font-size: 9pt;
			white-space: pre-wrap;
		}
		legend {
			font-weight: bold;
			font-size: 12pt;
			padding: 20px 8px;
		}
	</style>
</head>
	<body>
	<fieldset>
		<legend>Settings</legend>
		<form action="<?php print basename(__FILE__); ?>" method="POST">
			<div>
				<label for="host">Host:</label> <input type="text" name="host" value="<?php print isset($_REQUEST['host']) ? $_REQUEST['host'] : 'http://localhost/'; ?>" />
			</div>
			<div>
				<label for="api_user">API User:</label> <input type="text" name="api_user" value="<?php print isset($_REQUEST['api_user']) ? $_REQUEST['api_user'] : 'phpapi'; ?>" />
			</div>
			<div>
				<label for="api_key">API Key:</label> <input type="text" name="api_key" value="<?php print isset($_REQUEST['api_key']) ? $_REQUEST['api_key'] : 'phpapi'; ?>" />
			</div>
			<fieldset>
				<legend>API Function to test</legend>
				<div>
					<label for="function">Function:</label>
					<?php $func_selected = isset($_REQUEST['function']) ? $_REQUEST['function'] : ''; ?>
					<select name="function">
						<option value="catalogProductInfo"<?php print ($func_selected == 'catalogProductInfo') ? ' selected="selected"' : ''; ?>>catalogProductInfo(param1: sku)</option>
						<option value="delightapiProductInfo"<?php print ($func_selected == 'delightapiProductInfo') ? ' selected="selected"' : ''; ?>>delightapiProductInfo(param1: sku)</option>
						<option value="delightapiProductCreate"<?php print ($func_selected == 'delightapiProductCreate') ? ' selected="selected"' : ''; ?>>delightapiProductCreate(param1: sku, param2: name, param3: description, param4: price)</option>
						<option value="delightapiProductUpdate"<?php print ($func_selected == 'delightapiProductUpdate') ? ' selected="selected"' : ''; ?>>delightapiProductUpdate(param1: sku|product_id, param2: name, param3: description, param4: price, param5: StoreViewID)</option>
						<option value="delightSpeedapiProductMultiple"<?php print ($func_selected == 'delightSpeedapiProductMultiple') ? ' selected="selected"' : ''; ?>>delightSpeedapiProductMultiple(param1: sku, param2: name, param3: description, param4: price)</option>
						<option value="delightSpeedapiProductMultipleGet"<?php print ($func_selected == 'delightSpeedapiProductMultipleGet') ? ' selected="selected"' : ''; ?>>delightSpeedapiProductMultipleGet(param1: sku, param2: attributes, param3: count, param4: offset)</option>
						<option value="delightSpeedapiProductDelete"<?php print ($func_selected == 'delightSpeedapiProductDelete') ? ' selected="selected"' : ''; ?>>delightSpeedapiProductDelete(param1: id, param2: id, param3: id, param4: id, param5: id)</option>
						<option value="delightSpeedapiAdminSetIndexerState"<?php print ($func_selected == 'delightSpeedapiAdminSetIndexerState') ? ' selected="selected"' : ''; ?>>delightSpeedapiAdminSetIndexerState(param1: real_time|manual|...)</option>
						<option value="delightSpeedapiAdminReindex"<?php print ($func_selected == 'delightSpeedapiAdminReindex') ? ' selected="selected"' : ''; ?>>delightSpeedapiAdminReindex()</option>
						<option value="delightSpeedapiAdminFlushCache"<?php print ($func_selected == 'delightSpeedapiAdminFlushCache') ? ' selected="selected"' : ''; ?>>delightSpeedapiAdminFlushCache()</option>
						<option value="xx"<?php print ($func_selected == 'xx') ? ' selected="selected"' : ''; ?>>xx</option>
						<option value="xx"<?php print ($func_selected == 'xx') ? ' selected="selected"' : ''; ?>>xx</option>
						<option value="xx"<?php print ($func_selected == 'xx') ? ' selected="selected"' : ''; ?>>xx</option>
					</select>
				</div>
				<div>
					<label for="param1">Parameter 1:</label> <input type="text" name="param1" value="<?php print isset($_REQUEST['param1']) ? $_REQUEST['param1'] : ''; ?>" /><br/>
					<label for="param2">Parameter 2:</label> <input type="text" name="param2" value="<?php print isset($_REQUEST['param2']) ? $_REQUEST['param2'] : ''; ?>" /><br/>
					<label for="param3">Parameter 3:</label> <input type="text" name="param3" value="<?php print isset($_REQUEST['param3']) ? $_REQUEST['param3'] : ''; ?>" /><br/>
					<label for="param4">Parameter 4:</label> <input type="text" name="param4" value="<?php print isset($_REQUEST['param4']) ? $_REQUEST['param4'] : ''; ?>" /><br/>
					<label for="param5">Parameter 5:</label> <input type="text" name="param5" value="<?php print isset($_REQUEST['param5']) ? $_REQUEST['param5'] : ''; ?>" /><br/>
				</div>
			</fieldset>
			<input type="submit" value="Submit" />
		</form>
	</fieldset>
<?php
if (isset($_REQUEST['host'])) {
	$soapUri = $_REQUEST['host']. '/api/v2_soap?wsdl';
	$apiUser = isset($_REQUEST['api_user']) ? $_REQUEST['api_user'] : 'phpapi';
	$apiKey = isset($_REQUEST['api_key']) ? $_REQUEST['api_key'] : 'phpapi';
	
	$func = isset($_REQUEST['function']) ? $_REQUEST['function'] : '';
	$param1 = isset($_REQUEST['param1']) ? $_REQUEST['param1'] : '';
	$param2 = isset($_REQUEST['param2']) ? $_REQUEST['param2'] : '';
	$param3 = isset($_REQUEST['param3']) ? $_REQUEST['param3'] : '';
	$param4 = isset($_REQUEST['param4']) ? $_REQUEST['param4'] : '';
	$param5 = isset($_REQUEST['param5']) ? $_REQUEST['param5'] : '';

	try {
		$client = new SoapClient($soapUri, array('trace' => 1));
		$client->__setCookie('XDEBUG_SESSION_START', 'kdev');
		$client->__setCookie('XDEBUG_SESSION', 'kdev');
		$session = $client->login($apiUser, $apiKey);
		
		/*
		print('<pre>');
		print_r($client->__getFunctions());
		print('</pre>');
		exit();
		*/
		
		$product_attributes = array(
			'attributes' => array(
				'categories',
				'category_ids',
				'created_at',
				'custom_design',
				'custom_layout_update',
				'description',
				'enable_googlecheckout',
				'gift_message_available',
				'has_options',
				'meta_description',
				'meta_keyword',
				'meta_title',
				'name',
				'options_container',
				'proce',
				'product_id',
				'set',
				'short_description',
				'sku',
				'special_from_date',
				'special_price',
				'special_to_date',
				'status',
				'tax_class_id',
				'type',
				'type_id',
				'updated_at',
				'url_key',
				'url_path',
				'visibility',
				'websites',
				'website_ids',
				'weight',
				'media_list'
			)
		);

		// Call the selected function
		switch ($func) {
			// Original MAGENTO ProductInfo request
			case 'catalogProductInfo':
				$result = $client->catalogProductInfo($session, !empty($param1) ? $param1 : 'ABC 123', 0, $product_attributes, 'sku');
				break;
				
			// DelightAPI Requests which fix some errornous things
			case 'delightapiProductInfo':
				$result = $client->delightapiProductInfo($session, !empty($param1) ? $param1 : 'ABC 123', 0, $product_attributes, 'sku');
				break;
			case 'delightapiProductCreate':
				$productData = array(
					'website' => 'base',
					'name' => empty($param2) ? 'Product Name' : $param2,
					'description' => empty($param3) ? 'Product Description' : $param3,
					'short_description' => empty($param3) ? 'Product Description Short' : $param3 . ' Short',
					'weight' => '12',
					'status' => '1',
					'visibility' => '4',
					'price' => empty($param4) ? '321' : $param4,
					'special_price' => empty($param4) ? '300' : $param4 / 2,
				);
				$result = $client->delightapiProductCreate($session, 'simple', '4', !empty($param1) ? $param1 : 'ABC 123' . $_SERVER['REQUEST_TIME'], $productData);
				break;
			case 'delightapiProductUpdate':
				$productData = array(
					'website' => 'base',
					'name' => empty($param2) ? 'Product Name' : $param2,
					'description' => empty($param3) ? 'Product Description' : $param3,
					'short_description' => empty($param3) ? 'Product Description Short' : $param3 . ' Short',
					'weight' => '12',
					'status' => '1',
					'visibility' => '4',
					'price' => empty($param4) ? '321' : $param4,
					'special_price' => empty($param4) ? '300' : $param4 / 2,
					);
					$result = $client->delightapiProductUpdate($session, !empty($param1) ? $param1 : 'ABC 123 ', $productData, 0, is_numeric($param1) ? 'id' : 'sku');
				break;
				
			case 'delightSpeedapiProductMultiple':
				$productList = array();
				for ($i = 0; $i < 5; $i++) {
					$productList[] = array(
						'set' => '4', // This is the attribute set which is needed
						'type' => 'simple', // This is the product type which is needed
						'weight' => '0',
						'sku' => (!empty($param1) ? $param1 : 'ABC 123') . $_SERVER['REQUEST_TIME'] . '-' . $i,
						'tax_class_id' => '2',
						'website_ids' => array('1'),
						'category_ids' => array('1'),
						'product_data' => array((object)array(
							'website' => 'base',
							'name' => empty($param2) ? 'Product Name' : $param2,
							'description' => empty($param3) ? 'Product Description' : $param3,
							'short_description' => empty($param3) ? 'Product Description Short' : $param3 . ' Short',
							'status' => '1',
						)),
						'price' => empty($param4) ? '321' : $param4,
						/*'website_prices' => array((object)array(
							'website' => 'base',
							'price' => empty($param4) ? '321' : $param4,
						)),*/
						'meta_title' => empty($param2) ? 'Product Name' : $param2,
						'meta_description' => empty($param3) ? 'Product Description Short' : $param3 . ' Short',
						'meta_keyword' => 'some,product,keyowrds,in,meta,headers',
						/*'meta_informations'=> array((object)array(
							'website' => 'base',
							'meta_title' => empty($param2) ? 'Product Name' : $param2,
							'meta_description' => empty($param3) ? 'Product Description Short' : $param3 . ' Short',
							'meta_keyword' => 'some,product,keyowrds,in,meta,headers',
						)),*/
						'media_list' => array(
							(object)array(
								'file' => (object)array(
									'content' => 'iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAAK3RFWHRDcmVhdGlvbiBUaW1lAERpIDE2IEF1ZyAyMDA1IDIyOjU0OjMwICswMTAwXSnWnQAAAAd0SU1FB9MKBhQLIhoeEN8AAAAJcEhZcwAACvAAAArwAUKsNJgAAAAEZ0FNQQAAsY8L/GEFAAACaUlEQVR42n2Ty0tUcRTHv/fnvMxRHB8lFU4PUJGKIiiiVu7aFP0B7UI3LYo2tWtT+ygIAkGICEoINxqFBI6mFEYiaunYNNKo89QZ7525Xu/cX9+bM3Rzhg58+D3O43d+53AUOERKqSRHlN4iXH3Cd/CUafAcT38Lr7qe332i9Idj2Q2amagmUg4IfRSD+tIVKc0PZEhK7bGUm7dl8uNJ+fI+3rcE6jto6nX61ZQ3ty4N3/QEr97xHr3HU4zvpEga2M5hX2M9DtTpx9SNjDI5V1ygQY5Ytp8oB9ix0Otpv0GHGaZToMZ+yMU9TQwDrd1ncaFLXObladJQ9hN/cwl0wsozQBTQ41yJuQUUdaJRH8ShNuUILTtJfdnNVd7oBX0LRrgJ20lIU9u9LDKgmSFZKCIAVZW8QCPxVGQQWyuMIzH85+9SXSFRSG0VVjYKxdcBmZnC1FxxttQFWRHg83Lw4ezEZBp6DGJnHTLPVSZoTltTxejgkPpsRCzuVhj5ii6MTGQyTY0t4cN1sWvNbbXCzDITacIyVHydnLH6HrlfL//S12g6RiJ23f8tIsvwYCDxLr4up+WOhEH1tmZCqXHj+xIW5n/k7VcniN3GQkURS//KJbTW/rdv5s/72UWpAFus54uxBvYW0ZLzprMGzgC2WOPp66Hg8S6c6W5HJBLBTGoF0z+f8vXcMvUZuzdOB7EnANr2+5OF7EpcuATcXg9qips5vaAZVK07Uy+LUmUsRE9Pzzmv1zvo8/n8oVBoKJVK2UV7RRb3DlO1ALb4yQlykbjJJ/KFZJ3//18A+76WNJe+aY+xitIAOeU3NyMZIeKXGz4AAAAASUVORK5CYII=',
									'mime' => 'image/png',
								),
								'types' => array('image', 'small_image'),
								'label' => 'Base Image and Small Image',
								'remove' => false,
								'exclude' => false,
								/*'website_data' => array(
									(object)array(
										'website' => 'base',
										'label' => 'Image and Smallimage',
										'exclude' => false,
										'disable' => false,
										'sort_order' => 1,
										'use_as_image' => true,
										'use_as_thumbnail' => false,
										'use_as_smallimage' => true
									),
								),*/
							),
							(object)array(
								'file' => (object)array(
									'content' => 'iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAAK3RFWHRDcmVhdGlvbiBUaW1lAEZyIDE0IEZlYiAyMDAzIDEwOjU2OjA4ICswMTAwuzpJkAAAAAd0SU1FB9MKBhQLKPrL+cEAAAAJcEhZcwAACvAAAArwAUKsNJgAAAAEZ0FNQQAAsY8L/GEFAAACmElEQVR42n2SS0wTQRjH/7O7fWSXpaWl5VGwotYEw0UTjQc0Pi4iRr15M3rwYDwaY9CD8eLNGBNvRG8kaogxhIMmGgFjQBBRIdZqCaSAIG23LdDHdru7zrRrJBCY5Leb+eZ7zn8ItlpXRiUUkg3IFYOIK7uRToegpEJ8MtGmd5wp4cXpE9QrKWyMu3NrqPbeT2NR4+08RD+Bvgo4ioBNBbgsiE5M6OoidT1FecVtTJDLLfF6cZUHjP9GwoK0WWTD/R7v9DNmoTRRnJs6KGkqAScBs+/6EI/ksTSVRfy7hmJao8eKd1+7tgxylNVi/psSlJedmvtuT8IorNDdPAukJCiZ1tbjbWGNx/YJKn1n6GeEEqHkKUXWYGPDriDm2QSVGbdLwFpOlWl6eNYVEDtVw8yPzyTnYPPBuodNCQgxCoSUj8yywb3/yd3zncGu6YRKphYLGF5zmxAFpoLI/CsJmh51+IPSJdPOObrH/ow4QjvYgDwcLdLJY/U3IimdDMdKQLUbcDmIGxmSprtKBzvvX754IfR4IQ8yEcthlRfPcXaRljdliHVSUhOEj791wE/bliQ4RRskXSukre65I+2BrgzvIG+jKhSnCwg0oka20/pcNVIjqYFIdtSQqgBZhq2mGgda/ViY6P9sqVDi0rogvonSS671AF4v4PHAF/CZ1ngmZnqvcqby2lNvLzQ216SG+7tfYvABk/QXZU2YjMWfotlzHVUyBJeMQ3tcGP8wMAQtq5ali/bMGdGemwq4wwqMILUxdX5QPrF3wUMZ+wpPi1NuqPP7XEI2/L53sPT82hyM0jfqMGVJSUc2Y9b+CyVMWWYjEEtK9q4PUvaye6HMUkatv/pP4g2Sm+uNNlRkqbJsWcrKuuAt11+yD/ahi0x1zQAAAABJRU5ErkJggg==',
									'mime' => 'image/png',
								),
								'types' => array('thumbnail'),
								'label' => 'Thumbnail image',
								'remove' => false,
								'exclude' => false,
								/*'website_data' => array(
									(object)array(
										'website' => 'base',
										'label' => 'Thumbnail image',
										'exclude' => false,
										'disable' => false,
										'sort_order' => 2,
										'use_as_image' => false,
										'use_as_thumbnail' => true,
										'use_as_smallimage' => false
									),
								),*/
							),
						),
					);
				}
				$result = $client->delightSpeedapiProductMultiple($session, $productList);
				break;
			case 'delightSpeedapiProductMultipleGet':
				$attributes = explode(',', !empty($param2) ? $param2 : 'sku,name,description,coast,website,meta_informations');
				$filters = empty($param1) ? array() : array('sku' => $param1);
				$result = $client->delightSpeedapiProductMultipleGet($session, $attributes, (object) $filters, !empty($param4) ? (int)$param4 : 0, !empty($param3) ? (int)$param3 : 0);
				break;
			case 'delightSpeedapiProductDelete':
				$list = array();
				if (!empty($param1)) {
					$list[] = $param1;
				}
				if (!empty($param2)) {
					$list[] = $param2;
				}
				if (!empty($param3)) {
					$list[] = $param3;
				}
				if (!empty($param4)) {
					$list[] = $param4;
				}
				if (!empty($param5)) {
					$list[] = $param5;
				}
				$result = $client->delightSpeedapiProductDelete($session, $list, 'sku');
				break;
			case 'delightSpeedapiAdminSetIndexerState':
				$result = $client->delightSpeedapiAdminSetIndexerState($session, !empty($param1) ? $param1 : 'manual');
				break;
			case 'delightSpeedapiAdminReindex':
				$result = $client->delightSpeedapiAdminReindex($session);
				break;
			case 'delightSpeedapiAdminFlushCache':
				$result = $client->delightSpeedapiAdminFlushCache($session);
				break;
				
			default:
				throw new Exception('The selected function you selected is not testable as of now: ' . $func);
		}
	} catch (Exception $e) {
		print('<fieldset><legend>Error</legend><pre>');
		print_r($e->getMessage());
		print('</pre></fieldset>');
	}
	
	$xmlRequest = new SimpleXMLElement($client->__getLastRequest());
	$xmlResponse = new SimpleXMLElement($client->__getLastResponse());
	$client->endSession($session);

	$request = new DOMDocument('1.0');
	$request->formatOutput = true;
	$request->preserveWhiteSpace = false;
	$request->loadXML($xmlRequest->asXML());

	$response = new DOMDocument('1.0');
	$response->formatOutput = true;
	$response->preserveWhiteSpace = false;
	$response->loadXML($xmlResponse->asXML());

	// Save Request and Response as XML in ./tmp/
	$time = date('Y-m-d_H-i-s', $_SERVER['REQUEST_TIME']);
	$request->save('./tmp/' . $func . '_request_' . $time . '.xml');
	$response->save('./tmp/' . $func . '_response_' . $time . '.xml');
?>
	<fieldset>
		<legend>Request</legend>
		<?php print highlight_xml($request->saveXML()); ?>
	</fieldset>
	<fieldset>
		<legend>Response</legend>
		<?php print highlight_xml($response->saveXML()); ?>
	</fieldset>
	<fieldset>
		<legend>Result-Object</legend>
		<pre><?php print_r($result); ?></pre>
	</fieldset>
<?php
}
?>
	</body>
</html>
