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
namespace Doctrine\ORM\Decorator;

use Doctrine\DBAL\LockMode;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Persistence\ObjectManagerDecorator;

/**
 * Base class for EntityManager decorators
 *
 * @since 2.4
 * @author Lars Strojny <lars@strojny.net
 */
abstract class EntityManagerDecorator extends ObjectManagerDecorator implements EntityManagerInterface {
	/**
	 *
	 * @var EntityManagerInterface
	 */
	protected $wrapped;
	
	/**
	 *
	 * @param EntityManagerInterface $wrapped        	
	 */
	public function __construct(EntityManagerInterface $wrapped) {
		$this->wrapped = $wrapped;
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function getConnection() {
		return $this->wrapped->getConnection ();
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function getExpressionBuilder() {
		return $this->wrapped->getExpressionBuilder ();
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function beginTransaction() {
		return $this->wrapped->beginTransaction ();
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function transactional($func) {
		return $this->wrapped->transactional ( $func );
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function commit() {
		return $this->wrapped->commit ();
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function rollback() {
		return $this->wrapped->rollback ();
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function createQuery($dql = '') {
		return $this->wrapped->createQuery ( $dql );
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function createNamedQuery($name) {
		return $this->wrapped->createNamedQuery ( $name );
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function createNativeQuery($sql, ResultSetMapping $rsm) {
		return $this->wrapped->createNativeQuery ( $sql, $rsm );
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function createNamedNativeQuery($name) {
		return $this->wrapped->createNamedNativeQuery ( $name );
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function createQueryBuilder() {
		return $this->wrapped->createQueryBuilder ();
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function getReference($entityName, $id) {
		return $this->wrapped->getReference ( $entityName, $id );
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function getPartialReference($entityName, $identifier) {
		return $this->wrapped->getPartialReference ( $entityName, $identifier );
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function close() {
		return $this->wrapped->close ();
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function copy($entity, $deep = false) {
		return $this->wrapped->copy ( $entity, $deep );
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function lock($entity, $lockMode, $lockVersion = null) {
		return $this->wrapped->lock ( $entity, $lockMode, $lockVersion );
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function find($entityName, $id, $lockMode = LockMode::NONE, $lockVersion = null) {
		return $this->wrapped->find ( $entityName, $id, $lockMode, $lockVersion );
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function flush($entity = null) {
		return $this->wrapped->flush ( $entity );
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function getEventManager() {
		return $this->wrapped->getEventManager ();
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function getConfiguration() {
		return $this->wrapped->getConfiguration ();
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function isOpen() {
		return $this->wrapped->isOpen ();
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function getUnitOfWork() {
		return $this->wrapped->getUnitOfWork ();
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function getHydrator($hydrationMode) {
		return $this->wrapped->getHydrator ( $hydrationMode );
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function newHydrator($hydrationMode) {
		return $this->wrapped->newHydrator ( $hydrationMode );
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function getProxyFactory() {
		return $this->wrapped->getProxyFactory ();
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function getFilters() {
		return $this->wrapped->getFilters ();
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function isFiltersStateClean() {
		return $this->wrapped->isFiltersStateClean ();
	}
	
	/**
	 *
	 * @ERROR!!!
	 *
	 */
	public function hasFilters() {
		return $this->wrapped->hasFilters ();
	}
}
