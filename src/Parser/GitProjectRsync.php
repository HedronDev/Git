<?php

namespace Hedron\Git\Parser;

use Hedron\Command\CommandStackInterface;
use Hedron\GitPostReceiveHandler;
use Hedron\Parser\BaseParser;

/**
 * @Hedron\Annotation\Parser(
 *   pluginId = "git_project_rsync"
 * )
 */
class GitProjectRsync extends BaseParser {

  /**
   * {@inheritdoc}
   */
  public function parse(GitPostReceiveHandler $handler, CommandStackInterface $commandStack) {
    $gitDir = $this->getGitDirectoryPath();
    $applicationDir = $gitDir . DIRECTORY_SEPARATOR . 'application';
    if (!file_exists($applicationDir) || !is_dir($applicationDir)) {
      throw new \Exception("The project is missing an \"application\" directory.");
    }
    $commandStack->addCommand("rsync -av $applicationDir/ {$this->getDataDirectoryPath()}");
    $commandStack->execute();
  }

}
