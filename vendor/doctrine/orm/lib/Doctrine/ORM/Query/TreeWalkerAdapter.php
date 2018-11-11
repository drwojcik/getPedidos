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
 * An adapter implementation of the TreeWalker interface.
 * The methods in this class
 * are empty. ﻿This class exists as convenience for creating tree walkers.
 *
 * @author Roman Borschel <roman@code-factory.org>
 * @since 2.0
 */
abstract class TreeWalkerAdapter implements TreeWalker {
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
	 *
	 * @ERROR!!!
	 *
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
	 * @return array
	 */
	protected function _getQueryComponents() {
		return $this->_queryComponents;
	}
	
	/**
	 * Retrieves the Query Instance responsible for the current walkers execution.
	 *
	 * @return \Doctrine\ORM\AbstractQuery
	 */
	protected function _getQuery() {
		return $this->_query;
	}
	
	/**
	 * Retrieves the ParserResult.
	 *
	 * @return \Doctrine\ORM\Query\ParserResult
	 */
	protected function _getParserResult() {
		return $this->_parserResult;
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function walkSelectStatement(AST\SelectStatement $AST) {
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function walkSelectClause($selectClause) {
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function walkFromClause($fromClause) {
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function walkFunction($function) {
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function walkOrderByClause($orderByClause) {
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function walkOrderByItem($orderByItem) {
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function walkHavingClause($havingClause) {
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function walkJoin($join) {
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function walkSelectExpression($selectExpression) {
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function walkQuantifiedExpression($qExpr) {
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function walkSubselect($subselect) {
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function walkSubselectFromClause($subselectFromClause) {
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function walkSimpleSelectClause($simpleSelectClause) {
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function walkSimpleSelectExpression($simpleSelectExpression) {
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function walkAggregateExpression($aggExpression) {
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function walkGroupByClause($groupByClause) {
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function walkGroupByItem($groupByItem) {
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function walkUpdateStatement(AST\UpdateStatement $AST) {
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function walkDeleteStatement(AST\DeleteStatement $AST) {
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function walkDeleteClause(AST\DeleteClause $deleteClause) {
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function walkUpdateClause($updateClause) {
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function walkUpdateItem($updateItem) {
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function walkWhereClause($whereClause) {
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function walkConditionalExpression($condExpr) {
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function walkConditionalTerm($condTerm) {
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function walkConditionalFactor($factor) {
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function walkConditionalPrimary($primary) {
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function walkExistsExpression($existsExpr) {
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function walkCollectionMemberExpression($collMemberExpr) {
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function walkEmptyCollectionComparisonExpression($emptyCollCompExpr) {
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function walkNullComparisonExpression($nullCompExpr) {
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function walkInExpression($inExpr) {
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	function walkInstanceOfExpression($instanceOfExpr) {
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function walkLiteral($literal) {
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function walkBetweenExpression($betweenExpr) {
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function walkLikeExpression($likeExpr) {
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function walkStateFieldPathExpression($stateFieldPathExpression) {
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function walkComparisonExpression($compExpr) {
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function walkInputParameter($inputParam) {
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function walkArithmeticExpression($arithmeticExpr) {
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function walkArithmeticTerm($term) {
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function walkStringPrimary($stringPrimary) {
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function walkArithmeticFactor($factor) {
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function walkSimpleArithmeticExpression($simpleArithmeticExpr) {
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function walkPathExpression($pathExpr) {
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function walkResultVariable($resultVariable) {
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function getExecutor($AST) {
	}
}
