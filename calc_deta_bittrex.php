<?php


function getTickerts($url)
{

	$tuCurl = curl_init(); 
	curl_setopt($tuCurl, CURLOPT_URL, $url);  
	curl_setopt($tuCurl, CURLOPT_HEADER, 0); 
	curl_setopt($tuCurl, CURLOPT_RETURNTRANSFER, 1); 
	curl_setopt($tuCurl, CURLOPT_SSL_VERIFYPEER, 0); 
	$tuData = curl_exec($tuCurl); 
	
	if(curl_errno($tuCurl)){ 
	  echo 'Curl error: ' . curl_error($tuCurl); 
	  curl_close($tuCurl); 
	  return null;
	}

 	curl_close($tuCurl); 
	return json_decode($tuData);
}



$btc_eth = getTickerts("https://bittrex.com/api/v1.1/public/getticker?market=BTC-ETH");
if($btc_eth==null  || ($btc_eth->success!=true) )
{
	var_dump($btc_eth);
	echo "bittrex btc_eth failed\n";
	exit(-1);
}


#var_dump($btc_eth);

$eth_ltc = getTickerts("https://bittrex.com/api/v1.1/public/getticker?market=ETH-LTC");
if($eth_ltc==null || ($eth_ltc->success!=true)  )
{
	echo "bittrex ETH-LTC failed\n";
	exit(-1);
}


$eth_etc = getTickerts("https://bittrex.com/api/v1.1/public/getticker?market=ETH-ETC");
if($eth_etc==null || ($eth_etc->success!=true)  )
{
	echo "bittrex ETH-ETC failed\n";
	exit(-1);
}


$eth_sc = getTickerts("https://bittrex.com/api/v1.1/public/getticker?market=ETH-SC");
if($eth_sc==null || ($eth_sc->success!=true)  )
{
	echo "bittrex ETH-ETC failed\n";
	exit(-1);
}


$btc_cny = getTickerts("https://www.okcoin.cn/api/v1/ticker.do?symbol=btc_cny");
if($btc_cny==null)
{
	echo "okcoin btc_cny failed\n";
	exit(-1);
}


$ltc_cny = getTickerts("https://www.okcoin.cn/api/v1/ticker.do?symbol=ltc_cny");
if($ltc_cny==null)
{
	echo "okcoin btc_cny failed\n";
	exit(-1);
}

#print_r($btc_cny);


$eth_cny = getTickerts("https://www.okcoin.cn/api/v1/ticker.do?symbol=eth_cny");
if($eth_cny==null)
{
	echo "okcoin eth_cny failed\n";
	exit(-1);
}



$etc_cny = getTickerts("https://www.okcoin.cn/api/v1/ticker.do?symbol=etc_cny");
if($etc_cny==null)
{
	echo "okcoin etc_cny failed\n";
	exit(-1);
}



$eth_bitrrex_cny_by_btc = $btc_eth->result->Last * $btc_cny->ticker->last;
$eth_bitrrex_cny_by_ltc = (1/$eth_ltc->result->Last) * $ltc_cny->ticker->last;
$eth_bitrrex_cny_by_etc = (1/$eth_etc->result->Last) * $etc_cny->ticker->last;


$eth_okoin_cny=$eth_cny->ticker->last;

$eth_delta_btc = $eth_bitrrex_cny_by_btc - $eth_okoin_cny;
$eth_delta_ltc = $eth_bitrrex_cny_by_ltc - $eth_okoin_cny;
$eth_delta_etc = $eth_bitrrex_cny_by_etc - $eth_okoin_cny;

echo "eth_okoin_cny: $eth_okoin_cny \n";

echo "eth_bitrrex_cny_by_btc: $eth_bitrrex_cny_by_btc delta: $eth_delta_btc\n"; 
echo "eth_bitrrex_cny_by_ltc: $eth_bitrrex_cny_by_ltc delta: $eth_delta_ltc\n";
echo "eth_bitrrex_cny_by_ltc: $eth_bitrrex_cny_by_ltc delta: $eth_delta_etc\n";


$yunbi=getTickerts("https://yunbi.com/api/v2/tickers.json");

$yunbi_eth_cny = $yunbi->ethcny->ticker->last;

$eth_bitrrex_cny_by_btc = $btc_eth->result->Last * $yunbi->btccny->ticker->last;
$eth_bitrrex_cny_by_etc = (1/$eth_etc->result->Last)  * $yunbi->etccny->ticker->last;
$eth_bitrrex_cny_by_sc = (1/$eth_sc->result->Last)  * $yunbi->sccny->ticker->last;

echo "yunbi_eth_cny: $yunbi_eth_cny \n";
echo "eth_bitrrex_cny_by_btc: $eth_bitrrex_cny_by_btc delta: ". ($eth_bitrrex_cny_by_btc-$yunbi_eth_cny) ."\n"; 
echo "eth_bitrrex_cny_by_etc: $eth_bitrrex_cny_by_etc delta: ". ($eth_bitrrex_cny_by_etc-$yunbi_eth_cny) ."\n";
echo "eth_bitrrex_cny_by_sc:  $eth_bitrrex_cny_by_sc delta: ". ($eth_bitrrex_cny_by_sc-$yunbi_eth_cny) ."\n";

$yunbin_sell_one_got_sc = ($yunbi_eth_cny/$yunbi->sccny->ticker->last);

$bittrex_sell_one_eth_got_sc = (1/$eth_sc->result->Last);

echo "yunbin_sell_one_got_sc $yunbin_sell_one_got_sc bittrex_sell_one_eth_got_sc:$bittrex_sell_one_eth_got_sc \n";
echo "yunbin sell $bittrex_sell_one_eth_got_sc sc get  ".($bittrex_sell_one_eth_got_sc*$yunbi->sccny->ticker->last). " cny\n";

echo "yunbin sell 1 eth , got $yunbi_eth_cny cny , then buy  $yunbin_sell_one_got_sc  sc, transfer sc to bittrex \n";
echo "bittrex sell  $yunbin_sell_one_got_sc  sc , got ". ($yunbin_sell_one_got_sc*$eth_sc->result->Last). " eth.";




