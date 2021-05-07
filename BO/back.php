<?php
error_reporting(0);
require_once('PHPExcel.php');
define('HOST','10.169.7.107');
define('USER','infor');
define('PASSWORD','servinfodistant');
define('PORT','3306');
define('BDD','apps2m');

$dateDebut = date('d-m-Y',strtotime($_POST['dateDebut_x']));
$dateFin = date('d-m-Y',strtotime($_POST['dateFin_x']));

 $fileName = 'RecapCA-'.$dateDebut.'_au_'.$dateFin.'-'.time().'.xlsx';

$option= array(

PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
	);
try
{
	$bdd_openERP = new PDO('mysql:host='.HOST.';dbname='.BDD.';port='.PORT, USER, PASSWORD,$option);
	$bdd_openERP->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);	
	
}
catch(Exception $err)
{
	die('erreur ['.$err->getCode().'] '.$err->getMessage());
}

$reponse = $bdd_openERP->query("select id_magasin, Nom_magasin, Num_magasin, Categorie_magasin, ip_caisse from magasin where Categorie_magasin in (3,9) and Num_magasin != 0 and id_magasin not in (28,1007) order by Num_magasin asc limit 5");

  $objPHPExcel = new PHPExcel();

 		$mois_debut = date("m",strtotime($dateDebut));
        $jour_debut = date("d",strtotime($dateDebut));
        $annee_debut = date("y",strtotime($dateDebut));

        $mois_fin = date("m",strtotime($dateFin));
        $jour_fin = date("d",strtotime($dateFin));
        $annee_fin = date("y",strtotime($dateFin));

        $debut_date = mktime(0, 0, 0, $mois_debut, $jour_debut, $annee_debut);
        $fin_date = mktime(0, 0, 0, $mois_fin, $jour_fin, $annee_fin);


          $objPHPExcel->setActiveSheetIndex(0);
		  $objPHPExcel->getActiveSheet()->SetCellValue('A1', '');
		  $objPHPExcel->getActiveSheet()->SetCellValue('B1', '');
		  $nbrMag = 2;

		  $nbrCA = 2;
		  $nbrCli = 4;
		  $nbrArt = 6;
		  $nbrPM = 8;
		  $nbrCons = 10;

		  while($donnees = $reponse->fetch())
			{
				$objPHPExcel->getActiveSheet()->getStyle('A' . $nbrMag)->getFill()
                                              ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
                                              ->getStartColor()->setARGB('00B0F0');

				$objPHPExcel->getActiveSheet()->SetCellValue('A'.$nbrMag, $donnees['Nom_magasin']);
		  		$objPHPExcel->getActiveSheet()->SetCellValue('B'.$nbrCA, "Chiffre d'affaires");
		  		$objPHPExcel->getActiveSheet()->SetCellValue('B'.$nbrCli, "Nombre clients");
		  		$objPHPExcel->getActiveSheet()->SetCellValue('B'.$nbrArt, "Nombre d'articles");
		  		$objPHPExcel->getActiveSheet()->SetCellValue('B'.$nbrPM, "Panier Moyen");
		  		$objPHPExcel->getActiveSheet()->SetCellValue('B'.$nbrCons, "CA CONSIGNE");
		  		
		  		  $nbrMag += 10;
		  		  $nbrCA += 10;
				  $nbrCli += 10;
				  $nbrArt += 10;
				  $nbrPM += 10;
				  $nbrCons += 10;
			}

		  $count = 0;

        for($i = $debut_date; $i <= $fin_date; $i+=86400)
        {
			$nbrCA_ = 2;
		    $nbrCli_ = 4;
		    $nbrArt_ = 6;
		    $nbrPM_ = 8;
		    $nbrCons_ = 10;

        	$count += 1;
        	$date_use = date("d-m-Y",$i); 
        	$col = colone($count);

		  	$objPHPExcel->getActiveSheet()->SetCellValue( $col.'1', $date_use);

		  	$reponse2 = $bdd_openERP->query("select id_magasin, Nom_magasin, Num_magasin, Categorie_magasin, ip_caisse from magasin where Categorie_magasin in (3,9) and Num_magasin != 0 and id_magasin not in (28,1007) order by Num_magasin asc limit 5");
		  	
			while($donnees2 = $reponse2->fetch())
			{
				//var_dump($donnees2['ip_caisse']);
			    $host = $donnees2['ip_caisse'];
				$port = "5432";
				$dbName ="s2mcaisse";
				$user = "infor";
				$psw = "servinfo";

			    $connex = pg_connect("host=".$host." port = ".$port." dbname = ".$dbName."  user= ".$user."  password=".$psw);
				
				$sq_CA = "SELECT sum(price) AS prix from ticketlines WHERE DATE(datecreation) = '".$date_use."'";   
			    $sq_NbrClient = "SELECT COUNT(DISTINCT(ticket)) FROM ticketlines WHERE DATE(datecreation) = '".$date_use."'";     
			    $sqlCons = "SELECT SUM(price) AS consignation FROM ticketlines AS tl INNER JOIN products AS p ON tl.product = p.reference WHERE DATE(datecreation) >= '".$date_use."' AND DATE(datecreation) <= '".$date_use."' AND p.name LIKE 'CONS%'";
			    $sqlArt = "SELECT SUM(units) AS unite FROM ticketlines WHERE DATE(datecreation) = '".$date_use."'";
			    
			    $res_ca = pg_fetch_row(pg_query($connex,$sq_CA));
			    $res_nbrClient = pg_fetch_row(pg_query($connex,$sq_NbrClient));
			    $res_cons = pg_fetch_row(pg_query($connex,$sqlCons));
			    $res_article = pg_fetch_row(pg_query($connex,$sqlArt));

			   /* var_dump($col.$nbrCA_.'  '.$res_ca[0]);
			    var_dump($col.$nbrCli_.'  '.$res_nbrClient[0]);
			    var_dump($col.$nbrArt_.'  '.$res_cons[0]);
			    var_dump($col.$nbrPM_.'  '.$res_article[0]);
			    var_dump( $col.$nbrCons_.'  '.$res_ca[0]/$res_nbrClient[0]);*/

			    $objPHPExcel->getActiveSheet()->SetCellValue( $col.$nbrCA_, $res_ca[0]);
			    $objPHPExcel->getActiveSheet()->SetCellValue( $col.$nbrCli_, $res_nbrClient[0]);
			    $objPHPExcel->getActiveSheet()->SetCellValue( $col.$nbrArt_, $res_article[0]);
			    $objPHPExcel->getActiveSheet()->SetCellValue( $col.$nbrPM_, round($res_ca[0]/$res_nbrClient[0]));
			    $objPHPExcel->getActiveSheet()->SetCellValue( $col.$nbrCons_,$res_cons[0] );

			      $nbrCA_ += 10;
				  $nbrCli_ += 10;
				  $nbrArt_ += 10;
				  $nbrPM_ += 10;
				  $nbrCons_ += 10;
			}
        }

          $result_file = '../save/'.$fileName;
          $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
	      //$objWriter->save($result_file);

	      $return = "";
	      if($objWriter->save($result_file))
	      {
	      	$return = "";
	      }
	      else
	      {

	      	$return = 'save/'.$fileName;
	      }

	      print_r($return);
	    

	      header("Content-Type: application/vnd.ms-excel");

		function colone($nbr){
			switch ($nbr) {
				case '1':
					$col = 'C';
					break;
				case '2':
					$col = 'D';
					break;
				case '3':
					$col = 'E';
					break;
				case '4':
					$col = 'F';
					break;
				
				case '5':
					$col = 'G';
					break;
				
				case '6':
					$col = 'H';
					break;
				
				case '7':
					$col = 'I';
					break;
				
				case '8':
					$col = 'J';
					break;
				
				case '9':
					$col = 'K';
					break;
				
				case '10':
					$col = 'L';
					break;
				
				case '11':
					$col = 'M';
					break;
				
				case '12':
					$col = 'N';
					break;
				
				case '13':
					$col = 'O';
					break;
				
				case '14':
					$col = 'P';
					break;
				
				case '15':
					$col = 'Q';
					break;
				
				case '16':
					$col = 'R';
					break;
				
				case '17':
					$col = 'S';
					break;
				
				case '18':
					$col = 'T';
					break;
				case '19':
					$col = 'U';
					break;
				
				case '20':
					$col = 'V';
					break;
				
				case '21':
					$col = 'W';
					break;
				
				case '22':
					$col = 'X';
					break;
				
				case '23':
					$col = 'Y';
					break;
				
				case '24':
					$col = 'Z';
					break;
			}

			return $col ;
		}
   
?>
