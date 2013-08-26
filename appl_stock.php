<?php
// Fetch live data
$stock = fetch_live_quote('AAPL');
function fetch_live_quote($stock_symbol)
{
	if (!$quote = file_get_contents("http://www.google.com/finance/info?q=".$stock_symbol))
	{
		return $result['error'] = 'ERR';
	}
	
	// Encode to UTF8 (json_decode only likes UTF8)
	$quote = utf8_encode($quote);
	
	// Fix the format to make it valid JSON
	$quote = substr(trim($quote),3);
	
	if (!$quote = json_decode($quote,true))
	{
		return $result['error'] = 'ERR';
	}
	
	// We only need the first quote
	$quote = $quote[0];
	
	$result = array(
		'id'			=>	$quote['id'],
		'symbol'		=>	$quote['t'],
		'exchange' 		=>	$quote['e'],
		'l_price'		=>	$quote['l'],
		'l_trade_df'	=>	$quote['ltt'],
		'l_trade'		=>	strtotime($quote['ltt']),
		'change' 		=>	$quote['c'],
		'change_p'	 	=>	$quote['cp'],
		'e_price'		=>	isset($quote['el'])?$quote['el']:"",
		'e_trade_df'	=>	isset($quote['elt'])?$quote['elt']:"",
		'e_trade'		=>	isset($quote['elt'])?date('g:i A',strtotime($quote['elt'])):"",
		'e_change'		=>	isset($quote['ec'])?$quote['ec']:"",
		'e_change_p'	=>	isset($quote['ecp'])?$quote['ecp']:""
	);
	
	return $result;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf8" />
		<meta http-equiv="Cache-control" content="no-cache" />
		<meta http-equiv="refresh" content="300;url=appl_stock.php">
		
		<style type="text/css">
			@font-face
			{
				font-family: "Roadgeek2005SeriesC";
				src: url("http://panic.com/fonts/Roadgeek 2005 Series C/Roadgeek 2005 Series C.otf");
			}
			
			body, *
			{
			
			}
			body,div,dl,dt,dd,ul,ol,li,h1,h2,h3,h4,h5,h6,pre,form,fieldset,input,textarea,p,blockquote,th,td
			{ 
				margin: 0;
				padding: 0;
			}
				
			fieldset,img
			{ 
				border: 0;
			}
			
				
			/* Settin' up the page */
			
			html, body, #main
			{
				overflow: hidden; /* */
			}
			
			body
			{
				color: white;
				font-family: 'Roadgeek2005SeriesC', sans-serif;
				font-size: 20px;
				line-height: 24px;
			}
			body, html, #main
			{
				background: transparent !important;
			}
			
			#stock_quote
			{
				width: 250px;
				height: 250px;
				text-align: center;
				/*background-color: #000;*/
			}
			#stock_quote *
			{
				font-weight: normal;
			}
			
			h1
			{
				font-size: 60px;
				line-height: 60px;
				margin-top: 15px;
				margin-bottom: 8px;
				<?php
				if($stock['change'] >= 0) {
				?>
					color: rgb(0,186,0);
				<?php } else { ?>
					color: rgb(255,48,0);
				<?php } ?>
				text-shadow:0px -2px 0px black;
				text-transform: uppercase;
			}
			
			h2
			{
				width: 180px;
				margin: 0px auto;
				padding-top: 15px;
				font-size: 24px;
				line-height: 28px;
				color: #7e7e7e;
				text-transform: uppercase;
			}

			#change
			{
				width: 180px;
				color: #7e7e7e;
				font-size: 22px;
				line-height: 24px;
				text-align: center;
				margin: 0 auto;
			}

			.change {
				color: #fff;
			}

			#late
			{
				font-size: 18px;
				line-height: 20px;
				color: #7e7e7e;
			}
		</style>
	</head>
	
	<body>
		<div id="main">
		
			<div id="stock_quote">
				<h2>What's $AAPL worth right now?</h2>
				<h1 id="quote"><?php echo($stock['error'] ? "Error" : $stock['l_price']); ?></h1>
				<p id="change">Change: <span class="change"><?=$stock['change']?> (<?=$stock['change_p']?>%)</span></p>
				<?php if($stock['e_price']) { ?>
				<p id="late">Late: <span class="change"><?=$stock['e_price']?> as of <?=$stock['e_trade']?></span></p>
				<?php } ?>
				<p style="font-size: 12px;">(price delayed)</p>
			
			</div><!-- spacepeopleContainer -->

		</div><!-- main -->
	</body>
</html>