<?php

namespace Hedron\Git\ProjectType;

use EclipseGc\Plugin\Discovery\PluginDefinitionSet;
use Hedron\Annotation\ProjectType;
use Hedron\ParserDictionary;
use Hedron\ProjectType\ProjectTypeBase;

/**
 * @ProjectType(
 *   pluginId = "git",
 *   label = "Git"
 * )
 */
class Git extends ProjectTypeBase {

  /**
   * {@inheritdoc}
   */
  public static function getFileParsers(ParserDictionary $dictionary) : PluginDefinitionSet {
    $parsers = [
      'git_pull',
      'ensure_shared_volumes',
      'git_project_rsync',
      'docker_compose',
      'docker_compose_ps',
    ];
    $definitions = [];
    foreach ($parsers as $parser) {
      $definitions[] = $dictionary->getDefinition($parser);
    }
    return new PluginDefinitionSet(... $definitions);
  }

}
