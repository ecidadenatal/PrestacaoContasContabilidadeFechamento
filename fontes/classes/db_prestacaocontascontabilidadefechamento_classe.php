<?php
/*
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

class cl_prestacaocontascontabilidadefechamento extends DAOBasica {

  public function __construct() {
    parent::__construct("plugins.prestacaocontascontabilidadefechamento");
  }

  /**
   * Retorna todos os empenhos para verificação do controle interno
   * @param string $sCampos
   * @param        $sOrder
   * @param        $sWhere
   * @return string
   */
  function sql_query_prestacaocontassaltes ($sCampos = '*', $sOrder, $sWhere) {

    $sSqlPrestaContas  = " select {$sCampos}";
    $sSqlPrestaContas .= " from plugins.prestacaocontascontabilidadefechamento ";
    $sSqlPrestaContas .= "  inner join saltes on k13_conta = reduzido";
    
    if (!empty($sWhere)) {
      $sSqlPrestaContas .= " where {$sWhere} ";
    }

    if (!empty($sOrder)) {
      $sSqlPrestaContas .= " order by {$sOrder}";
    }

    return $sSqlPrestaContas;
  }

}
