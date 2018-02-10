<!DOCTYPE html>
<html>
wowzer
<style>  
table, th, td {
    border: 1px solid black;
    border-collapse: collapse;
}
th, td {
    padding: 3px;
}
tr:nth-child(odd){
	background-color:#eee;
}
</style>
<?php
if (!$link = mysql_connect('localhost', 'user', 'password')) {
    echo 'Could not connect to mysql';
    exit;
}

if (!mysql_select_db('mystuff', $link)) {
    echo 'Could not select database';
    exit;
}

$transactions = array_map('str_getcsv', file('transactions.csv'));
// var_dump($transactionData);

$count = 0;
foreach($transactions as $transaction)
{
	if($count++ > 0)
	{
		$transactionDate = strtotime($transaction[1]);
		$unixTime = date('U', $transactionDate);
		$description = $transaction['3'];
		$amount = $transaction[4];
		$dc = $transaction[5];
		if($dc == 'CR')
		{
			$dc = 0;
		}
		else if ($dc == 'DR')
		{
			$dc = 1;
		}

	}
	$sql = "INSERT INTO `mystuff`.`transactions` (`id`, `description`, `amount`, `debit/credit`, `transaction_date`) VALUES (NULL, '" . $description . "', '" . $amount . "', '" . $dc . "', FROM_UNIXTIME('" . $unixTime . "'));";
	mysql_query($sql, $link);
	//echo 'MySQL Error: ' . mysql_error();
}

$sql    = 'SELECT * FROM transactions';
$result = mysql_query($sql, $link);

if (!$result) {
    echo "DB Error, could not query the database\n";
    echo 'MySQL Error: ' . mysql_error();
    exit;
}

echo '<html>';
echo '<style>table, th, td { border: 1px solid black;}</style>';
echo '<table style="width:50%">';
echo '<tr><th>id</th><th>description</th><th>amount</th><th>debit/credit</th><th>transaction date</th><th>date</th></tr>';
while ($row = mysql_fetch_assoc($result)) {
	echo '<tr>';
    	echo '<td>' . $row['id'] . '</td>';
    	echo '<td>' . $row['description'] . '</td>';
    	echo '<td>' . $row['amount'] . '</td>';
    	echo '<td>' . $row['debit/credit'] . '</td>';
    	echo '<td>' . $row['transaction_date'] . '</td>';
    	echo '<td>' . $row['date'] . '</td>';
	echo '</tr>';
}
echo '</table>';
echo '</html>';
mysql_free_result($result);

?>
</html>
