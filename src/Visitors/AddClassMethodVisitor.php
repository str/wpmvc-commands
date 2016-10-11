<?php

namespace WPMVC\Commands\Visitors;

use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Param;
use PhpParser\Node\Name;
use PhpParser\Comment;

/**
 * Visits the node to add a new class method.
 *
 * @link https://github.com/nikic/PHP-Parser/blob/master/doc/2_Usage_of_basic_components
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\Commands
 * @version 1.0.0
 */
class AddClassMethodVisitor extends NodeVisitorAbstract
{
    /**
     * Method's name to add.
     * @since 1.0.0
     * @var string
     */
    protected $methodName;

    /**
     * Method's parameters.
     * @since 1.0.0
     * @var array
     */
    protected $params = [];

    /**
     * Default constructor.
     * 
     * @param string $methodName Method name.
     * @param array  $params     Method parameters.
     */
    public function __construct($methodName, $params = [])
    {
        $this->methodName = $methodName;
        $this->params = $params;
    }

    /**
     * On leave node event.
     * Adds extra statement before parser leaves node.
     * @since 1.0.0
     *
     * @param Node $node Node to check.
     */
    public function leaveNode(Node $node)
    {
        if ($node instanceof Class_) {
            // Build params
            $params = [];
            foreach ($this->params as $param) {
                if (is_array($param)) {
                    $params[] = isset($param['type']) && isset($param['name'])
                        ? new Param($param['name'], null, new Name([$param['type']]))
                        : new Param($param[0]);
                } else {
                    $params[] = new Param($param);
                }
            }
            // ADD statement
            $node->stmts[] = new ClassMethod(
                $this->methodName,
                [
                    'type'      => 1,
                    'params'    => $params,
                ],
                [
                    'comments'  => [new Comment(sprintf('// Ayuco: addition %s', date('Y-m-d h:i a')))]
                ]
            );
        }
    }
}