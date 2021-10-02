<?php
	session_start();

	if ( salje_se_ime_i_ploca() )
	{
		$_SESSION['pokusaj']=0;
		$_SESSION['ime']=$_POST['ime'];
		$_SESSION['tablica'] = $_POST['ploca'];
		generiraj_plocu();
		generiraj_opt_plocu();
	}
	
	if( obradi_unos() )
		provjeri_kraj_igre();
	
	//-------------------------------------------------------

	// Funkcija koja se brine za ispis pomocnih brojeva
	// Takodjer sluzi za detekciju krsi li novouneseni broj pravilo igre ili ne

	function ispis_ili_pomocni_brojevi($x,$y,$ispis)
	{
		$array;
		$index=0;
		for( $i=1; $i<=6; $i++){
			if ( $i == $y ) continue;
			if( 0 < $_SESSION['gen_tablica'][$x][$i] && 7 > $_SESSION['gen_tablica'][$x][$i] ){
				$array[$index]=$_SESSION['gen_tablica'][$x][$i];
				$index++;
			}
		}
		for( $i=1; $i<=6; $i++){
			if ( $i == $x ) continue;
			if( 0 < $_SESSION['gen_tablica'][$i][$y] && 7 > $_SESSION['gen_tablica'][$i][$y] ){
				$array[$index]=$_SESSION['gen_tablica'][$i][$y];
				$index++;
			}
		}
		$new_x;
		if( $x%2==0 ) $new_x = $x-1;
		else $new_x = $x+1;
		if( $y < 4){
			if( 0 < $_SESSION['gen_tablica'][$new_x][$y%3+1] && 7 > $_SESSION['gen_tablica'][$new_x][$y%3+1] ){
				$array[$index]=$_SESSION['gen_tablica'][$new_x][$y%3+1];
				$index++;
			}
			if( 0 < $_SESSION['gen_tablica'][$new_x][($y%3+2)%3] && 7 > $_SESSION['gen_tablica'][$new_x][($y%3+2)%3] ){
				$array[$index]=$_SESSION['gen_tablica'][$new_x][($y%3+2)%3];
				$index++;
			}
		}
		else{
			if( 0 < $_SESSION['gen_tablica'][$new_x][($y+1+2*($y-4)*($y-5))%7] && 7 > $_SESSION['gen_tablica'][$new_x][($y+1+2*($y-4)*($y-5))%7] ){
				$array[$index]=$_SESSION['gen_tablica'][$new_x][($y+1+2*($y-4)*($y-5))%7];
				$index++;
			}
			if( 0 < $_SESSION['gen_tablica'][$new_x][($y-1+5*($y-5)*($y-6))%7] && 7 > $_SESSION['gen_tablica'][$new_x][($y-1+5*($y-5)*($y-6))%7] ){
				$array[$index]=$_SESSION['gen_tablica'][$new_x][($y-1+5*($y-5)*($y-6))%7];
				$index++;
			}
		}
		if ($ispis){
			echo '<p style="line-height:0.01px; margin:0px;"><br></p>';
			for($i=1; $i <= 6; $i++)
				if( !in_array($i,$array) )
					echo '<span style ="color: green; font-size:0.6em;" >'.$i.'</span>';
		}
		return $array;
		
	}

	// Funkcija koja, ako je popunjena cijela tablica tocno, ispisuje "WINNER" i postavlja
	// link na ponovno zapocinjanje igre 	

	function provjeri_kraj_igre()
	{
		$kraj=1;
		for ($i=1; $i <= 6; $i++)
			for ($j=1; $j<=6; $j++)
				if ( !( $_SESSION['gen_tablica'][$i][$j] && $_SESSION['opt_tablica'][$i][$j] == $_SESSION['gen_tablica'][$i][$j]) ) 
					$kraj=0;
		if( $kraj ){
			echo '<br><br>';
			echo '<h1>WINNER !</h1><br>';
			echo '<a href="rokudoku.php">Povratak na pocetak!</a>';
		}
	
	}

	// Funkcija koja generira optimalne ploce
	// Posto radimo sa samo dvije ploce, ovakav pristup mi se cinio prihvatljiv
	// Da smo radili sa puno vise ploca, provjeru je li tablica tocno ispunjena
	// bih provjeravao na vise "algoritamski" nacin

	function generiraj_opt_plocu()
	{
		for ($i=1; $i <= 6; $i++)
			for ($j=1; $j<=6; $j++)
				$_SESSION['upisi'][$i][$j] = 0;
		if ($_SESSION['tablica'] == "1")
		{
			$_SESSION['opt_tablica'] = [[0,0,0,0,0,0,0],
						    [0,2,3,4,1,5,6],
						    [0,1,5,6,2,3,4],
						    [0,3,1,2,4,6,5],
						    [0,4,6,5,3,1,2],
						    [0,5,2,1,6,4,3],
						    [0,6,4,3,5,2,1]];
		}
		else
		{
			$_SESSION['opt_tablica'] = [[0,0,0,0,0,0,0],
						    [0,1,2,3,4,5,6],
						    [0,4,5,6,1,2,3],
						    [0,2,3,1,5,6,4],
						    [0,5,6,4,2,3,1],
						    [0,3,1,2,6,4,5],
						    [0,6,4,5,3,1,2]];
		}


	}

	// Funkcija koja obraduje unos korisnika
	// Ako je korisnik odabrao opciju "Zelim sve ispocetka" funkcija ga
	// salje na posebnu stranicu sa linkom na povratak
	// Takodjer funkcija se brine o broju pokusaja

	function obradi_unos()
	{
		$kraj = false;
		if( isset($_POST['radio'] ))
		{
			switch($_POST['radio']){
				case "prvi":
					for ($i=1; $i <= 6; $i++)
						for ($j=1; $j <= 6; $j++){
							if ( isset($_POST[$i.$j]) && preg_match('/^[1-6]{1}$/',$_POST[$i.$j]) ){
								$_SESSION['gen_tablica'][$i][$j] = $_POST[$i.$j];
								$_SESSION['upisi'][$i][$j] = 1;
							}
							else continue;
						}
					break;
				case "drugi":
					if ( !($_SESSION['saved'][$_POST['delete1']][$_POST['delete2']]) )
						$_SESSION['gen_tablica'][$_POST['delete1']][$_POST['delete2']]=0;
					break;
				case "treci":
					$kraj = true;
					break;
			}
			if( $kraj ){
				echo '<a href="rokudoku.php">Povratak na pocetak!</a>';
				return false;
			}

			else{
				$_SESSION['pokusaj']++;
				nacrtaj_plocu();
				return true;
			}
		}
		else{
			nacrtaj_plocu();
			return true;
		}
	}

	// Funkcija koja generira pocetnu plocu

	function generiraj_plocu()
	{
		for ($i=0; $i <= 6; $i++)
			for ($j=0; $j <= 6; $j++)
				$_SESSION['gen_tablica'][$i][$j]=0;
			
		if ($_SESSION['tablica'] == "1")
		{
			$_SESSION['gen_tablica'] = [[0,0,0,0,0,0,0],
						    [0,0,0,4,0,0,0],
						    [0,0,0,0,2,3,0],
						    [0,3,0,0,0,6,0],
						    [0,0,6,0,0,0,2],
						    [0,0,2,1,0,0,0],
						    [0,0,0,0,5,0,0]];

			$_SESSION['saved'] = $_SESSION['gen_tablica'];
		}
		else
		{
			$_SESSION['gen_tablica'] = [[0,0,0,0,0,0,0],
						    [0,1,0,3,0,0,0],
						    [0,0,5,0,1,0,3],
						    [0,0,0,1,5,6,0],
						    [0,0,0,0,2,0,0],
					        [0,3,0,2,0,4,0],
						    [0,6,0,0,0,0,0]];

			$_SESSION['saved'] = $_SESSION['gen_tablica'];
		}
		
	}	

	function salje_se_ime_i_ploca()
	{
		if( isset($_POST['ime'] ) && isset($_POST['ploca'] )) 
			return true;
		return false;
	}

	function nacrtaj_plocu()
	{
		?>
       		<!DOCTYPE html>

            	<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
            	<head>
                	<meta charset="utf-8" />
                	<title>Rokudoku</title>
			<style type="text/css">
        			table {
   					border:1px solid;
    					border-top:solid;
    					border-spacing: 0px;
    					width: 400px;
    					height: 300px;
    					font-size: 20px;
					}
				td {
   					height:50px;
    					width:50px;
    					border:1px solid;
    					text-align:center;
  				}
 				td:first-child {
    					border-left:solid;
  				}
  				td:nth-child(3n) {
    					border-right:solid ;
  				}
  				tr:first-child {
    					border-top:1px solid;
  				}
  				tr:nth-child(2n) td {
    					border-bottom:solid ;
  				}
    			</style>

            	</head>
            	<body>
		<h1> ROKUDOKU ! </h1>
		<br>
		<br>
			<?php
				echo 'Igrac: '.$_SESSION['ime'].'<br><br>';
				echo 'Broj pokusaja: '.$_SESSION['pokusaj'].'<br><br>';
			?>

		<table>
			<form action="Rokudoku_obradi.php" method="post">
                	<?php $n=6;
				for( $i=1; $i<=$n; $i++){
					echo "<tr>";
					for ( $j=1; $j <= $n; $j++){
						$input=false;
						echo "<td>";
						if ($_SESSION['gen_tablica'][$i][$j] != 0){
							if ( $_SESSION['upisi'][$i][$j] == 1 && !in_array($_SESSION['gen_tablica'][$i][$j],ispis_ili_pomocni_brojevi($i,$j,false)))
								echo '<span style ="color: blue;">'.$_SESSION['gen_tablica'][$i][$j].'</span>'; 
							else if ( $_SESSION['upisi'][$i][$j] == 1 )
								echo '<span style ="color: red;">'.$_SESSION['gen_tablica'][$i][$j].'</span>';
							else
								echo "<strong>".$_SESSION['gen_tablica'][$i][$j];
						}
						else {
							$input=true;
							?>
								<input type="text" maxlength="1" name="<?php echo $i.$j; ?>" size="1">
							<?php
						}
						if( $input ){
							ispis_ili_pomocni_brojevi($i,$j,true);
							echo "</td>";
						}
						else
							echo "</td>";
					}
					echo "</tr>";
				}
			?>
		</table>
			<br>
			<input type="radio" name="radio" value="prvi" checked>
				Unos brojeva pomocu textboxeva.
			<br><br>
			<input type="radio" name="radio" value="drugi">
				Obrisi broj iz retka
					<select name="delete1">
       					<option name="del11" value="1">1</option>
						<option name="del12" value="2">2</option>
						<option name="del13" value="3">3</option>
						<option name="del14" value="4">4</option>
						<option name="del15" value="5">5</option>
						<option name="del16" value="6">6</option>
					 </select>
				i stupca
					<select name="delete2">
						<option name="del11" value="1">1</option>
						<option name="del12" value="2">2</option>
						<option name="del13" value="3">3</option>
						<option name="del14" value="4">4</option>
						<option name="del15" value="5">5</option>
						<option name="del16" value="6">6</option>
					 </select>
			<br><br>
			<input type="radio" name="radio" value="treci">
				Zelim sve ispocetka!
			<br><br>
			<button type="submit">Izvrsi akciju!</button>
		</form>

		</body>
		</html>
<?php
}
?>