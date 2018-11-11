<?php

/**
 * Classe responsável por conter os métodos
 * de consulta no banco
 * 
 * @author Diego Wojcik <diego@softwar.com.br>
 * @since 2016-08-17
 */
namespace Basico\Entity\Repository;

use Jlib\Util\FormatValue;
use Jlib\View\Html\Tabela\Tabela;
class ViewAgendaPessoalRepository extends AbstractEntityRepository
{
    /**
     * @see Basico\Entity\Repository.AbstractEntityRepository::dataToHtmlTable()
     */
	public function dataToHtmlTable ($data)
    {
        throw new \Exception('Método não implementado.');
    }
    /**
     * @see Basico\Entity\Repository.AbstractEntityRepository::dataToHtmlTable()
     */
    public function dataToHtmlTableAgendaGeral ($collectionData)
    {
    
    	$acesso = 'advocacia-agenda-todos';
    	$privilegio = 'Visualizar';
    	$acessoAgendaTodos = $this->validaAcesso($acesso, $privilegio, false);
    		
    	$data = array();
    	foreach ($collectionData as $dados){
    		//$data[] = $entity->toTable();
    		$formater = new FormatValue();
    		$dtAtual = new \DateTime();
    		$atrasada = false;
    		if($dados['DataAto'] < $dtAtual){
    			$atrasada = true;
    		}
    		$dados['DataAto'] 	    = $formater->formatDateToView($dados['DataAto']);
    		$dados['HoraAto'] 	     = $formater->formatHourToView($dados['HoraAto']);
    		$dados['DataResposta'] 	= $formater->formatDateTimeToView($dados['DataResposta']);
    		#$dados['DataTarefa'] 	= $formater->formatDateTimeToView($dados['DataTarefa']);
    		//$dados['PrazoAntecedenciaConclusaoAto'] = $formater->formatHourToView($dados['PrazoAntecedenciaConclusaoAto']);
    		if($dados['TipoPrazo'] == 'A'){
    			$dados['TipoPrazo'] = 'Antecedência';
    		}else{
    			$dados['TipoPrazo'] = 'Posterior';
    		}
    		if($dados['TipoPendencia'] == 'Pendência de Contratação' || $dados['TipoPendencia'] == 'Pendência Auditoria'){
    			if($atrasada){
    				$cor =  'red';
    			}else{
    				$cor =  'orange';
    			}
    
    
    			//if($cor = 'red';)
    		}else if($dados['TipoPendencia'] == 'Responder a Etapa'){
    			if($atrasada){
    				$cor =  'red';
    			}else{
    				$cor =  'green';
    			}
    		}
    			
    
    
    		$link = "/advocacia/processo/edit/{$dados['CodAdvocaciaProcesso']}";
    		$dados['link'] = "<a href='$link'>";
    		$dados['link'].=   $dados['CodProcesso'];
    		$dados['link'].= "</a>";
    		$tramitacao = $dados['CodAdvocaciaProcessoTramitacao'];
    		$dados['ancora'] = "<a href='$link' target='_blank' name='$tramitacao'>{$dados['CodProcesso']}";
    		#$dados['ancora'].=   "<font color=".$cor.">{$dados['CodProcesso']}</font>";
    		$dados['ancora'].= "</a>";
    		//CodAdvocaciaProcessoTramitacaoItens
    		if($dados['CodAdvocaciaTipoTramitacaoItens']){
    			$linkResposta = "/advocacia/tramitacao-itens/get-form-resposta-agenda/{$dados['CodAdvocaciaProcessoTramitacaoItens']}";
    			$dados['LinkResposta'] = "<a class='showform_respondeagenda' href='$linkResposta'>";
    			$dados['LinkResposta'].=  "<font color=".$cor.">{$dados['Descricao']}</font>";
    			$dados['LinkResposta'].= "</a>";
    
    		}else{
    			$dados['LinkResposta'] = '-';
    		}
    		// 			if(!$respondeTudo){
    			
    		// 				if($tarefaAnteriorObrigatoria){
    		// 					$state = 'disabled';
    		// 				}else{
    		// 					if(!empty($resultadoPauta)){
    		// 						$state = 'active';
    		// 					}else{
    		// 						$state = 'disabled';
    		// 					}
    		// 				}
    			
    		// 			}else{
    		// 				$state = 'active';
    		// 			}
    
    		$data[] = $dados;
    	}
    
    	//Tabela
    	$tab = new Tabela($data);
    	//$tab->addCampo('link', 'Processo');
    	$tab->addCampo('ancora', 'Processo');
    
    	$tab->addCampo('TipoPendencia', 'Pendência');
    	#$tab->addCampo('Descricao', 'Descrição');
    	$tab->addCampo('LinkResposta', 'Descrição');
    	$tab->addCampo('DataAto', 'Data Ato');
    	$tab->addCampo('HoraAto', 'Hora');
    	#$tab->addCampo('DataTarefa', 'Data Hora Limite');
    	$tab->addCampo('PrazoAntecedenciaConclusaoAto', 'Prazo');
    	$tab->addCampo('TipoPrazo', 'Tipo Prazo');
    	$tab->addCampo('Pauta', 'Pauta');
    	$tab->setHtmlId('adv-agenda-list');
    
    	return $tab;
    }
    public function dataToHtmlTableAtividades ($collectionData)
    {
    	$data = array();
    	foreach ($collectionData as $dados){
    		//$data[] = $entity->toTable();
    		$formater = new FormatValue();
    		$dtAtual = new \DateTime();
    		$atrasada = false;
    		if($dados['DataHoraAgenda'] < $dtAtual){
    			$atrasada = true;
    		}	
    		if($atrasada){
    			$cor =  'red';
    		}else{
    			$cor =  'orange';
    		}
    		$dados['DataAgenda'] 	= $formater->formatDateToView($dados['DataHoraAgenda']);
    		$dados['HoraAgenda'] 	= $formater->formatHourToView($dados['DataHoraAgenda']);
    		//$dados['DataResposta'] 	= $formater->formatDateTimeToView($dados['DataResposta']);
    		#$dados['DataTarefa'] 	= $formater->formatDateTimeToView($dados['DataTarefa']);
    		//$dados['PrazoAntecedenciaConclusaoAto'] = $formater->formatHourToView($dados['PrazoAntecedenciaConclusaoAto']);
    		if($dados['Status'] == 'C'){
    			$dados['Status'] = 'Concluído';
    		}elseif($dados['Status'] == 'A'){
    			$dados['Status'] = 'Em Andamento';
    		}else{
    			$dados['Status'] = 'Pendente';
    		}
    		if($dados['Responsavel'] == 'S'){
    			$dados['Responsavel'] = 'Sim';
    		}else{
    			$dados['Responsavel'] = 'Não';
    		}
    			
    		//$link = "/advocacia/processo/edit/{$dados['CodAdvocaciaProcesso']}";
    		/*$ancora = '#'.$dados['CodAdvocaciaProcessoTramitacao'];
    		$dados['link'] = "<a href='$ancora'>";
    		$dados['link'].=   $dados['CodProcesso'];
    		$dados['link'].= "</a>";*/
    		
    		$link = "/basico/agenda/edit/{$dados['CodAgenda']}";
    		$dados['link'] = "<a href='$link' target='_blank'>"; //{$dados['TipoAgenda']}
    		$dados['link'].=   "<font color=".$cor.">{$dados['TipoAgenda']}</font>";
    		$dados['link'].= "</a>";
    		$data[] = $dados;
    	}
    
    	//Tabela
    	$tab = new Tabela($data);
    	$tab->addCampo('link', 'Tipo');
    	$tab->addCampo('Descricao', 'Descrição');
    	$tab->addCampo('Status', 'Status');
    	$tab->addCampo('DataAgenda', 'Data');
    	$tab->addCampo('HoraAgenda', 'Hora');
    	$tab->addCampo('Nome', 'Pessoa');
    	$tab->addCampo('Responsavel', 'Responsável');
    	$tab->setAllowEdit(true);
    	$tab->setEditLink('/basico/agenda/edit');
    	$tab->addEditParam('CodAgenda', false);
    	if (!$acessoPermitido){
    		$tab->setAllowDelete(false);
    	}else{
    		$tab->setAllowDelete(true);
    	}
    	$tab->setDeleteLink('/basico/agenda/delete');
    	$tab->setDeleteHrefClass('btnExcluir');
    	$tab->addDeleteParam('CodAgenda', false);
    	$tab->setHtmlId('adv-agenda-list-tarefa');
    
    	return $tab;
    }
    /**
     * Retorna um array das contas a pagar dentro de um intervalo de datas
     *
     * @param \DateTime $dtIni
     * @param \DateTime $dtFim
     */
    public function findPesquisar(\DateTime $dtIni = null, \DateTime $dtFim = null, $where = null, $codigoUsuarioLogado = null){
    	//v.Prazo,v.DataConclusao,v.Prioridade
    	$qb = $this->getEntityManager()->createQueryBuilder();
    	$qb->select('v.CodAgenda, v.CodUsuario,v.Prazo,v.DataConclusao,v.Prioridade, v.Nome, v.Responsavel, v.DataCriacao, v.DataHoraAgenda, v.DataHoraAviso,
    			 v.CodTipoAgenda, v.TipoAgenda, v.Status, v.Descricao')
    			->from('Basico\Entity\ViewAgendaPessoal', 'v');
    	if(!empty($dtIni) && !empty($dtFim)){
    		$qb->where('v.DataHoraAgenda BETWEEN :DtIni AND :DtFim')
    		->orWhere('v.DataHoraAgenda < :DtIni')
    		->andwhere('v.Status <> :Status');
    		
    		if($codigoUsuarioLogado){
    			$qb->andwhere('v.CodUsuario = :User')
    			->setParameter('User', $codigoUsuarioLogado);
    		}
    			
    		$qb->setParameter('Status', 'C')
    		->setParameter('DtIni', $dtIni->format('Y-m-d').' 00:00:01.000')
    		->setParameter('DtFim', $dtFim->format('Y-m-d').' 23:59:59.000')
    		->orderBy('v.DataHoraAgenda','ASC');
    	}elseif ($dtIni){
    		$qb->where('v.DataHoraAgenda BETWEEN :DtIni AND :DtFim')
    		->orWhere('v.DataHoraAgenda < :DtIni')
    		
			->andwhere('v.Status <> :Status');
			if($codigoUsuarioLogado){
    			$qb->andwhere('v.CodUsuario = :User')
    			->setParameter('User', $codigoUsuarioLogado);
    		}
			
    		$qb->setParameter('Status', 'C')
    		->setParameter('DtIni', $dtIni->format('Y-m-d').' 00:00:01.000')
    		->setParameter('DtFim', $dtFim->format('Y-m-d').' 23:59:59.000')
    		->orderBy('v.DataHoraAgenda','ASC');
    	}else{
    		$qb->where('1=1')
    		->andwhere('v.Status <> :Status');
    		if($codigoUsuarioLogado){
    			$qb->andwhere('v.CodUsuario = :User')
    			->setParameter('User', $codigoUsuarioLogado);
    		}
    	//	->andwhere('v.Status <> "C"')
    		$qb->setParameter('Status', 'C')
    		//->setParameter('User', $codigoUsuarioLogado)
    		->orderBy('v.DataHoraAgenda','ASC');
    	}
    
    
    
    	//-----------
    	// Where
    	//-----------

        if (!empty($where['Prazo'])){
            $dataPra = explode('/', $where['Prazo']);
            $dataP = $dataPra[2] . '-' . $dataPra[1]. '-' . $dataPra[0];

            $qb->andwhere('v.Prazo = :Prazo')
                ->setParameter('Prazo',$dataP);
        }
        if (!empty($where['DataConclusao'])){
            $dataCon = explode('/', $where['DataConclusao']);
            $dataC = $dataCon[2] . '-' . $dataCon[1]. '-' . $dataCon[0];

            $qb->andwhere('v.DataConclusao = :DataConclusao')
                ->setParameter('DataConclusao',$dataC);
        }
        if (!empty($where['Descricao'])){
            $where['Descricao'] = '%'.$where['Descricao'].'%';

            $qb->andwhere('v.Descricao Like :Descricao')
                ->setParameter('Descricao',$where['Descricao']);
        }
        if ($where['Responsavel'] == 'S'){
            $qb->andwhere('v.Responsavel IS not NULL');
        }
        if ($where['Responsavel'] == 'N'){
            $qb->andwhere('v.Responsavel IS NULL');
        }
        if (!empty($where['Prioridade'])){

            $qb->andwhere('v.Prioridade = :Prioridade')
                ->setParameter('Prioridade',$where['Prioridade']);
        }
        if (!empty($where['HoraAgenda'])){
            $where['HoraAgenda'] = $where['HoraAgenda'].':00';
            $where['HoraAgenda'] = '1900-01-01 '.$where['HoraAgenda'];

            $qb->andwhere('v.HoraAgenda = :HoraAgenda')
                ->setParameter('HoraAgenda',$where['HoraAgenda']);
        }
            //$qb->orderBy('v.DataCriacao','DESC');



        #CodCentroCust
    	/*if (!empty($where['ResponsavelAto'])){
    		$qb->andWhere('v.ResponsavelAto = :ResponsavelAto')
    		->setParameter('ResponsavelAto', $where['ResponsavelAto']);
    	}
    
    	if (!empty($where['CodAdvocaciaPauta'])){
    		$qb->andWhere('v.CodAdvocaciaPauta = :CodAdvocaciaPauta')
    		->setParameter('CodAdvocaciaPauta', $where['CodAdvocaciaPauta']);
    	}
    
    	if (!empty($where['CodAdvocaciaGrupoTrabalho'])){
    		$qb->andWhere('v.CodAdvocaciaGrupoTrabalho = :CodAdvocaciaGrupoTrabalho')
    		->setParameter('CodAdvocaciaGrupoTrabalho', $where['CodAdvocaciaGrupoTrabalho']);
    	}
    	if (!empty($where['CodAdvocaciaComarca'])){
    		$qb->andWhere('v.CodAdvocaciaComarca = :CodAdvocaciaComarca')
    		->setParameter('CodAdvocaciaComarca', $where['CodAdvocaciaComarca']);
    	}
    	if (!empty($where['CodAdvocaciaLocalAto'])){
    		$qb->andWhere('v.CodAdvocaciaLocalAto = :CodAdvocaciaLocalAto')
    		->setParameter('CodAdvocaciaLocalAto', $where['CodAdvocaciaLocalAto']);
    	}
    */
    
    	    	return $qb->getQuery()->getResult();
    }
    
    /**
     * Retorna um array das contas a pagar a partir da data atual
     *
     */
    public function findAgendaFromToday($where = null, $pauta = null){
    	$dtIni = new \DateTime();
    
    	// 		$qb = $this->getEntityManager()->createQueryBuilder();
    	// 		$qb->select('v')
    	// 		->from('Advocacia\Entity\ViewAdvocaciaAgenda', 'v')
    	// 		//->where('v.DataPago IS NULL')
    	// 		->where('v.DataAto >= :DtIni')
    	// 		->andwhere('v.DataResposta IS NULL')
    	// 		->setParameter('DtIni', $dtIni->format('Y-m-d').' 00:00:00.000')
    	// 		->orderBy('v.DataAto','ASC');
    
    	$qb = $this->getEntityManager()->createQueryBuilder();
    	$qb->select('v.CodAgenda, v.CodUsuario, v.Nome, v.Responsavel, v.DataCriacao, v.DataHoraAgenda, v.DataHoraAviso,
    			 v.CodTipoAgenda, v.TipoAgenda, v.Status, v.Descricao')
    						->from('Basico\Entity\ViewAgendaPessoal', 'v')
    						->where('v.DataHoraAgenda >= :DtIni')
    						//->orWhere('v.DataAto < :DtIni')
    			->andwhere('v.TipoPendencia <> :Pendencia');
    	if(isset($pauta) && !empty($pauta)){
    		$qb->andWhere("v.CodAdvocaciaPauta IN ($pauta)");
    		//->setParameter('Pauta', $pauta);
    	}
    	$qb->setParameter('Pendencia', '-')
    
    	//->andwhere('v.DataResposta IS NULL')
    	->setParameter('DtIni', $dtIni->format('Y-m-d').' 00:00:01.000')
    	//->setParameter('DtFim', $dtFim->format('Y-m-d').' 00:00:00.000')
    	->orderBy('v.DataAto, v.Ordem ','ASC');
    
    	//-----------
    	// Where
    	//-----------
    	#CodCentroCusto
//     	if (!empty($where['ResponsavelAto'])){
//     		$qb->andWhere('v.ResponsavelAto = :ResponsavelAto')
//     		->setParameter('ResponsavelAto', $where['ResponsavelAto']);
//     	}
    
//     	if (!empty($where['CodAdvocaciaPauta'])){
//     		$qb->andWhere('v.CodAdvocaciaPauta = :CodAdvocaciaPauta')
//     		->setParameter('CodAdvocaciaPauta', $where['CodAdvocaciaPauta']);
//     	}
    
//     	if (!empty($where['CodAdvocaciaGrupoTrabalho'])){
//     		$qb->andWhere('v.CodAdvocaciaGrupoTrabalho = :CodAdvocaciaGrupoTrabalho')
//     		->setParameter('CodAdvocaciaGrupoTrabalho', $where['CodAdvocaciaGrupoTrabalho']);
//     	}
//     	if (!empty($where['CodAdvocaciaComarca'])){
//     		$qb->andWhere('v.CodAdvocaciaComarca = :CodAdvocaciaComarca')
//     		->setParameter('CodAdvocaciaComarca', $where['CodAdvocaciaComarca']);
//     	}
//     	if (!empty($where['CodAdvocaciaLocalAto'])){
//     		$qb->andWhere('v.CodAdvocaciaLocalAto = :CodAdvocaciaLocalAto')
//     		->setParameter('CodAdvocaciaLocalAto', $where['CodAdvocaciaLocalAto']);
//     	}
    
    
    	return $qb->getQuery()->getResult();
    }
    /**
     * Retorna a quantidade de tarefas não cumpridas de um determinado usuário.
     * @author Diego Wojcik <diego@softwar.com.br>
     * @since 23/05/2017
     *
     * @param int $CodUsuario
     */
    public function findQtdTarefasAbertas($CodUsuario){
    	$qb = $this->getEntityManager()->createQueryBuilder();
    	$qb->select($qb->expr()->count('v.CodAgenda'))
    	->from('Basico\Entity\ViewAgendaPessoal', 'v')
    	->andwhere('v.CodUsuario = :CodUser')
    	->setParameter('CodUser', $CodUsuario);
    	$qb->andwhere('v.Status <> :Status');
    	$qb->setParameter('Status', 'C');
    	#$sql = $qb->getQuery()->getSQL();
    	
    	return $qb->getQuery()->getOneOrNullResult();
    }





}

?>