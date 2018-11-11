<?php

/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license. For more information, see
 * <http://www.doctrine-project.org>.
 */
namespace Doctrine\ORM\Query;

/**
 * Represents a chain of tree walkers that modify an AST and finally emit output.
 * Only the last walker in the chain can emit output. Any previous walkers can modify
 * the AST to influence the final output produced by the last walker.
 *
 * @author Roman Borschel <roman@code-factory.org>
 * @since 2.0
 */
class TreeWalkerChain implements TreeWalker {
	/**
	 * The tree walkers.
	 *
	 * @var TreeWalker[]
	 */
	private $_walkers = array ();
	
	/**
	 * The original Query.
	 *
	 * @var \Doctrine\ORM\AbstractQuery
	 */
	private $_query;
	
	/**
	 * The ParserResult of the original query that was produced by the Parser.
	 *
	 * @var \Doctrine\ORM\Query\ParserResult
	 */
	private $_parserResult;
	
	/**
	 * The query components of the original query (the "symbol table") that was produced by the Parser.
	 *
	 * @var array
	 */
	private $_queryComponents;
	
	/**
	 * Returns the internal queryComponents array.
	 *
	 * @return array
	 */
	public function getQueryComponents() {
		return $this->_queryComponents;
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function setQueryComponent($dqlAlias, array $queryComponent) {
		$requiredKeys = array (
				'metadata',
				'parent',
				'relation',
				'map',
				'nestingLevel',
				'token' 
		);
		
		if (array_diff ( $requiredKeys, array_keys ( $queryComponent ) )) {
			throw QueryException::invalidQueryComponent ( $dqlAlias );
		}
		
		$this->_queryComponents [$dqlAlias] = $queryComponent;
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function __construct($query, $parserResult, array $queryComponents) {
		$this->_query = $query;
		$this->_parserResult = $parserResult;
		$this->_queryComponents = $queryComponents;
	}
	
	/**
	 * Adds a tree walker to the chain.
	 *
	 * @param string $walkerClass
	 *        	The class of the walker to instantiate.
	 *        	
	 * @return void
	 */
	public function addTreeWalker($walkerClass) {
		$this->_walkers [] = new $walkerClass ( $this->_query, $this->_parserResult, $this->_queryComponents );
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function walkSelectStatement(AST\SelectStatement $AST) {
		foreach ( $this->_walkers as $walker ) {
			$walker->walkSelectStatement ( $AST );
			
			$this->_queryComponents = $walker->getQueryComponents ();
		}
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function walkSelectClause($selectClause) {
		foreach ( $this->_walkers as $walker ) {
			$walker->walkSelectClause ( $selectClause );
		}
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function walkFromClause($fromClause) {
		foreach ( $this->_walkers as $walker ) {
			$walker->walkFromClause ( $fromClause );
		}
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function walkFunction($function) {
		foreach ( $this->_walkers as $walker ) {
			$walker->walkFunction ( $function );
		}
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function walkOrderByClause($orderByClause) {
		foreach ( $this->_walkers as $walker ) {
			$walker->walkOrderByClause ( $orderByClause );
		}
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function walkOrderByItem($orderByItem) {
		foreach ( $this->_walkers as $walker ) {
			$walker->walkOrderByItem ( $orderByItem );
		}
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function walkHavingClause($havingClause) {
		foreach ( $this->_walkers as $walker ) {
			$walker->walkHavingClause ( $havingClause );
		}
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function walkJoin($join) {
		foreach ( $this->_walkers as $walker ) {
			$walker->walkJoin ( $join );
		}
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function walkSelectExpression($selectExpression) {
		foreach ( $this->_walkers as $walker ) {
			$walker->walkSelectExpression ( $selectExpression );
		}
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function walkQuantifiedExpression($qExpr) {
		foreach ( $this->_walkers as $walker ) {
			$walker->walkQuantifiedExpression ( $qExpr );
		}
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function walkSubselect($subselect) {
		foreach ( $this->_walkers as $walker ) {
			$walker->walkSubselect ( $subselect );
		}
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function walkSubselectFromClause($subselectFromClause) {
		foreach ( $this->_walkers as $walker ) {
			$walker->walkSubselectFromClause ( $subselectFromClause );
		}
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function walkSimpleSelectClause($simpleSelectClause) {
		foreach ( $this->_walkers as $walker ) {
			$walker->walkSimpleSelectClause ( $simpleSelectClause );
		}
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function walkSimpleSelectExpression($simpleSelectExpression) {
		foreach ( $this->_walkers as $walker ) {
			$walker->walkSimpleSelectExpression ( $simpleSelectExpression );
		}
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function walkAggregateExpression($aggExpression) {
		foreach ( $this->_walkers as $walker ) {
			$walker->walkAggregateExpression ( $aggExpression );
		}
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function walkGroupByClause($groupByClause) {
		foreach ( $this->_walkers as $walker ) {
			$walker->walkGroupByClause ( $groupByClause );
		}
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function walkGroupByItem($groupByItem) {
		foreach ( $this->_walkers as $walker ) {
			$walker->walkGroupByItem ( $groupByItem );
		}
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function walkUpdateStatement(AST\UpdateStatement $AST) {
		foreach ( $this->_walkers as $walker ) {
			$walker->walkUpdateStatement ( $AST );
		}
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function walkDeleteStatement(AST\DeleteStatement $AST) {
		foreach ( $this->_walkers as $walker ) {
			$walker->walkDeleteStatement ( $AST );
		}
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function walkDeleteClause(AST\DeleteClause $deleteClause) {
		foreach ( $this->_walkers as $walker ) {
			$walker->walkDeleteClause ( $deleteClause );
		}
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function walkUpdateClause($updateClause) {
		foreach ( $this->_walkers as $walker ) {
			$walker->walkUpdateClause ( $updateClause );
		}
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function walkUpdateItem($updateItem) {
		foreach ( $this->_walkers as $walker ) {
			$walker->walkUpdateItem ( $updateItem );
		}
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function walkWhereClause($whereClause) {
		foreach ( $this->_walkers as $walker ) {
			$walker->walkWhereClause ( $whereClause );
		}
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function walkConditionalExpression($condExpr) {
		foreach ( $this->_walkers as $walker ) {
			$walker->walkConditionalExpression ( $condExpr );
		}
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function walkConditionalTerm($condTerm) {
		foreach ( $this->_walkers as $walker ) {
			$walker->walkConditionalTerm ( $condTerm );
		}
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function walkConditionalFactor($factor) {
		foreach ( $this->_walkers as $walker ) {
			$walker->walkConditionalFactor ( $factor );
		}
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function walkConditionalPrimary($condPrimary) {
		foreach ( $this->_walkers as $walker ) {
			$walker->walkConditionalPrimary ( $condPrimary );
		}
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function walkExistsExpression($existsExpr) {
		foreach ( $this->_walkers as $walker ) {
			$walker->walkExistsExpression ( $existsExpr );
		}
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function walkCollectionMemberExpression($collMemberExpr) {
		foreach ( $this->_walkers as $walker ) {
			$walker->walkCollectionMemberExpression ( $collMemberExpr );
		}
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function walkEmptyCollectionComparisonExpression($emptyCollCompExpr) {
		foreach ( $this->_walkers as $walker ) {
			$walker->walkEmptyCollectionComparisonExpression ( $emptyCollCompExpr );
		}
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function walkNullComparisonExpression($nullCompExpr) {
		foreach ( $this->_walkers as $walker ) {
			$walker->walkNullComparisonExpression ( $nullCompExpr );
		}
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function walkInExpression($inExpr) {
		foreach ( $this->_walkers as $walker ) {
			$walker->walkInExpression ( $inExpr );
		}
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	function walkInstanceOfExpression($instanceOfExpr) {
		foreach ( $this->_walkers as $walker ) {
			$walker->walkInstanceOfExpression ( $instanceOfExpr );
		}
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function walkLiteral($literal) {
		foreach ( $this->_walkers as $walker ) {
			$walker->walkLiteral ( $literal );
		}
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function walkBetweenExpression($betweenExpr) {
		foreach ( $this->_walkers as $walker ) {
			$walker->walkBetweenExpression ( $betweenExpr );
		}
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function walkLikeExpression($likeExpr) {
		foreach ( $this->_walkers as $walker ) {
			$walker->walkLikeExpression ( $likeExpr );
		}
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function walkStateFieldPathExpression($stateFieldPathExpression) {
		foreach ( $this->_walkers as $walker ) {
			$walker->walkStateFieldPathExpression ( $stateFieldPathExpression );
		}
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function walkComparisonExpression($compExpr) {
		foreach ( $this->_walkers as $walker ) {
			$walker->walkComparisonExpression ( $compExpr );
		}
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function walkInputParameter($inputParam) {
		foreach ( $this->_walkers as $walker ) {
			$walker->walkInputParameter ( $inputParam );
		}
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function walkArithmeticExpression($arithmeticExpr) {
		foreach ( $this->_walkers as $walker ) {
			$walker->walkArithmeticExpression ( $arithmeticExpr );
		}
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function walkArithmeticTerm($term) {
		foreach ( $this->_walkers as $walker ) {
			$walker->walkArithmeticTerm ( $term );
		}
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function walkStringPrimary($stringPrimary) {
		foreach ( $this->_walkers as $walker ) {
			$walker->walkStringPrimary ( $stringPrimary );
		}
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function walkArithmeticFactor($factor) {
		foreach ( $this->_walkers as $walker ) {
			$walker->walkArithmeticFactor ( $factor );
		}
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function walkSimpleArithmeticExpression($simpleArithmeticExpr) {
		foreach ( $this->_walkers as $walker ) {
			$walker->walkSimpleArithmeticExpression ( $simpleArithmeticExpr );
		}
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function walkPathExpression($pathExpr) {
		foreach ( $this->_walkers as $walker ) {
			$walker->walkPathExpression ( $pathExpr );
		}
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function walkResultVariable($resultVariable) {
		foreach ( $this->_walkers as $walker ) {
			$walker->walkResultVariable ( $resultVariable );
		}
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function getExecutor($AST) {
	}
}
