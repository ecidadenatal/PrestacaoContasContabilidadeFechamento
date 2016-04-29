<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/db_conecta_plugin.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/JSON.php");
require_once ("dbforms/db_funcoes.php");

$oJson              = new services_json();
$oParam             = $oJson->decode(str_replace("\\","",$_POST["json"]));

$oRetorno           = new stdClass();
$oRetorno->erro     = false;
$oRetorno->mensagem = '';

db_inicio_transacao();
try {

  switch ($oParam->exec) {

    case "salvarFechamento":

      $oDaoPrestacaoContasContabilidadeFechamento = new cl_prestacaocontascontabilidadefechamento();
      $oDaoPrestacaoContasContabilidadeFechamento->reduzido  = $oParam->reduzido;
      $oDaoPrestacaoContasContabilidadeFechamento->mes       = $oParam->mes;
      $oDaoPrestacaoContasContabilidadeFechamento->exercicio = $oParam->ano;
      $oDaoPrestacaoContasContabilidadeFechamento->status    = $oParam->statusFechamento;
      $oDaoPrestacaoContasContabilidadeFechamento->motivo    = $oParam->motivo;
      $oDaoPrestacaoContasContabilidadeFechamento->incluir(null);

      if ($oDaoPrestacaoContasContabilidadeFechamento->erro_status == 0) {
        throw new DBException("Erro ao fechar a prestaзгo de contas. ".$oDaoPrestacaoContasContabilidadeFechamento->erro_msg);
      }

      $oRetorno->mensagem = urlencode("Fechamento da Prestaзгo de Contas incluнdo com sucesso.");
      db_fim_transacao(false);
    break;

    case "getUltimoMesAno":

      $sWhere  = "reduzido = {$oParam->reduzido}";
      $sWhere .= " and exercicio = (select max(exercicio) 
                                    from plugins.prestacaocontascontabilidadefechamento 
                                    where reduzido = {$oParam->reduzido})";
      $sWhere .= " and status = 1";

      $oDaoPrestacaoContasContabilidadeFechamento = new cl_prestacaocontascontabilidadefechamento();
      $sSqlPrestacaoContasMes = $oDaoPrestacaoContasContabilidadeFechamento->sql_query_prestacaocontassaltes("coalesce(max(mes), 0) as mes, coalesce(max(exercicio), 0) as ano", "", $sWhere);
      $rsPrestacaoContasMes   = $oDaoPrestacaoContasContabilidadeFechamento->sql_record($sSqlPrestacaoContasMes);
        
      $oDadosPrestacaoContasMes = db_utils::fieldsMemory($rsPrestacaoContasMes, 0);
      
      if ($oDadosPrestacaoContasMes->ano != 0 && $oDadosPrestacaoContasMes->mes != 0) {

        $oRetorno->ano = $oDadosPrestacaoContasMes->ano;
        $oRetorno->mes = $oDadosPrestacaoContasMes->mes;
      } else {
        
        $oRetorno->erro = true;
      }
      db_fim_transacao(false);
    break;

    default:

      $oRetorno->erro = true;
      $oRetorno->mensagem = urlencode("Opзгo de operaзгo nгo definida.");
      db_fim_transacao(false);
    break;
  }

} catch (Exception $eErro) {

  db_fim_transacao(true);
  $oRetorno->erro       = true;
  $oRetorno->mensagem   = $eErro->getMessage();
}

echo $oJson->encode($oRetorno);

?>