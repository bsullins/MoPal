<?php

require_once("header.php");
require_once("functions.php");

//if the form was submitted, do something
if( isset($_POST['go']) ){
	$results = json_decode(queryES($_POST['host'], $_POST['q']), true);
}

?>

<h1>Welcome to MoPal!</h1>

<div id="wrapper">

	<p>Enter your<a href="http://elasticsearch.org" target="_blank">Elastic Search</a>host and query below and click Go!<br/><span style="font-size:12px;">(then export to CSV below)</span></p>
	<div id="search-form">
		<form action="index.php" method="post">
			<table>
				<tr>
					<td class="label">host<br/>(w/ index)</td>
					<td colspan="2"><input type="text" id="host" name="host" style="width:100%" value="<?php if (isset($_POST['host'])) { echo $_POST['host']; } ?>"/></td>
				</tr>
				<tr><td class="label">query<br/>(must have fields)</td><td rowspan="3"><textarea name="q" rows="11" cols="80"><?php if (isset($_POST['q'])) { echo $_POST['q']; } ?></textarea></td></tr>
				<tr><td align="right">preview rows:<input type="text" name="preview" id="preview" style="width:60px;" value="<?php if (isset($_POST['preview'])) { echo $_POST['preview']; } else { echo "10"; } ?>" /></td></tr>
				<tr><td align="right" style="vertical-align:bottom;"><input type="submit" name="go" class="button" value="GO!"></td></tr>


				<tr>
					<td></td>

				</tr>
			</table>
		</form>
	</div>

	<div id="results">
		<?php
				if ( isset($results) ) {

					//get relative URL
					$url = formUrl('csv.php');

					?>
						<form action="<?php echo $url; ?>" method="post" target="_blank">
							<input type="hidden" name="ESQuery" id="ESQuery" value="<?php if (isset($_POST['q'])) { echo urlencode($_POST['q']); } ?>" />
							<input type="hidden" name="ESHost" id="ESHost" value="<?php if (isset($_POST['host'])) { echo $_POST['host']; } ?>"/>
							.csv filename:<input type="text" name="filename" id="filename" value="<?php if (isset($_POST['filename'])) { echo $_POST['filename']; } ?>" /><input type="submit" value="download .csv" />
						</form>

					<?php

					echo array2table(parseHits($results), 'data-table', $_POST['preview']);

				}

			?>

	</div> <!-- results -->
</div> <!-- wrapper -->
<?php

require_once("footer.php");

?>