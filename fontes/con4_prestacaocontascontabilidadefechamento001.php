<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa e software livre; voce pode redistribui-lo e/ou     
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versao 2 da      
 *  Licenca como (a seu criterio) qualquer versao mais nova.          
 *                                                                    
 *  Este programa e distribuido na expectativa de ser util, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de              
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM           
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU     
 *  junto com este programa; se nao, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Copia da licenca no diretorio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta_plugin.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("classes/db_prestacaocontascontabilidadefechamento_classe.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("classes/db_conlancamval_classe.php"));
include(modification("classes/db_conlancam_classe.php"));
include(modification("classes/db_conlancamcompl_classe.php"));
include(modification("classes/db_conplano_classe.php"));
include(modification("libs/db_sql.php"));
include(modification("classes/db_conplanoreduz_classe.php"));

ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);

$clconplano     = new cl_conplano;
$clconlancamval = new cl_conlancamval;
$clconlancamcompl = new cl_conlancamcompl;
$clconlancam      = new cl_conlancam;
$clorcfontes      = new cl_orcfontes;
$clconplanoreduz  = new cl_conplanoreduz;

$clorcfontes->rotulo->label();

$clrotulo = new rotulocampo;
$clrotulo->label("c61_reduz");
$clrotulo->label("DBtxt22");
$clrotulo->label("DBtxt21");
$db_opcao = 1;
$db_botao = true;
$anousu = db_getsession("DB_anousu");

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >

<table width="790" align="center" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="center" valign="center" bgcolor="#CCCCCC"> 
    <center>
    <?
    	include(modification("forms/db_frmprestacaocontascontabilidadefechamento.php"));
    ?>
    </center>
	</td>
  </tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
