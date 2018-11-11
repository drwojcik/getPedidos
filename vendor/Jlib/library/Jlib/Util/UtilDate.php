<?php

namespace Jlib\Util;

class UtilDate {
	
	/**
	 * Retorna o nome do mês.
	 *
	 * @param int $monthNumber
	 *        	(Número do mês)
	 * @param boolean $short
	 *        	(Retornar abreviado?) (default false)
	 * @return string
	 * @throws \Exception
	 */
	public function getMonthNameByNum($monthNumber, $short = null) {
		if (empty ( $short ) || $short === false) {
			switch ($monthNumber) {
				case 1 :
					$mes = 'Janeiro';
					break;
				case 2 :
					$mes = 'Fevereiro';
					break;
				case 3 :
					$mes = 'Março';
					break;
				case 4 :
					$mes = 'Abril';
					break;
				case 5 :
					$mes = 'Maio';
					break;
				case 6 :
					$mes = 'Junho';
					break;
				case 7 :
					$mes = 'Julho';
					break;
				case 8 :
					$mes = 'Agosto';
					break;
				case 9 :
					$mes = 'Setembro';
					break;
				case 10 :
					$mes = 'Outubro';
					break;
				case 11 :
					$mes = 'Novembro';
					break;
				case 12 :
					$mes = 'Dezembro';
					break;
				default :
					throw new \Exception ( 'O número do mês deve estar entre 1 e 12.' );
					break;
			}
		} else {
			switch ($monthNumber) {
				case 1 :
					$mes = 'Jan';
					break;
				case 2 :
					$mes = 'Fev';
					break;
				case 3 :
					$mes = 'Mar';
					break;
				case 4 :
					$mes = 'Abr';
					break;
				case 5 :
					$mes = 'Mai';
					break;
				case 6 :
					$mes = 'Jun';
					break;
				case 7 :
					$mes = 'Jul';
					break;
				case 8 :
					$mes = 'Ago';
					break;
				case 9 :
					$mes = 'Set';
					break;
				case 10 :
					$mes = 'Out';
					break;
				case 11 :
					$mes = 'Nov';
					break;
				case 12 :
					$mes = 'Dez';
					break;
				default :
					throw new \Exception ( 'O número do mês deve estar entre 1 e 12.' );
					break;
			}
		}
		
		return $mes;
	}
	
	/**
	 * Retorna o nome do dia da semana.
	 *
	 * @param int $dayNumber
	 *        	(Número do dia)
	 * @param boolean $short
	 *        	(Retornar abreviado?) (default false)
	 * @return string
	 * @throws \Exception
	 */
	public function getDayNameByNum(int $dayNumber, $short = null) {
		if (empty ( $short ) || $short === false) {
			switch ($dayNumber) {
				case 1 :
					$day = 'Segunda-Feira';
					break;
				case 2 :
					$day = 'Terça-Feira';
					break;
				case 3 :
					$day = 'Quarta-Feira';
					break;
				case 4 :
					$day = 'Quinta-Feira';
					break;
				case 5 :
					$day = 'Sexta-Feira';
					break;
				case 6 :
					$day = 'Sábado';
					break;
				case 7 :
					$day = 'Domingo';
					break;
				default :
					throw new \Exception ( 'O número do dia deve estar entre 1 e 7.' );
					break;
			}
		} else {
			switch ($dayNumber) {
				case 1 :
					$day = 'Seg';
					break;
				case 2 :
					$day = 'Ter';
					break;
				case 3 :
					$day = 'Qua';
					break;
				case 4 :
					$day = 'Qui';
					break;
				case 5 :
					$day = 'Sex';
					break;
				case 6 :
					$day = 'Sáb';
					break;
				case 7 :
					$day = 'Dom';
					break;
				default :
					throw new \Exception ( 'O número do dia deve estar entre 1 e 7.' );
					break;
			}
		}
		
		return $day;
	}
	
	/**
	 * Traduz o nome do dia da semana.
	 *
	 * @param string $dayName
	 *        	(dia da semana em inglês)
	 * @return string
	 * @throws \Exception
	 */
	public function translateDayOfWeek($dayName) {
		switch ($dayName) {
			case 'Mon' :
				$day = 'Seg';
				break;
			case 'Tue' :
				$day = 'Ter';
				break;
			case 'Wed' :
				$day = 'Qua';
				break;
			case 'Thu' :
				$day = 'Qui';
				break;
			case 'Fri' :
				$day = 'Sex';
				break;
			case 'Sat' :
				$day = 'Sáb';
				break;
			case 'Sun' :
				$day = 'Dom';
				break;
			
			case 'Monday' :
				$day = 'Segunda-Feira';
				break;
			case 'Tuesday' :
				$day = 'Terça-Feira';
				break;
			case 'Wednesday' :
				$day = 'Quarta-Feira';
				break;
			case 'Thursday' :
				$day = 'Quinta-Feira';
				break;
			case 'Friday' :
				$day = 'Sexta-Feira';
				break;
			case 'Saturday' :
				$day = 'Sábado';
				break;
			case 'Sunday' :
				$day = 'Domingo';
				break;
			default :
				throw new \Exception ( "Falha ao traduzir o dia '{$dayName}'" );
				break;
		}
		
		return $day;
	}
	
	/**
	 * Traduz o nome do mês.
	 *
	 * @param string $monthName
	 *        	(Nome do mês em inglês)
	 * @return string
	 * @throws \Exception
	 */
	public function translateMonth($monthName) {
		switch ($monthName) {
			case 'Jan' :
				$month = 'Janeiro';
				break;
			case 'Feb' :
				$month = 'Fevereiro';
				break;
			case 'Mar' :
				$month = 'Março';
				break;
			case 'Apr' :
				$month = 'Abril';
				break;
			case 'May' :
				$month = 'Maio';
				break;
			case 'Jun' :
				$month = 'Junho';
				break;
			case 'Jul' :
				$month = 'Julho';
				break;
			case 'Aug' :
				$month = 'Agosto';
				break;
			case 'Sep' :
				$month = 'Setembro';
				break;
			case 'Oct' :
				$month = 'Outubro';
				break;
			case 'Nov' :
				$month = 'Novembro';
				break;
			case 'Dec' :
				$month = 'Dezembro';
				break;
			
			case 'January' :
				$month = 'Janeiro';
				break;
			case 'February' :
				$month = 'Fevereiro';
				break;
			case 'March' :
				$month = 'Março';
				break;
			case 'April' :
				$month = 'Abril';
				break;
			case 'May' :
				$month = 'Maio';
				break;
			case 'June' :
				$month = 'Junho';
				break;
			case 'July' :
				$month = 'Julho';
				break;
			case 'August' :
				$month = 'Agosto';
				break;
			case 'September' :
				$month = 'Setembro';
				break;
			case 'October' :
				$month = 'Outubro';
				break;
			case 'November' :
				$month = 'Novembro';
				break;
			case 'December' :
				$month = 'Dezembro';
				break;
			default :
				throw new \Exception ( "Falha ao traduzir o mês '{$monthName}'" );
				break;
		}
		
		return $month;
	}
}