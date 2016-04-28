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

//MODULO: caixa

?>
<form name="form1" method="post" action="">
<center>
<fieldset>
<legend>Contabilidade - Fechamento da Prestação de Contas</legend>
<table border="0">
  <tr>
    <td>
      <? db_ancora("$Lc61_reduz",'js_pesquisa();',1); ?>
    </td>
    <td>
      <? db_input('c61_reduz',8,"",true,'text',1);  ?>
      <? db_input('c60_descr' ,50,"",true,'text',3);  ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="Mes/Ano">
      <strong>Mês/Ano</strong>
    </td>
    <td> 
      <?
        db_select("mes", array_combine(range(1,12), range(1,12)), true, 1, "style='width: 50px;'");

        $ano_atual = db_getsession("DB_anousu");
        $ano_inicio = $ano_atual - 1;
        db_select("ano", array_combine(range($ano_inicio, $ano_atual), range($ano_inicio, $ano_atual)), true, 1, "style='width: 80px;'"); 
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="Status">
      <strong>Status</strong>
    </td>
    <td> 
      <?
        $x = array("0"=>"Selecione","1"=>"Prestado Contas","2"=>"Em diligência");
	      db_select('status',$x,true, 1, "onchange=js_status();");
      ?>
    </td>
  </tr>
  <tr id='trMotivo' >
    <td nowrap title="Motivo">
      <strong>Motivo</strong>
    </td>
    <td> 
      <?
        $motivo = "";
        db_textarea('motivo',6,70,$motivo,true,'text',5,"");
      ?>
    </td>
  </tr>
</table>
</fieldset>
</center>
<input name="db_opcao" type="button" id="db_opcao" value="Incluir" onclick="js_salvarFechamento();" <?=($db_botao==false?"disabled":"")?> >
<!--<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisaPrestacao();" >-->
</form>
<script>

function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_conplanoreduz','func_conplanoreduz.php?funcao_js=parent.js_preenchepesquisa|c61_reduz|c60_descr','Pesquisa',true);
}
function js_preenchepesquisa(chave1,chave2){

  db_iframe_conplanoreduz.hide();
  document.form1.c61_reduz.value=chave1;
  document.form1.c60_descr.value=chave2;
  js_verificaMes();
}

function js_status(){
  //$('trMotivo').style.display = $('status').value == 2 ? 'table-row' : 'none';
  $('motivo').disabled = $('status').value == 2 ? false : true;
}

function js_salvarFechamento() {

  var reduzido = $('c61_reduz').value;
  var status   = $('status').value;
  var mes      = $('mes').value;
  var ano      = $('ano').value;
  var motivo   = null;

  if (status == 2) {

    if ($('motivo').value == ""){
      alert("Quando o status for 'Em diligência', o motivo deve ser preenchido.");
      return false;
    }

    motivo = $('motivo').value;
  } else if (status == 0) {

    alert("O status do fechamento deve ser selecionado.");
    js_status();
    return false;
  }

  js_divCarregando("Aguarde, salvando fechamento","msgBox");

  var oParam = new Object();
  oParam.exec     = 'salvarFechamento';
  oParam.reduzido = reduzido;
  oParam.statusFechamento = status;
  oParam.mes      = mes;
  oParam.ano      = ano;
  oParam.motivo   = motivo;

  var oAjax = new Ajax.Request('con4_prestacaocontascontabilidadefechamento001.RPC.php',
      {method: 'post',
        parameters: 'json='+Object.toJSON(oParam),
        onComplete: js_completaFechamento
      });
}

function js_completaFechamento(oAjax) {
  
  js_removeObj("msgBox");
  var obj = eval("("+oAjax.responseText+")");
  
  alert(obj.mensagem.urlDecode());

  if (obj.erro == true) {

    return false;
  }
  
  limpaTela();
  return true;
}

function js_verificaMes() {

  var reduzido = $('c61_reduz').value;
  
  js_divCarregando("Aguarde, consultando fechamentos","msgBox");

  var oParam = new Object();
  oParam.exec     = 'getUltimoMesAno';
  oParam.reduzido = reduzido;  

  var oAjax = new Ajax.Request('con4_prestacaocontascontabilidadefechamento001.RPC.php',
      {method: 'post',
        parameters: 'json='+Object.toJSON(oParam),
        onComplete: js_completaMes
      });
}

function js_completaMes(oAjax) {

  js_removeObj("msgBox");
  var obj = eval("("+oAjax.responseText+")");

  if (obj.erro == false) {

    var mes = parseInt(obj.mes)+1;
    var ano = parseInt(obj.ano);

    //caso o último mês tenha sido dezembro
    if (mes == 13) {
      mes = 1;
      ano++;
    }

    $('mes').value    = mes;
    $('mes').disabled = true;
    
    $('ano').value    = ano;
    $('ano').disabled = true;
    return true;
  }

  $('mes').disabled = false;
  $('ano').disabled = false;
  return false;
}

function limpaTela(){
  
  $('c61_reduz').value  = '';
  $('c60_descr').value  = '';
  $('status').value = 0;
  $('motivo').value = "";
  $('mes').value = 1;

  $('mes').disabled = false;
  $('ano').disabled = false;
}

</script>