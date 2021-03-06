<?php
declare(ENCODING = 'utf-8');

/*                                                                        *
 * This script belongs to the FLOW3 package "PHPCR".                      *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License as published by the *
 * Free Software Foundation, either version 3 of the License, or (at your *
 * option) any later version.                                             *
 *                                                                        *
 * This script is distributed in the hope that it will be useful, but     *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser       *
 * General Public License for more details.                               *
 *                                                                        *
 * You should have received a copy of the GNU Lesser General Public       *
 * License along with the script.                                         *
 * If not, see http://www.gnu.org/licenses/lgpl.html                      *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

/**
 * A QueryObjectModelFactory creates instances of the JCR query object model.
 *
 * Refer to QueryObjectModelInterface for a description of the query object model.
 *
 * @version $Id$
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 * @license http://opensource.org/licenses/bsd-license.php Simplified BSD License
 * @api
 */
interface PHPCR_Query_QOM_QueryObjectModelFactoryInterface extends PHPCR_Query_QOM_QueryObjectModelConstantsInterface {

	/**
	 * Creates a query with one or more selectors.
	 * If source is a selector, that selector is the default selector of the
	 * query. Otherwise the query does not have a default selector.
	 *
	 * If the query is invalid, this method throws an InvalidQueryException.
	 * See the individual QOM factory methods for the validity criteria of each
	 * query element.
	 *
	 * @param PHPCR_Query_QOM_SourceInterface $source the Selector or the node-tuple Source; non-null
	 * @param PHPCR_Query_QOM_ConstraintInterface $constraint the constraint, or null if none
	 * @param array $orderings zero or more orderings; null is equivalent to a zero-length array
	 * @param array $columns the columns; null is equivalent to a zero-length array
	 * @return PHPCR_Query_QOM_QueryObjectModelInterface the query; non-null
	 * @throws PHPCR_Query_InvalidQueryException if a particular validity test is possible on this method, the implemention chooses to perform that test and the parameters given fail that test. See the individual QOM factory methods for the validity criteria of each query element.
	 * @throws PHPCR_RepositoryException if another error occurs.
	 * @api
	 */
	public function createQuery(PHPCR_Query_QOM_SourceInterface $source, $constraint, array $orderings, array $columns);

	/**
	 * Selects a subset of the nodes in the repository based on node type.
	 *
	 * The query is invalid if $nodeTypeName or $selectorName is not a
	 * syntactically valid JCR name.
	 *
	 * The query is invalid if $selectorName is identical to the name of another
	 * selector in the query.
	 *
	 * If $nodeTypeName is a valid JCR name but not the name of a node type
	 * available in the repository, the query is valid but the selector selects
	 * no nodes.
	 *
	 * @param string $nodeTypeName the name of the required node type; non-null
	 * @param string $selectorName the selector name; non-null
	 * @return PHPCR_Query_QOM_SelectorInterface the selector; non-null
	 * @throws PHPCR_Query_InvalidQueryException if a particular validity test is possible on this method, the implemention chooses to perform that test (and not leave it until later) on createQuery, and the parameters given fail that test
	 * @throws PHPCR_RepositoryException if the operation otherwise fails
	 * @api
	 */
	public function selector($nodeTypeName, $selectorName = NULL);

	/**
	 * Performs a join between two node-tuple sources.
	 *
	 * The query is invalid if $left is the same source as $right.
	 *
	 * @param PHPCR_Query_QOM_SourceInterface $left the left node-tuple source; non-null
	 * @param PHPCR_Query_QOM_SourceInterface $right the right node-tuple source; non-null
	 * @param string $joinType one of QueryObjectModelConstants.JCR_JOIN_TYPE_*
	 * @param PHPCR_Query_QOM_JoinConditionInterface $joinCondition the join condition; non-null
	 * @return PHPCR_Query_QOM_JoinInterface the join; non-null
	 * @throws PHPCR_Query_InvalidQueryException if a particular validity test is possible on this method, the implemention chooses to perform that test (and not leave it until later) on createQuery, and the parameters given fail that test
	 * @throws PHPCR_RepositoryException if the operation otherwise fails
	 * @api
	 */
	public function join(PHPCR_Query_QOM_SourceInterface $left, PHPCR_Query_QOM_SourceInterface $right, $joinType, PHPCR_Query_QOM_JoinConditionInterface $joinCondition);

	/**
	 * Tests whether the value of a property in a first selector is equal to the
	 * value of a property in a second selector.
	 *
	 * The query is invalid if:
	 * * $selector1 is not the name of a selector in the query, or
	 * * $selector2 is not the name of a selector in the query, or
	 * * $selector1 is the same as $selector2, or
	 * * $property1 is not a syntactically valid JCR name, or
	 * * $property2 is not a syntactically valid JCR name, or
	 * * the value of $property1 is not the same property type as the value of
	 *   $property2, or
	 * * $property1 is a multi-valued property, or
	 * * $property2 is a multi-valued property, or
	 * * $property1 is a BINARY property, or
	 * * $property2 is a BINARY property.
	 *
	 * @param string $selector1Name the name of the first selector; non-null
	 * @param string $property1Name the property name in the first selector; non-null
	 * @param string $selector2Name the name of the second selector; non-null
	 * @param string $property2Name the property name in the second selector; non-null
	 * @return PHPCR_Query_QOM_EquiJoinConditionInterface the constraint; non-null
	 * @throws PHPCR_Query_InvalidQueryException if a particular validity test is possible on this method, the implemention chooses to perform that test (and not leave it until later) on createQuery, and the parameters given fail that test
	 * @throws PHPCR_RepositoryException if the operation otherwise fails
	 * @api
	 */
	public function equiJoinCondition($selector1Name, $property1Name, $selector2Name, $property2Name);

	/**
	 * Tests whether a first selector's node is the same as a node identified by
	 * relative path from a second selector's node.
	 *
	 * The query is invalid if:
	 * * $selector1 is not the name of a selector in the query, or
	 * * $selector2 is not the name of a selector in the query, or
	 * * $selector1 is the same as $selector2, or
	 * * $selector2Path is not a syntactically valid relative path.  Note, however,
	 *   that if the path is syntactically valid but does not identify a node
	 *   visible to the current session, the query is valid but the constraint
	 *   is not satisfied.
	 *
	 * @param string $selector1Name the name of the first selector; non-null
	 * @param string $selector2Name the name of the second selector; non-null
	 * @param string $selector2Path the path relative to the second selector; non-null
	 * @return PHPCR_Query_QOM_SameNodeJoinConditionInterface the constraint; non-null
	 * @throws PHPCR_Query_InvalidQueryException if a particular validity test is possible on this method, the implemention chooses to perform that test (and not leave it until later) on createQuery, and the parameters given fail that test
	 * @throws PHPCR_RepositoryException if the operation otherwise fails
	 * @api
	 */
	public function sameNodeJoinCondition($selector1Name, $selector2Name, $selector2Path = NULL);

	/**
	 * Tests whether a first selector's node is a child of a second selector's node.
	 *
	 * The query is invalid if:
	 * $childSelector is not the name of a selector in the query, or
	 * $parentSelector is not the name of a selector in the query, or
	 * $childSelector is the same as $parentSelector.
	 *
	 * @param string $childSelectorName the name of the child selector; non-null
	 * @param string $parentSelectorName the name of the parent selector; non-null
	 * @return PHPCR_Query_QOM_ChildNodeJoinConditionInterface the constraint; non-null
	 * @throws PHPCR_Query_InvalidQueryException if a particular validity test is possible on this method, the implemention chooses to perform that test (and not leave it until later) on createQuery, and the parameters given fail that test
	 * @throws PHPCR_RepositoryException if the operation otherwise fails
	 * @api
	 */
	public function childNodeJoinCondition($childSelectorName, $parentSelectorName);

	/**
	 * Tests whether a first selector's node is a descendant of a second selector's node.
	 *
	 * The query is invalid if:
	 * $descendantSelector is not the name of a selector in the query, or
	 * $ancestorSelector is not the name of a selector in the query, or
	 * $descendantSelector is the same as $ancestorSelector.
	 *
	 * @param string $descendantSelectorName the name of the descendant selector; non-null
	 * @param string $ancestorSelectorName the name of the ancestor selector; non-null
	 * @return PHPCR_Query_QOM_DescendantNodeJoinConditionInterface the constraint; non-null
	 * @throws PHPCR_Query_InvalidQueryException if a particular validity test is possible on this method, the implemention chooses to perform that test (and not leave it until later) on createQuery, and the parameters given fail that test
	 * @throws PHPCR_RepositoryException if the operation otherwise fails
	 * @api
	 */
	public function descendantNodeJoinCondition($descendantSelectorName, $ancestorSelectorName);

	/**
	 * Performs a logical conjunction of two other constraints.
	 *
	 * @param PHPCR_Query_QOM_ConstraintInterface $constraint1 the first constraint; non-null
	 * @param PHPCR_Query_QOM_ConstraintInterface $constraint2 the second constraint; non-null
	 * @return PHPCR_Query_QOM_AndInterface the And constraint; non-null
	 * @throws PHPCR_Query_InvalidQueryException if a particular validity test is possible on this method, the implemention chooses to perform that test (and not leave it until later) on createQuery, and the parameters given fail that test
	 * @throws PHPCR_RepositoryException if the operation otherwise fails
	 * @api
	 */
	public function _and(PHPCR_Query_QOM_ConstraintInterface $constraint1, PHPCR_Query_QOM_ConstraintInterface $constraint2);

	/**
	 * Performs a logical disjunction of two other constraints.
	 *
	 * @param PHPCR_Query_QOM_ConstraintInterface $constraint1 the first constraint; non-null
	 * @param PHPCR_Query_QOM_ConstraintInterface $constraint2 the second constraint; non-null
	 * @return PHPCR_Query_QOM_OrInterface the Or constraint; non-null
	 * @throws PHPCR_Query_InvalidQueryException if a particular validity test is possible on this method, the implemention chooses to perform that test (and not leave it until later) on createQuery, and the parameters given fail that test
	 * @throws PHPCR_RepositoryException if the operation otherwise fails
	 * @api
	 */
	public function _or(PHPCR_Query_QOM_ConstraintInterface $constraint1, PHPCR_Query_QOM_ConstraintInterface $constraint2);

	/**
	 * Performs a logical negation of another constraint.
	 *
	 * @param PHPCR_Query_QOM_ConstraintInterface $constraint the constraint to be negated; non-null
	 * @return PHPCR_Query_QOM_NotInterface the Not constraint; non-null
	 * @throws PHPCR_Query_InvalidQueryException if a particular validity test is possible on this method, the implemention chooses to perform that test (and not leave it until later) on createQuery, and the parameters given fail that test
	 * @throws PHPCR_RepositoryException if the operation otherwise fails
	 * @api
	 */
	public function not(PHPCR_Query_QOM_ConstraintInterface $constraint);

	/**
	 * Filters node-tuples based on the outcome of a binary operation.
	 *
	 * @param PHPCR_Query_QOM_DynamicOperandInterface $operand1 the first operand; non-null
	 * @param string $operator the operator; one of QueryObjectModelConstants.JCR_OPERATOR_*
	 * @param PHPCR_Query_QOM_StaticOperandInterface $operand2 the second operand; non-null
	 * @return PHPCR_Query_QOM_ComparisonInterface the constraint; non-null
	 * @throws PHPCR_Query_InvalidQueryException if a particular validity test is possible on this method, the implemention chooses to perform that test (and not leave it until later) on createQuery, and the parameters given fail that test
	 * @throws PHPCR_RepositoryException if the operation otherwise fails
	 * @api
	 */
	public function comparison(PHPCR_Query_QOM_DynamicOperandInterface $operand1, $operator, PHPCR_Query_QOM_StaticOperandInterface $operand2);

	/**
	 * Tests the existence of a property in the specified or default selector.
	 *
	 * The query is invalid if:
	 *
	 * $propertyName is not a syntactically valid JCR name, or
	 * $selectorName is not the name of a selector in the query.
	 *
	 * @param string $propertyName the property name; non-null
	 * @param string $selectorName the selector name; non-null
	 * @return PHPCR_Query_QOM_PropertyExistenceInterface the constraint; non-null
	 * @throws PHPCR_Query_InvalidQueryException if a particular validity test is possible on this method, the implemention chooses to perform that test (and not leave it until later) on createQuery, and the parameters given fail that test
	 * @throws PHPCR_RepositoryException if the operation otherwise fails
	 * @api
	 */
	public function propertyExistence($propertyName, $selectorName = NULL);

	/**
	 * Performs a full-text search against the specified or default selector.
	 *
	 * The query is invalid if:
	 * $selectorName is not the name of a selector in the query, or
	 * $propertyName is specified but is not a syntactically valid JCR name, or
	 * $fullTextSearchExpression does not conform to the full text search grammar.
	 *
	 * If $propertyName is specified but, for a node-tuple, the selector node
	 * does not have a property named $propertyName, the query is valid but the
	 * constraint is not satisfied.
	 *
	 * @param string $propertyName the property name, or null to search all full-text indexed properties of the node (or node subgraph, in some implementations);
	 * @param string $fullTextSearchExpression the full-text search expression; non-null
	 * @param string $selectorName the selector name; non-null
	 * @return PHPCR_Query_QOM_FullTextSearchInterface the constraint; non-null
	 * @throws PHPCR_Query_InvalidQueryException if a particular validity test is possible on this method, the implemention chooses to perform that test (and not leave it until later) on createQuery, and the parameters given fail that test
	 * @throws PHPCR_RepositoryException if the operation otherwise fails
	 * @api
	 */
	public function fullTextSearch($propertyName, $fullTextSearchExpression, $selectorName = NULL);

	/**
	 * Tests whether a node in the specified or default selector is reachable by
	 * a specified absolute path.
	 *
	 * The query is invalid if:
	 * $selectorName is not the name of a selector in the query, or
	 * $path is not a syntactically valid absolute path.  Note,
	 * however, that if the path is syntactically valid but does not identify a
	 * node in the repository (or the node is not visible to this session,
	 * because of access control constraints), the query is valid but the
	 * constraint is not satisfied.
	 *
	 * @param string $path an absolute path; non-null
	 * @param string $selectorName the selector name; non-null
	 * @return PHPCR_Query_QOM_SameNodeInterface the constraint; non-null
	 * @throws PHPCR_Query_InvalidQueryException if the query is invalid
	 * @throws PHPCR_RepositoryException if the operation otherwise fails
	 * @api
	 */
	public function sameNode($path, $selectorName = NULL);

	/**
	 * Tests whether a node in the specified or default selector is a child of a
	 * node reachable by a specified absolute path.
	 *
	 * The query is invalid if:
	 * $selectorName is not the name of a selector in the query, or
	 * $path is not a syntactically valid absolute path.  Note,
	 * however, that if the path is syntactically valid but does not identify a
	 * node in the repository (or the node is not visible to this session,
	 * because of access control constraints), the query is valid but the
	 * constraint is not satisfied.
	 *
	 * @param string $path an absolute path; non-null
	 * @param string $selectorName the selector name; non-null
	 * @return PHPCR_Query_QOM_ChildNodeInterface the constraint; non-null
	 * @throws PHPCR_Query_InvalidQueryException if a particular validity test is possible on this method, the implemention chooses to perform that test (and not leave it until later) on createQuery, and the parameters given fail that test
	 * @throws PHPCR_RepositoryException if the operation otherwise fails
	 * @api
	 */
	public function childNode($path, $selectorName = NULL);

	/**
	 * Tests whether a node in the specified or default selector is a descendant
	 * of a node reachable by a specified absolute path.
	 *
	 * The query is invalid if:
	 * $selectorName is not the name of a selector in the query, or
	 * $path is not a syntactically valid absolute path.  Note,
	 * however, that if the path is syntactically valid but does not identify a
	 * node in the repository (or the node is not visible to this session,
	 * because of access control constraints), the query is valid but the
	 * constraint is not satisfied.
	 *
	 * @param string $path an absolute path; non-null
	 * @param string $selectorName the selector name; non-null
	 * @return PHPCR_Query_QOM_DescendantNodeInterface the constraint; non-null
	 * @throws PHPCR_Query_InvalidQueryException if a particular validity test is possible on this method, the implemention chooses to perform that test (and not leave it until later) on createQuery, and the parameters given fail that test
	 * @throws PHPCR_RepositoryException if the operation otherwise fails
	 * @api
	 */
	public function descendantNode($path, $selectorName = NULL);

	/**
	 * Evaluates to the value (or values, if multi-valued) of a property in the
	 * specified or default selector.
	 *
	 * The query is invalid if:
	 * $selectorName is not the name of a selector in the query, or
	 * $propertyName is not a syntactically valid JCR name.
	 *
	 * @param string $propertyName the property name; non-null
	 * @param string $selectorName the selector name; non-null
	 * @return PHPCR_Query_QOM_PropertyValueInterface the operand; non-null
	 * @throws PHPCR_Query_InvalidQueryException if the query is invalid
	 * @throws PHPCR_RepositoryException if the operation otherwise fails
	 * @api
	 */
	public function propertyValue($propertyName, $selectorName = NULL);

	/**
	 * Evaluates to the length (or lengths, if multi-valued) of a property.
	 *
	 * @param PHPCR_Query_QOM_PropertyValueInterface $propertyValue the property value for which to compute the length; non-null
	 * @return PHPCR_Query_QOM_LengthInterface the operand; non-null
	 * @throws PHPCR_Query_InvalidQueryException if a particular validity test is possible on this method, the implemention chooses to perform that test (and not leave it until later) on createQuery, and the parameters given fail that test
	 * @throws PHPCR_RepositoryException if the operation otherwise fails
	 * @api
	 */
	public function length(PHPCR_Query_QOM_PropertyValueInterface $propertyValue);

	/**
	 * Evaluates to a NAME value equal to the prefix-qualified name of a node in
	 * the specified or default selector.
	 *
	 * The query is invalid if $selectorName is not the name of a selector in
	 * the query.
	 *
	 * @param string $selectorName the selector name; non-null
	 * @return PHPCR_Query_QOM_NodeNameInterface the operand; non-null
	 * @throws PHPCR_Query_InvalidQueryException if a particular validity test is possible on this method, the implemention chooses to perform that test (and not leave it until later) on createQuery, and the parameters given fail that test
	 * @throws PHPCR_RepositoryException if the operation otherwise fails
	 * @api
	 */
	public function nodeName($selectorName = NULL);

	/**
	 * Evaluates to a NAME value equal to the local (unprefixed) name of a node in the specified or default selector.
	 *
	 * The query is invalid if $selectorName is not the name of a selector in
	 * the query.
	 *
	 * @param string $selectorName the selector name; non-null
	 * @return PHPCR_Query_QOM_NodeLocalNameInterface the operand; non-null
	 * @throws PHPCR_Query_InvalidQueryException if the query is invalid
	 * @throws PHPCR_RepositoryException if the operation otherwise fails
	 * @api
	 */
	public function nodeLocalName($selectorName = NULL);

	/**
	 * Evaluates to a DOUBLE value equal to the full-text search score of a node in the specified or default selector.
	 *
	 * The query is invalid if $selectorName is not the name of a selector in
	 * the query.
	 *
	 * @param string $selectorName the selector name; non-null
	 * @return PHPCR_Query_QOM_FullTextSearchScoreInterface the operand; non-null
	 * @throws PHPCR_Query_InvalidQueryException if a particular validity test is possible on this method, the implemention chooses to perform that test (and not leave it until later) on createQuery, and the parameters given fail that test
	 * @throws PHPCR_RepositoryException if the operation otherwise fails
	 * @api
	 */
	public function fullTextSearchScore($selectorName = NULL);

	/**
	 * Evaluates to the lower-case string value (or values, if multi-valued) of an operand.
	 *
	 * @param PHPCR_Query_QOM_DynamicOperandInterface $operand the operand whose value is converted to a lower-case string; non-null
	 * @return PHPCR_Query_QOM_LowerCaseInterface the operand; non-null
	 * @throws PHPCR_Query_InvalidQueryException if a particular validity test is possible on this method, the implemention chooses to perform that test (and not leave it until later) on createQuery, and the parameters given fail that test
	 * @throws PHPCR_RepositoryException if the operation otherwise fails
	 * @api
	 */
	public function lowerCase(PHPCR_Query_QOM_DynamicOperandInterface $operand);

	/**
	 * Evaluates to the upper-case string value (or values, if multi-valued) of an operand.
	 *
	 * @param PHPCR_Query_QOM_DynamicOperandInterface $operand the operand whose value is converted to a upper-case string; non-null
	 * @return PHPCR_Query_QOM_UpperCaseInterface the operand; non-null
	 * @throws PHPCR_Query_InvalidQueryException if a particular validity test is possible on this method, the implemention chooses to perform that test (and not leave it until later) on createQuery, and the parameters given fail that test
	 * @throws PHPCR_RepositoryException if the operation otherwise fails
	 * @api
	 */
	public function upperCase(PHPCR_Query_QOM_DynamicOperandInterface $operand);

	/**
	 * Evaluates to the value of a bind variable.
	 *
	 * The query is invalid if $bindVariableName is not a valid JCR prefix.
	 *
	 * @param string $bindVariableName the bind variable name; non-null
	 * @return PHPCR_Query_QOM_BindVariableValueInterface the operand; non-null
	 * @throws PHPCR_Query_InvalidQueryException if a particular validity test is possible on this method, the implemention chooses to perform that test (and not leave it until later) on createQuery, and the parameters given fail that test
	 * @throws PHPCR_RepositoryException if the operation otherwise fails
	 * @api
	 */
	public function bindVariable($bindVariableName);

	/**
	 * Evaluates to a literal value.
	 *
	 * The query is invalid if no value is bound to $literalValue.
	 *
	 * @param PHPCR_ValueInterface $literalValue the value
	 * @return PHPCR_ValueInterface the operand; non-null
	 * @throws PHPCR_Query_InvalidQueryException if a particular validity test is possible on this method, the implemention chooses to perform that test (and not leave it until later) on createQuery, and the parameters given fail that test
	 * @throws PHPCR_RepositoryException if the operation otherwise fails
	 * @api
	 */
	public function literal(PHPCR_ValueInterface $literalValue);

	/**
	 * Orders by the value of the specified operand, in ascending order.
	 *
	 * The query is invalid if $operand does not evaluate to a scalar value.
	 *
	 * @param PHPCR_Query_QOM_DynamicOperandInterface $operand the operand by which to order; non-null
	 * @return PHPCR_Query_QOM_OrderingInterface the ordering
	 * @throws PHPCR_Query_InvalidQueryException if the query is invalid
	 * @throws PHPCR_RepositoryException if the operation otherwise fails
	 * @api
	 */
	public function ascending(PHPCR_Query_QOM_DynamicOperandInterface $operand);

	/**
	 * Orders by the value of the specified operand, in descending order.
	 *
	 * The query is invalid if $operand does not evaluate to a scalar value.
	 *
	 * @param PHPCR_Query_QOM_DynamicOperandInterface $operand the operand by which to order; non-null
	 * @return PHPCR_Query_QOM_OrderingInterface the ordering
	 * @throws PHPCR_Query_InvalidQueryException if the query is invalid
	 * @throws PHPCR_RepositoryException if the operation otherwise fails
	 * @api
	 */
	public function descending(PHPCR_Query_QOM_DynamicOperandInterface $operand);

	/**
	 * Identifies a property in the specified or default selector to include in
	 * the tabular view of query results.
	 * The column name is the property name if not given.
	 *
	 * The query is invalid if:
	 * $selectorName is not the name of a selector in the query, or
	 * $propertyName is specified but it is not a syntactically valid JCR name, or
	 * $propertyName is specified but does not evaluate to a scalar value, or
	 * $propertyName is specified but $columnName is omitted, or
	 * $propertyName is omitted but $columnName is specified, or
	 * the columns in the tabular view are not uniquely named, whether those
	 * column names are specified by $columnName (if $propertyName is specified)
	 * or generated as described above (if $propertyName is omitted).
	 *
	 * If $propertyName is specified but, for a node-tuple, the selector node
	 * does not have a property named $propertyName, the query is valid and the
	 * column has null value.
	 *
	 * @param string $propertyName the property name, or null to include a column for each single-value non-residual property of the selector's node type
	 * @param string $columnName the column name; must be null if propertyName is null
	 * @param string $selectorName the selector name; non-null
	 * @return PHPCR_Query_QOM_ColumnInterface the column; non-null
	 * @throws PHPCR_Query_InvalidQueryException if the query has no default selector or is otherwise invalid
	 * @throws PHPCR_RepositoryException if the operation otherwise fails
	 * @api
	 */
	public function column($propertyName, $columnName = NULL, $selectorName = NULL);

}

?>