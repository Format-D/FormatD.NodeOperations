<?php
namespace FormatD\NodeOperations\Command;

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Cli\CommandController;

/**
 * @Flow\Scope("singleton")
 */
class NodeOperationsCommandController extends CommandController {

	/**
	 * @Flow\Inject
	 * @var \Neos\Flow\Utility\Now
	 */
	protected $now;

	/**
	 * @Flow\Inject
	 * @var \Neos\Neos\Service\NodeOperations
	 */
	protected $nodeOperationService;

	/**
	 * @Flow\Inject
	 * @var \Neos\ContentRepository\Domain\Service\ContextFactoryInterface
	 */
	protected $contextFactory;

	/**
	 * Move node with $nodeIdentifier before, into or after targetNode with $targetNodeIdentifier
	 *
	 * @param string $nodeIdentifier
	 * @param string $targetNodeIdentifier
	 * @param string $position where the node should be added (allowed: before, into, after)
	 * @return void
	 */
	public function moveCommand($nodeIdentifier, $targetNodeIdentifier, $position)
	{
		if (!in_array($position, ['before', 'into', 'after'], TRUE)) {
			$this->outputLine('Unrecognised position: "' . $position . '" (possible options: before, into, after)');
			return;
		}

		$nodeContext = $this->getContext();

		$node = $nodeContext->getNodeByIdentifier($nodeIdentifier);
		if (!$node) {
			$this->outputLine('Node with identifier "' . $nodeIdentifier . '" not found!');
			return;
		}

		$targetNode = $nodeContext->getNodeByIdentifier($targetNodeIdentifier);
		if (!$targetNode) {
			$this->outputLine('TargetNode with identifier "' . $targetNodeIdentifier . '" not found!');
			return;
		}

		$this->outputLine('Moving node "' . $node->getLabel() . '" ' . $position . ' node "' . $targetNode->getLabel() . '"...');

		$this->nodeOperationService->move($node, $targetNode, $position);

		$this->outputLine('Done!');
	}

	/**
	 * Copy node with $nodeIdentifier before, into or after targetNode with $targetNodeIdentifier
	 *
	 * @param string $nodeIdentifier
	 * @param string $targetNodeIdentifier
	 * @param string $position where the new node should be created (allowed: before, into, after)
	 * @param string $name of the new node
	 * @return void
	 */
	public function copyCommand($nodeIdentifier, $targetNodeIdentifier, $position, $name = NULL)
	{
		if (!in_array($position, ['before', 'into', 'after'], TRUE)) {
			$this->outputLine('Unrecognised position: "' . $position . '" (possible options: before, into, after)');
			return;
		}

		$nodeContext = $this->getContext();

		$node = $nodeContext->getNodeByIdentifier($nodeIdentifier);
		if (!$node) {
			$this->outputLine('Node with identifier "' . $nodeIdentifier . '" not found!');
			return;
		}

		$targetNode = $nodeContext->getNodeByIdentifier($targetNodeIdentifier);
		if (!$targetNode) {
			$this->outputLine('TargetNode with identifier "' . $targetNodeIdentifier . '" not found!');
			return;
		}

		$this->outputLine('Copying node "' . $node->getLabel() . '" ' . $position . ' node "' . $targetNode->getLabel() . '"...');

		if ($name) {
			$this->outputLine('New NodeName will be: "' . $name . '"');
		}

		$this->nodeOperationService->copy($node, $targetNode, $position, $name);

		$this->outputLine('Done!');
	}

	/**
	 * @return \Neos\ContentRepository\Domain\Service\Context
	 */
	protected function getContext()
	{
		if (method_exists($this->now, '_activateDependency')) {
			$this->now->_activateDependency();
		}

		return $this->contextFactory->create(array(
			'workspaceName' => 'live',
			'currentDateTime' => $this->now,
		));
	}
}