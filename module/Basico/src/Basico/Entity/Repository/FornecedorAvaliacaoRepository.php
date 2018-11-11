<?php
/**
 * Classe responsável por conter os métodos
 * de consulta no banco
 *
 * @author Diego Wojcik <diego@softwar.com.br>
 * @since 2016-01-06
 */
namespace Basico\Entity\Repository;

class FornecedorAvaliacaoRepository extends AbstractEntityRepository {

	/**
	 * @see Basico\Entity\Repository.AbstractEntityRepository::dataToHtmlTable()
	 */
	public function dataToHtmlTable ($data)
	{
		throw new \Exception('Método não implementado.');
	}
}