<?php
namespace Basico\PluginController;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class ParamCores extends AbstractPlugin {
    
    /**
     * @var EntityManager
     */
    protected $_em;
    
	public function __invoke(){
	    if ($this->_em == null)
	        $this->_em = $this->getController()->getServiceLocator()->get('Doctrine\ORM\EntityManager');
	    return $this;
	}

    /**
     * Subtrai a $dataPrazo da $dataComparar e retorna o hexadecimal da cor
     * referente ao resultado da subtração das datas.    
     * Ex.: (06/12/2013 - 04/12/2013) = 2 busca no BD a cor referente ao prazo de 2 dias
     * Tendo como comparação o tipo do parâmetro de cores. Ex: Tarefas, Financeiro, etc.
     * 
     * @param string $tipoParametro
     * @param Datetime $dataPrazo
     * @param Datetime $dataComparar (ex.: data atual)
     * @return string (hexadecimal da cor)
     * @throws \Exception
     */
    public function corPrazoDatas($tipoParametro, $dataPrazo, $dataComparar){
        if ( !($dataPrazo instanceof \Datetime) || !($dataComparar instanceof \Datetime) ){
            throw new \Exception("As datas precisam ser um objeto Datetime.");
        } else {
            $dataPrazo->format('Y-m-d');
            $dataComparar->format('Y-m-d');            
            $dias = $this->getPeriodOfDaysFromDatediff($dataComparar, $dataPrazo, array(6,7));
            
            $repCoresPrazoDatas = $this->_em->getRepository('Basico\Entity\ParamCoresPrazoDatas');
            $entCoresPrazoDatas = $repCoresPrazoDatas->findByTipoParametroAndPrazo($tipoParametro, $dias);
            if ($entCoresPrazoDatas){
                return $entCoresPrazoDatas->getCorHex();
            } else {
                return '';
            }
        }
    }
    
    public function getLegendaCorPrazoDatas($tipoParametro){
        $repCores = $this->_em->getRepository('Basico\Entity\ParamCores');
        $entCores = $repCores->findOneByDescricao($tipoParametro);
        if ($entCores){
            $CoresPrazoDatas = $entCores->getCoresPrazoDatas();
            if ($CoresPrazoDatas){
                $data = array();
                foreach ($CoresPrazoDatas as $param){
                    $data[] = $param->toArray();
                }
                return $data;
            } else {
                return false;
            }            
        } else {
            return false;
        }
    }
    
    /**
     * Retorna o número de dias valídos da diferença entre duas datas.
     * Considera dias válidos os dias da semana que não estão presentes no array $excludeDaysOfWeek.
     * 
     * @param Datetime $dateStart
     * @param Datetime $dateEnd
     * @param array $excludeDaysOfWeek (Ex.: array(6,7) exclui sabado e domingo)
     * @return int
     * @throws \Exception
     */
    public function getPeriodOfDaysFromDatediff($dateStart, $dateEnd, $excludeDaysOfWeek){
        if ( !($dateEnd instanceof \Datetime) || !($dateStart instanceof \Datetime) ){
            throw new \Exception("As datas precisam ser um objeto Datetime.");
        } else {
            $diff = $dateStart->diff($dateEnd);
            $dias = (int) $diff->format('%R%a');
            
            //verificação para contar apenas os dias válidos (dias úteis)
            $interval = \DateInterval::createFromDateString('1 day');
            if ($dateStart < $dateEnd)
                $period = new \DatePeriod($dateStart, $interval, $dateEnd);
            else
                $period = new \DatePeriod($dateEnd, $interval, $dateStart);
            $validDays = 0;
            foreach ($period as $dt){
                if (!in_array($dt->format('N'), $excludeDaysOfWeek)){
                    $validDays++;
                }
            }
            if ($dias < 0)
                $dias = $validDays * -1;
            else
                $dias = $validDays;
            
            return $dias;
        }
    }
}

?>