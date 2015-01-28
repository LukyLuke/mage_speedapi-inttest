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
					'website' => '1',
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
					'website' => '1',
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
						'website' => '1',
						'sku' => (!empty($param1) ? $param1 : 'ABC 123') . $_SERVER['REQUEST_TIME'] . '-' . $i,
						'coast' => empty($param4) ? '321' : $param4,
						'product_data' => array((object)array(
							'website' => '1',
							'name' => empty($param2) ? 'Product Name' : $param2,
							'description' => empty($param3) ? 'Product Description' : $param3,
							'short_description' => empty($param3) ? 'Product Description Short' : $param3 . ' Short',
							'status' => '1',
						)),
						'website_prices' => array((object)array(
							'website' => '1',
							'price' => empty($param4) ? '321' : $param4,
						)),
						'meta_informations'=> array((object)array(
							'website' => '1',
							'meta_title' => empty($param2) ? 'Product Name' : $param2,
							'meta_description' => empty($param3) ? 'Product Description Short' : $param3 . ' Short',
							'meta_keywords' => 'some,product,keyowrds,in,meta,headers',
						)),
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
				$result = $client->delightSpeedapiProductDelete($session, $list, 'id');
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
