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
    $parse = FALSE;
    foreach ($handler->getCommittedFiles() as $file_name) {
      if (strpos($file_name, 'application/') === 0) {
        $parse = TRUE;
        break;
      }
    }
    if ($parse) {
      $commandStack->addCommand("rsync -av $applicationDir/ {$this->getDataDirectoryPath()}");
      $commandStack->addCommand("cd {$this->getDataDirectoryPath()}");
      $commandStack->addCommand("find -perm /200 -exec chmod g+w {} \\;");
      $commandStack->addCommand("find -type d -exec chmod g+s {} \\;");
      $commandStack->addCommand("chgrp -R www-data .");
      $commandStack->execute();
    }
  }

}
